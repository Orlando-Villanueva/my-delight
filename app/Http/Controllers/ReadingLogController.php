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
     */
    public function create()
    {
        $books = $this->bibleReferenceService->listBibleBooks();
        
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
                return view('partials.reading-log-success', compact('log'));
            }

            return redirect()->route('dashboard')->with('success', 'Reading logged successfully!');

        } catch (ValidationException $e) {
            if ($request->header('HX-Request')) {
                return response()
                    ->view('partials.form-errors', ['errors' => $e->errors()])
                    ->setStatusCode(422);
            }
            
            return back()->withErrors($e->errors())->withInput();

        } catch (InvalidArgumentException $e) {
            $error = ['chapter_input' => $e->getMessage()];
            
            if ($request->header('HX-Request')) {
                return response()
                    ->view('partials.form-errors', ['errors' => $error])
                    ->setStatusCode(422);
            }
            
            return back()->withErrors($error)->withInput();

        } catch (QueryException $e) {
            // Handle unique constraint violation (duplicate reading log)
            if ($e->getCode() === '23000') {
                $error = ['chapter_input' => 'You have already logged one or more of these chapters for today.'];
                
                if ($request->header('HX-Request')) {
                    return response()
                        ->view('partials.form-errors', ['errors' => $error])
                        ->setStatusCode(422);
                }
                
                return back()->withErrors($error)->withInput();
            }
            
            // Re-throw if it's a different database error
            throw $e;
        }
    }

    /**
     * Get chapters for a specific book (HTMX endpoint).
     */
    public function getBookChapters(Request $request, int $bookId)
    {
        if (!$this->bibleReferenceService->validateBookId($bookId)) {
            return response()->json(['error' => 'Invalid book ID'], 400);
        }

        $chapterCount = $this->bibleReferenceService->getBookChapterCount($bookId);
        $chapters = range(1, $chapterCount);

        if ($request->header('HX-Request')) {
            return view('partials.chapter-options', compact('chapters'));
        }

        return response()->json(['chapters' => $chapters]);
    }
} 