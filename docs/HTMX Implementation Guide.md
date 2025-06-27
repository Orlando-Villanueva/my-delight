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

### HTMX-Native Philosophy

Follow these principles to avoid over-engineering and maintain the HTMX way:

#### ✅ **Prefer Built-in HTMX Features**
- Use HTMX's declarative attributes over JavaScript event listeners
- Leverage built-in error handling instead of manual DOM manipulation
- Trust HTMX's response handling capabilities

#### ❌ **Avoid JavaScript Complexity**
- Don't use `addEventListener` for HTMX responses when attributes exist
- Avoid manual JSON parsing when HTML responses work
- Minimize custom JavaScript for HTMX operations
- **✅ Applied in ReadingLogService**: Removed complex event dispatching, kept clean HTML responses

#### 🎯 **Hypermedia-First Approach**
- Return HTML fragments, not JSON when possible
- Use appropriate HTTP status codes (422 for validation, etc.)
- Let HTMX handle response routing with built-in attributes

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

#### 1. Content Loading Pattern (Recommended for Forms)

**Use Case**: Loading forms and content sections within the main layout without full page reloads.

**Pattern**: HTMX loads content into main content area, providing seamless navigation while maintaining URL accessibility.

```html
<!-- Dashboard with main content area -->
<div id="main-content">
    <!-- Dashboard content or loaded forms appear here -->
    <div class="dashboard-overview">
        <button hx-get="{{ route('logs.create') }}" 
                hx-target="#main-content" 
                hx-swap="innerHTML">
            📖 Log Reading
        </button>
    </div>
</div>
```

```php
// Controller method supporting both HTMX and direct access
public function create(Request $request)
{
    $books = $this->bibleReferenceService->listBibleBooks();
    
    // Return partial for HTMX requests
    if ($request->header('HX-Request')) {
        return view('partials.reading-log-form', compact('books'));
    }
    
    // Return full page for direct access (graceful degradation)
    return view('logs.create', compact('books'));
}
```

**Benefits**:
- ✅ **Seamless Navigation**: No page reloads, maintains app-like feel
- ✅ **URL Accessibility**: Direct URLs still work for bookmarking
- ✅ **Progressive Enhancement**: Graceful degradation if JavaScript disabled
- ✅ **Consistent Layout**: Form appears within authenticated layout

#### 2. Partial Content Updates
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

### Modern HTMX Error Handling (Recommended)

Use HTMX's built-in error handling attributes for clean, declarative error management:

#### **Setup: Response-Targets Extension Required**

The `response-targets` extension is **required** for `hx-target-error` to work properly:

```html
<!-- In layout head - Load extension after main HTMX -->
<script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/htmx.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/ext/response-targets.js"></script>
```

#### **Implementation: Form with Error Handling**

```html
<!-- Simple, HTMX-native error handling -->
<form hx-post="{{ route('logs.store') }}" 
      hx-target="#form-response" 
      hx-swap="innerHTML"
      hx-ext="response-targets"
      hx-target-error="#form-response">
    @csrf
    
    <div id="form-response">
        <!-- Success and error responses appear here -->
    </div>
    
    <!-- Form fields... -->
</form>
```

**Key Configuration:**
- `hx-ext="response-targets"` - Activates the extension for this form
- `hx-target-error="#form-response"` - Where error responses (4xx/5xx) are displayed
- `hx-target="#form-response"` - Where success responses (2xx) are displayed

**Key Benefits:**
- ✅ **No JavaScript required** - Pure HTMX declarative approach
- ✅ **Automatic error routing** - 4xx/5xx responses go to `hx-target-error`
- ✅ **Hypermedia-focused** - Returns HTML fragments, not JSON
- ✅ **Simpler debugging** - No complex event listeners to trace

#### **Common Issue: Extension Not Loaded**

**Problem:** `hx-target-error` doesn't work, errors only appear in network tab
**Cause:** Missing `response-targets` extension or `hx-ext` attribute
**Solution:** Load extension and add `hx-ext="response-targets"` to form

**Avoid Complex Approaches:**
```html
<!-- ❌ DON'T: Complex JavaScript event listeners -->
<script>
document.addEventListener('htmx:responseError', function(evt) {
    // Manual DOM manipulation - not the HTMX way
    const target = document.querySelector('#form-response');
    target.innerHTML = evt.detail.xhr.response;
});
</script>
```

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
        
        // Use Service Layer for reading log creation (includes validation)
        $log = $this->readingLogService->logReading(
            $request->user(),
            $validated
        );
        
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
        
        // Use Service Layer for reading log creation (includes validation)
        $log = $this->readingLogService->logReading(
            $request->user(),
            $validated
        );
        
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

<!-- Note: Authentication uses standard Laravel forms (not HTMX) for MVP simplicity.
     HTMX is used for reading logs, dashboard updates, and other app features.
     See auth/login.blade.php for actual authentication implementation. -->

<!-- Example HTMX form for reading logs and other app features -->
<form hx-post="/logs" hx-target="#logs-response" hx-swap="innerHTML">
    @csrf
    <div>
        <label for="book_id">Bible Book</label>
        <select name="book_id" id="book_id" required>
            <option value="">Select a book...</option>
            <!-- Book options -->
        </select>
    </div>
    <div>
        <label for="chapter">Chapter</label>
        <input type="number" name="chapter" id="chapter" required>
    </div>
    <button type="submit">Log Reading</button>
    <div id="logs-response"></div>
</form>
```

### Route Organization

```php
// routes/web.php

// Fortify routes are automatically registered
// Custom view responses configured in FortifyServiceProvider

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

// Guest routes (Fortify handles the POST endpoints)
Route::middleware(['guest'])->group(function () {
    // Custom view routes for Fortify forms
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
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