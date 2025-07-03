<?php

namespace App\Http\Controllers;

use App\Services\BibleReferenceService;
use App\Services\ReadingLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

class ReadingLogController extends Controller
{
    public function __construct(
        private BibleReferenceService $bibleReferenceService,
        private ReadingLogService $readingLogService
    ) {}

    /**
     * Show the form for creating a new reading log.
     * Supports both HTMX content loading and direct page access.
     */
    public function create(Request $request)
    {
        // TEMPORARY: Test French support by loading French book names
        // You can change 'fr' to 'en' to switch back to English
        $locale = $request->get('lang', 'en'); // Allow testing via ?lang=fr
        $books = $this->bibleReferenceService->listBibleBooks(null, $locale);
        
        // Return partial view for HTMX requests (seamless content loading)
        if ($request->header('HX-Request')) {
            return view('partials.reading-log-form', compact('books'));
        }
        
        // Return full page for direct access (graceful degradation)
        return view('logs.create', compact('books'));
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
                'notes_text' => 'nullable|string|max:500'
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

            // Return appropriate response based on request type
            if ($request->header('HX-Request')) {
                return view('partials.reading-log-success-message', compact('log'));
            }

            return redirect()->route('dashboard')->with('success', 'Reading logged successfully!');

        } catch (ValidationException $e) {
            if ($request->header('HX-Request')) {
                return response()
                    ->view('partials.validation-errors', ['errors' => $e->errors()])
                    ->setStatusCode(422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (InvalidArgumentException $e) {
            // Wrap message in array to match ValidationException structure
            $error = ['chapter_input' => [$e->getMessage()]];
            
            if ($request->header('HX-Request')) {
                return response()
                    ->view('partials.validation-errors', ['errors' => $error])
                    ->setStatusCode(422);
            }
            
            return back()->withErrors($error)->withInput();

        } catch (QueryException $e) {
            // Handle unique constraint violation (duplicate reading log)
            if ($e->getCode() === '23000') {
                // Duplicate entry message wrapped in array to align with view expectations
                $error = ['chapter_input' => ['You have already logged one or more of these chapters for today.']];
                
                if ($request->header('HX-Request')) {
                    return response()
                        ->view('partials.validation-errors', ['errors' => $error])
                        ->setStatusCode(422);
                }
                
                return back()->withErrors($error)->withInput();
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
        
        // Get reading logs with pagination
        $logsQuery = $user->readingLogs()->recentFirst();
        
        if ($startDate) {
            $logsQuery->dateRange($startDate);
        }
        
        $logs = $logsQuery->paginate(5)->withQueryString();
        
        // Return partial view for HTMX requests
        if ($request->header('HX-Request')) {
            // If it's an infinite scroll request (has page parameter), return just the items
            if ($request->has('page') && $request->get('page') > 1) {
                return view('partials.reading-log-infinite-scroll', compact('logs', 'filter'));
            }
            
            // If it's a filter request (has filter parameter), return just the content
            if ($request->has('filter')) {
                return view('partials.reading-log-list', compact('logs', 'filter'));
            }
            
            // Otherwise, return the page container for navigation (no sidebar)
            return view('partials.logs-page', compact('logs', 'filter'));
        }
        
        // Return full page for direct access (graceful degradation)
        return view('logs.index', compact('logs', 'filter'));
    }
} 