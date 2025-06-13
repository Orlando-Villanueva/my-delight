# HTMX Implementation Guide

## Overview

This guide outlines the HTMX implementation patterns for the Bible Reading Habit Builder. HTMX serves as the primary mechanism for server-driven UI updates, following the principle that **state lives on the server** and HTML fragments represent that state.

## Core HTMX Principles

### Server-Driven State Pattern

HTMX follows a server-centric approach where:
- **State Management**: All application state resides on the server
- **HTML as State Representation**: Server returns HTML fragments that represent current state
- **Minimal Client Logic**: Client handles only presentation and user interaction
- **RESTful Design**: Embraces true REST principles with HATEOAS

```html
<!-- Example: Reading log list that updates server-side -->
<div id="reading-logs">
    <div hx-get="/logs" hx-trigger="load" hx-target="#reading-logs">
        Loading reading logs...
    </div>
</div>
```

## HTML Fragment Response Structures

### Standard Response Patterns

#### 1. Partial Content Updates
```php
// Controller method returning HTML fragment
public function getReadingLogs(Request $request)
{
    $logs = ReadingLog::where('user_id', auth()->id())
                     ->orderBy('date_read', 'desc')
                     ->get();
    
    // Return partial view for HTMX
    return view('partials.reading-logs', compact('logs'));
}
```

```html
<!-- partials/reading-logs.blade.php -->
@forelse($logs as $log)
    <div class="reading-log-item" data-log-id="{{ $log->id }}">
        <div>
            <div>
                <h3>{{ $log->passage_text }}</h3>
                <p>{{ $log->date_read->format('M d, Y') }}</p>
                @if($log->notes_text)
                    <p>{{ $log->notes_text }}</p>
                @endif
            </div>
            <button hx-delete="/logs/{{ $log->id }}" 
                    hx-target="closest .reading-log-item"
                    hx-swap="outerHTML"
                    hx-confirm="Delete this reading log?">
                Delete
            </button>
        </div>
    </div>
@empty
    <div>
        <p>No reading logs yet. Start your Bible reading journey!</p>
        <a href="/logs/create">Log your first reading</a>
    </div>
@endforelse
```

## Error Handling Patterns

### Laravel Validation Integration

#### 1. Form Validation with HTMX
```php
public function storeReadingLog(Request $request)
{
    try {
        $validated = $request->validate([
            'book_id' => 'required|integer|min:1|max:66',
            'chapter' => 'required|integer|min:1',
            'date_read' => 'required|date|before_or_equal:today',
            'notes_text' => 'nullable|string|max:500'
        ]);
        
        // Custom validation for chapter count
        $book = $this->bibleService->getBookById($validated['book_id']);
        if ($validated['chapter'] > $book['chapter_count']) {
            throw ValidationException::withMessages([
                'chapter' => "Chapter {$validated['chapter']} does not exist in {$book['name']}."
            ]);
        }
        
        $log = $this->createReadingLog($validated);
        
        return view('partials.reading-log-success', compact('log'));
        
    } catch (ValidationException $e) {
        return response()
            ->view('partials.form-errors', ['errors' => $e->errors()])
            ->setStatusCode(422);
    }
}
```

#### 2. Database Constraint Violations

Handle database integrity constraint violations, such as the unique constraint on reading logs that prevents duplicate chapter readings on the same date.

```php
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

public function storeReadingLog(Request $request)
{
    try {
        $validated = $request->validate([
            'book_id' => 'required|integer|min:1|max:66',
            'chapter' => 'required|integer|min:1',
            'date_read' => 'required|date|before_or_equal:today',
            'notes_text' => 'nullable|string|max:500'
        ]);
        
        // Custom validation for chapter count
        $book = $this->bibleService->getBookById($validated['book_id']);
        if ($validated['chapter'] > $book['chapter_count']) {
            throw ValidationException::withMessages([
                'chapter' => "Chapter {$validated['chapter']} does not exist in {$book['name']}."
            ]);
        }
        
        $log = ReadingLog::create([
            'user_id' => auth()->id(),
            'book_id' => $validated['book_id'],
            'chapter' => $validated['chapter'],
            'date_read' => $validated['date_read'],
            'passage_text' => $this->formatPassageText($validated['book_id'], $validated['chapter']),
            'notes_text' => $validated['notes_text']
        ]);
        
        return view('partials.reading-log-success', compact('log'));
        
    } catch (ValidationException $e) {
        return response()
            ->view('partials.form-errors', ['errors' => $e->errors()])
            ->setStatusCode(422);
    } catch (QueryException $e) {
        // Handle unique constraint violation (duplicate reading log)
        if ($e->getCode() === '23000') { // Integrity constraint violation
            return response()
                ->view('partials.form-errors', [
                    'errors' => ['chapter' => 'You have already logged this chapter for today.']
                ])
                ->setStatusCode(422);
        }
        
        // Re-throw if it's a different database error
        throw $e;
    }
}
```

## Loading States and User Feedback

### Loading Indicators

```html
<!-- Button with loading state -->
<button hx-post="/logs" 
        hx-target="#logs-container"
        hx-indicator="#save-loading">
    <span id="save-loading" class="htmx-indicator">
        <div class="loading-spinner"></div>
    </span>
    <span>Save Reading</span>
</button>
```

### HTMX Loading State CSS
```css
/* Required CSS for HTMX loading indicators */
.htmx-indicator { 
    display: none; 
}

.htmx-request .htmx-indicator { 
    display: inline-block; 
}
```

## Laravel Integration Patterns

### CSRF Protection

```html
<!-- Meta tag in layout head -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- HTMX configuration -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('htmx:configRequest', function(evt) {
            evt.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        });
    });
</script>
```

### Route Organization

```php
// routes/web.php
Route::middleware(['auth', 'web'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/streak', [DashboardController::class, 'getStreak'])->name('dashboard.streak');
    Route::get('/calendar', [DashboardController::class, 'getCalendar'])->name('dashboard.calendar');
    
    // Reading log routes
    Route::get('/logs', [ReadingLogController::class, 'index'])->name('logs.index');
    Route::post('/logs', [ReadingLogController::class, 'store'])->name('logs.store');
    Route::delete('/logs/{log}', [ReadingLogController::class, 'destroy'])->name('logs.destroy');
    
    // Statistics routes
    Route::get('/stats/books', [StatisticsController::class, 'getBookProgress'])->name('stats.books');
    Route::get('/stats/summary', [StatisticsController::class, 'getSummary'])->name('stats.summary');
});
```

## Advanced HTMX Patterns

### Event-Driven Updates

```html
<!-- Trigger custom events -->
<button hx-post="/logs" 
        hx-target="#logs-list"
        hx-trigger-after-swap="reading-logged">
    Save Reading
</button>

<!-- Listen for custom events -->
<div id="streak-counter" 
     hx-get="/streak" 
     hx-trigger="reading-logged from:body">
    Current Streak: {{ $streak }}
</div>
```

### Performance Optimization

#### Fragment Caching
```php
public function getBookProgress(Request $request)
{
    $cacheKey = "book_progress_" . auth()->id();
    
    $progress = Cache::remember($cacheKey, 300, function () {
        return BookProgress::where('user_id', auth()->id())
                          ->orderBy('book_id')
                          ->get();
    });
    
    return view('partials.book-progress', compact('progress'));
}
```

This implementation guide provides the foundation for building robust, server-driven interactions using HTMX while maintaining clean separation of concerns and excellent user experience. 