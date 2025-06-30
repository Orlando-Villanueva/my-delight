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

### URL Management with `hx-push-url`

For navigation between different pages (not just content loading), use `hx-push-url="true"` to maintain proper browser history and URL state:

```html
<!-- Navigation buttons with URL management -->
<button hx-get="{{ route('logs.index') }}" 
        hx-target="#main-content" 
        hx-swap="innerHTML"
        hx-push-url="true">
    View History
</button>

<button hx-get="{{ route('dashboard') }}" 
        hx-target="#main-content" 
        hx-swap="innerHTML"
        hx-push-url="true">
    Dashboard
</button>
```

**How `hx-push-url` Works:**
1. **HTMX makes request** to the URL specified in `hx-get` (e.g., `/logs`)
2. **Server responds** with appropriate content (partial for HTMX, full page for direct access)
3. **HTMX updates DOM** with the response content
4. **HTMX updates browser URL** to match the request URL (e.g., `/logs`)
5. **Browser history** gets a new entry, enabling back/forward navigation

**Controller Pattern for URL Management:**
```php
public function index(Request $request)
{
    $logs = $this->getReadingLogs($request->user());
    
    // Return partial view for HTMX requests (navigation)
    if ($request->header('HX-Request')) {
        return view('partials.reading-log-page-content', compact('logs'));
    }
    
    // Return full page for direct URL access (bookmarking, refresh)
    return view('logs.index', compact('logs'));
}
```

**When to Use `hx-push-url`:**
- ✅ **Page Navigation**: Moving between distinct application pages
- ✅ **Bookmarkable Content**: Users should be able to bookmark and share URLs
- ❌ **Modal/Form Loading**: Temporary content that shouldn't change the URL
- ❌ **Filter Updates**: Content updates within the same logical page

**Benefits of URL Management:**
- ✅ **Bookmarking**: Users can bookmark `/logs` and return directly
- ✅ **Sharing**: URLs can be shared and accessed directly
- ✅ **Browser Navigation**: Back/forward buttons work as expected
- ✅ **Refresh Handling**: Page refresh loads the correct content
- ✅ **SEO Friendly**: Proper URLs for different application states

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

#### Fragment Caching for HTMX Responses

Based on PR5 performance assessment, HTMX endpoints should implement strategic caching for expensive operations:

```php
// Dashboard statistics caching (high-impact optimization)
public function getDashboardStatistics(Request $request)
{
    $userId = auth()->id();
    $cacheKey = "user_dashboard_stats_{$userId}";
    
    $statistics = Cache::remember($cacheKey, 300, function () use ($userId) {
        return $this->statisticsService->getDashboardStatistics(auth()->user());
    });
    
    return view('partials.dashboard-stats', compact('statistics'));
}

// Streak counter caching (frequently requested via HTMX)
public function getCurrentStreak(Request $request)
{
    $userId = auth()->id();
    $cacheKey = "user_current_streak_{$userId}";
    
    $streak = Cache::remember($cacheKey, 900, function () {
        return $this->readingLogService->calculateCurrentStreak(auth()->user());
    });
    
    return view('partials.streak-counter', compact('streak'));
}

// Book progress caching (for reading log form updates)
public function getBookProgress(Request $request)
{
    $userId = auth()->id();
    $cacheKey = "user_book_progress_{$userId}";
    
    $progress = Cache::remember($cacheKey, 300, function () {
        return BookProgress::where('user_id', auth()->id())
                          ->orderBy('book_id')
                          ->get();
    });
    
    return view('partials.book-progress', compact('progress'));
}
```

#### Cache Invalidation for HTMX Updates

```php
// Clear relevant caches when reading logs are created
public function store(Request $request)
{
    $validated = $request->validate([...]);
    
    $log = $this->readingLogService->logReading($request->user(), $validated);
    
    // Invalidate affected caches for immediate UI consistency
    $userId = auth()->id();
    $currentYear = now()->year;
    
    Cache::forget("user_dashboard_stats_{$userId}");
    Cache::forget("user_current_streak_{$userId}");
    Cache::forget("user_book_progress_{$userId}");
    Cache::forget("user_calendar_{$userId}_{$currentYear}");
    
    if ($request->header('HX-Request')) {
        return view('partials.reading-log-success', compact('log'));
    }
    
    return redirect()->route('dashboard')->with('success', 'Reading logged!');
}
```

#### HTMX Response Optimization

```html
<!-- Optimize HTMX requests with appropriate triggers and targets -->
<!-- Only update streak when readings actually change -->
<div id="streak-display" 
     hx-get="{{ route('dashboard.streak') }}" 
     hx-trigger="reading-logged from:body delay:100ms">
    {{ $currentStreak }} days
</div>

<!-- Cache-friendly calendar updates -->
<div id="calendar-view" 
     hx-get="{{ route('dashboard.calendar') }}" 
     hx-trigger="reading-logged from:body delay:200ms"
     hx-swap="innerHTML settle:100ms">
    @include('partials.calendar-grid')
</div>
```

#### Performance Monitoring for HTMX

```php
// Add performance logging for HTMX endpoints
public function getDashboardStatistics(Request $request)
{
    $startTime = microtime(true);
    
    $statistics = Cache::remember("user_dashboard_stats_" . auth()->id(), 300, 
        fn() => $this->statisticsService->getDashboardStatistics(auth()->user())
    );
    
    $executionTime = microtime(true) - $startTime;
    
    // Log slow HTMX responses for optimization
    if ($executionTime > 0.5) {
        Log::info('Slow HTMX response', [
            'endpoint' => 'dashboard.statistics',
            'user_id' => auth()->id(),
            'execution_time' => $executionTime,
            'is_cache_hit' => $executionTime < 0.1
        ]);
    }
    
    return view('partials.dashboard-stats', compact('statistics'));
}
```

## Modal / Slide-over Pattern (Reading Log Entry)

> 📖 **Context**: Starting June 2025 we now open the *Log Reading* form in a right-hand slide-over (modal) instead of replacing the main content area. This keeps the dashboard and history pages visible in the background, creates a focused flow, and removes layout inconsistencies between pages.

### Why a modal?
1. **Visual continuity** – the user still "sees" where they are (dashboard, history, etc.).
2. **Task focus** – dimmed background reduces distractions while filling the form.
3. **Single source of truth** – the exact same Blade partial is used for both HTMX modal loading and full-page fallback (`/logs/create`).
4. **URL hygiene** – temporary overlays should not change `window.location`; therefore **DO NOT** use `hx-push-url` for modal loads.

### Anatomy
```html
<!-- Trigger (Dashboard, History, Anywhere) -->
<button hx-get="{{ route('logs.create') }}"
        hx-target="#reading-log-modal-content"
        hx-swap="innerHTML"
        @click="modalOpen = true"
        class="btn btn-primary">
    📖 Log Reading
</button>

<!-- Modal / Slide-over Container (once per layout) -->
<div x-data="{ modalOpen: false }">
    <!-- Backdrop -->
    <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="modalOpen = false"></div>

    <!-- Panel -->
    <aside x-show="modalOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="translate-x-full"
           class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white shadow-xl z-50 overflow-y-auto">
        <div id="reading-log-modal-content" class="p-6">
            <!-- HTMX will inject the form here -->
        </div>
    </aside>
</div>
```

### Controller contract
```php
public function create(Request $request)
{
    $books = $this->bibleReferenceService->listBibleBooks();

    // 1️⃣ Modal / HTMX request – return the *form partial only*
    if ($request->header('HX-Request')) {
        return view('partials.reading-log-form', compact('books'));
    }

    // 2️⃣ Direct page access – return full layout (graceful degrade)
    return view('logs.create', compact('books'));
}
```

### Implementation rules
- **No `hx-push-url`** for modal triggers; it's transient UI.
- Put the modal container in `layouts/authenticated.blade.php` so it's globally available.
- Load Alpine.js *Focus* plugin for accessible focus-trap if needed.
- Use the [`Penguin UI` modal blueprint](https://www.penguinui.com/components/modal) as a starting point for transitions and a11y.
- **Escape hatch** – a `<button type="button">Cancel</button>` inside the form simply triggers `modalOpen = false` (no network request).
- **Success state** – on 2xx response, replace modal content with a success partial; auto-close after 2 seconds via Alpine `setTimeout` if desired.

### Accessibility checklist
- Trap focus within the panel while open (`x-trap.inert.noscroll` from Alpine v3 plugin).
- Close on *Esc* key and backdrop click.
- Maintain high contrast; respect reduced-motion.

### Test plan
1. Desktop – open/close modal from dashboard & history.
2. Mobile – ensure panel slides up from bottom and covers at least 75 vh.
3. Refresh safety – direct `/logs/create` URL still shows full-width page.
4. Keyboard – Esc closes, focus returns to original *Log Reading* button.

This implementation guide provides the foundation for building robust, server-driven interactions using HTMX while maintaining clean separation of concerns and excellent user experience. 