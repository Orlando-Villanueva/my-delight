<?php

namespace App\Http\Controllers;

use App\Services\BibleReferenceService;
use App\Services\ReadingLogService;
use App\Services\ReadingFormService;
use App\Services\UserStatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class ReadingLogController extends Controller
{
    public function __construct(
        private BibleReferenceService $bibleReferenceService,
        private ReadingLogService $readingLogService,
        private ReadingFormService $readingFormService,
        private UserStatisticsService $userStatisticsService
    ) {}

    /**
     * Show the form for creating a new reading log.
     * Returns the modal form partial for HTMX loading.
     */
    public function create(Request $request)
    {
        // TEMPORARY: Test French support by loading French book names
        // You can change 'fr' to 'en' to switch back to English
        $locale = $request->get('lang', 'en'); // Allow testing via ?lang=fr
        $books = $this->bibleReferenceService->listBibleBooks(null, $locale);

        // Pass empty error bag for consistent template behavior
        $errors = new MessageBag();

        // Get form context data (yesterday logic, streak info)
        $formContext = $this->readingFormService->getFormContextData($request->user());

        // Always return partial view for modal display
        return view('partials.reading-log-form', array_merge(
            compact('books', 'errors'),
            $formContext
        ));
    }

    /**
     * Store a newly created reading log.
     */
    public function store(Request $request)
    {
        try {
            // Late Logging Grace: Only allow today or yesterday
            $today = today()->toDateString();
            $yesterday = today()->subDay()->toDateString();

            $validated = $request->validate([
                'book_id' => 'required|integer|min:1|max:66',
                'chapter_input' => ['required', 'string', 'regex:/^(\d+|\d+-\d+)$/'],
                'date_read' => "required|date|in:{$today},{$yesterday}",
                'notes_text' => 'nullable|string|max:1000'
            ]);

            // Parse chapter input (single or range)
            $chapterData = $this->bibleReferenceService->parseChapterInput($validated['chapter_input']);

            // Validate chapter range using service
            if (!$this->bibleReferenceService->validateChapterRange(
                $validated['book_id'],
                $chapterData['start'],
                $chapterData['end']
            )) {
                throw ValidationException::withMessages([
                    'chapter_input' => 'Invalid chapter range for the selected book.'
                ]);
            }

            // Format passage text using service
            $validated['passage_text'] = $this->bibleReferenceService->formatBibleReferenceRange(
                $validated['book_id'],
                $chapterData['start'],
                $chapterData['end']
            );

            // Add chapter data for service
            if ($chapterData['type'] === 'range') {
                $validated['chapters'] = $chapterData['chapters'];
            } else {
                $validated['chapter'] = $chapterData['start'];
            }

            // Create reading log using service
            $log = $this->readingLogService->logReading($request->user(), $validated);

            // Always return HTMX success partial
            return view('partials.reading-log-success-message', compact('log'));
        } catch (ValidationException $e) {
            // Get books data for form re-display
            $books = $this->bibleReferenceService->listBibleBooks(null, 'en');

            // Pass errors directly to the view
            $errors = new MessageBag($e->errors());

            // Get form context data (yesterday logic, streak info)
            $formContext = $this->readingFormService->getFormContextData($request->user());

            // Return form with validation errors
            return view('partials.reading-log-form', array_merge(
                compact('books', 'errors'),
                $formContext
            ));
        } catch (InvalidArgumentException $e) {
            // Get books data for form re-display
            $books = $this->bibleReferenceService->listBibleBooks(null, 'en');

            // Create error bag for form display
            $errors = new MessageBag(['chapter_input' => [$e->getMessage()]]);

            // Get form context data (yesterday logic, streak info)
            $formContext = $this->readingFormService->getFormContextData($request->user());

            // Return form with validation errors
            return view('partials.reading-log-form', array_merge(
                compact('books', 'errors'),
                $formContext
            ));
        } catch (QueryException $e) {
            // Handle unique constraint violation (duplicate reading log)
            if ($e->getCode() === '23000') {
                // Get books data for form re-display
                $books = $this->bibleReferenceService->listBibleBooks(null, 'en');

                // Create error bag for form display
                $errors = new MessageBag(['chapter_input' => ['You have already logged one or more of these chapters for today.']]);

                // Get form context data (yesterday logic, streak info)
                $formContext = $this->readingFormService->getFormContextData($request->user());

                // Return form with validation errors
                return view('partials.reading-log-form', array_merge(
                    compact('books', 'errors'),
                    $formContext
                ));
            }

            // Re-throw if it's a different database error
            throw $e;
        }
    }

    /**
     * Display a listing of reading logs with infinite scroll pagination.
     * Supports both HTMX content loading and direct page access.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get reading logs - all records, chronologically ordered
        $allLogs = $user->readingLogs()->recentFirst();

        // Group by date_read to show all readings for each day
        $groupedLogs = $allLogs->get()
            ->groupBy(function ($log) {
                return $log->date_read->format('Y-m-d'); // Group by date only
            })
            ->map(function ($logsForDay) {
                // Deduplicate readings within each day by passage + date + created_at (same session)
                $deduplicated = $logsForDay->groupBy(function ($log) {
                    return $log->passage_text . '|' . $log->date_read . '|' . $log->created_at->format('Y-m-d H:i:s');
                })
                    ->map(function ($group) {
                        return $group->first(); // Take the first entry from each group
                    })
                    ->values();

                // Add time_ago to each log and sort readings within each day by created_at (newest first)
                return $deduplicated->map(function ($log) {
                    // Use the service's smart time calculation for consistent display across all components
                    $log->time_ago = $this->userStatisticsService->calculateSmartTimeAgo($log);
                    $log->logged_time_ago = $this->userStatisticsService->formatTimeAgo($log->created_at);
                    return $log;
                })->sortByDesc('created_at')->values();
            })
            ->sortByDesc(function ($logsForDay, $date) {
                return $date; // Sort days by date (newest first)
            });

        // Manual pagination by days (not individual logs)
        $perPage = 8; // Number of days to show per page
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        // Paginate the day groups
        $paginatedDays = $groupedLogs->slice($offset, $perPage);
        $logs = new LengthAwarePaginator(
            $paginatedDays,
            $groupedLogs->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        $logs->withQueryString();

        // Return appropriate view based on request type
        if ($request->header('HX-Request')) {
            // If it's an infinite scroll request (has page parameter), return just the items
            if ($request->has('page') && $request->get('page') > 1) {
                return view('partials.reading-log-infinite-scroll', compact('logs'));
            }

            // Otherwise, return the page container for HTMX navigation
            return view('partials.logs-page', compact('logs'));
        }

        // Return full page for direct access (browser URL)
        return view('logs.index', compact('logs'));
    }
}
