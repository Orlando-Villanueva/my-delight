<?php

namespace App\Http\Controllers;

use App\Services\BibleReferenceService;
use App\Services\ReadingLogService;
use App\Services\ReadingFormService;
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
        private ReadingFormService $readingFormService
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
     * Display a listing of reading logs with filtering options.
     * Supports both HTMX content loading and direct page access.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get filter parameter (days back)
        $filter = $request->get('filter', '7'); // Default to last 7 days
        $validFilters = ['7', '30', '90', 'all'];
        
        if (!in_array($filter, $validFilters)) {
            $filter = '7';
        }
        
        // Calculate date range based on filter
        $startDate = null;
        if ($filter !== 'all') {
            $startDate = now()->subDays((int)$filter)->toDateString();
        }
        
        // Get reading logs and group by reading session to avoid duplicates
        $allLogs = $user->readingLogs()->recentFirst();
        
        if ($startDate) {
            $allLogs->dateRange($startDate);
        }
        
        // Group by passage_text + date_read + created_at (same reading session)
        $groupedLogs = $allLogs->get()
            ->groupBy(function ($log) {
                return $log->passage_text . '|' . $log->date_read . '|' . $log->created_at->format('Y-m-d H:i:s');
            })
            ->map(function ($group) {
                return $group->first(); // Take the first entry from each group
            })
            ->values()
            ->sortByDesc('created_at');
        
        // Manual pagination
        $perPage = 5;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedLogs = $groupedLogs->slice($offset, $perPage);
        $logs = new LengthAwarePaginator(
            $paginatedLogs,
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
                return view('partials.reading-log-infinite-scroll', compact('logs', 'filter'));
            }
            
            // If it's a filter request (has filter parameter), return just the content
            if ($request->has('filter')) {
                return view('partials.reading-log-list', compact('logs', 'filter'));
            }
            
            // Otherwise, return the page container for HTMX navigation
            return view('partials.logs-page', compact('logs', 'filter'));
        }
        
        // Return full page for direct access (browser URL)
        return view('logs.index', compact('logs', 'filter'));
    }
} 