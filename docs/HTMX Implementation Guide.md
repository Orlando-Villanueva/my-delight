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
    
    // Always return partial view for modal display
    return view('partials.reading-log-form', compact('books'));
}
```

**Benefits**:
- ✅ **Seamless Navigation**: No page reloads, maintains app-like feel
- ✅ **URL Accessibility**: Direct URLs still work for bookmarking
- ✅ **Progressive Enhancement**: Graceful degradation if JavaScript disabled
 ✅ **Modal-First**: Focused user experience with slide-over form
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

## Zero-Duplication Architecture Pattern

**Principle**: Prevent HTML duplication across HTMX views by ensuring each UI component exists in exactly one place.

### Component-Based Architecture

#### **1. Shared Component Structure**

```blade
{{-- partials/{feature}-sidebar.blade.php --}}
<div class="lg:w-1/4 bg-white rounded-lg p-6 shadow-sm">
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">📅 Widget Title</h3>
        <!-- Widget implementation -->
    </div>
</div>
```

#### **2. Page Container Pattern**

```blade
{{-- Main view: dashboard.blade.php --}}
@extends('layouts.authenticated')
@section('content')
<div id="page-container" class="flex gap-6">
    @include('partials.dashboard-content')
    @include('partials.dashboard-sidebar')
</div>
@endsection

{{-- HTMX container: partials/dashboard-page.blade.php --}}
<div class="flex gap-6">
    @include('partials.dashboard-content')
    @include('partials.dashboard-sidebar')
</div>
```

#### **3. Parameterized Components**

```blade
{{-- partials/header-update.blade.php --}}
<div id="page-header" hx-swap-oob="true">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            @if(isset($subtitle))
                <p class="text-gray-600 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>

{{-- Usage --}}
@include('partials.header-update', [
    'title' => 'Page Title', 
    'subtitle' => 'Optional description'
])
```

#### **4. Controller Dual Response Pattern**

```php
public function index(Request $request)
{
    $data = $this->service->getData($request->user());
    
    // HTMX navigation - return page container only
    if ($request->header('HX-Request')) {
        return view('partials.feature-page', compact('data'));
    }
    
    // Direct access - return full page with layout
    return view('feature.index', compact('data'));
}
```

### HTMX Implementation Patterns

#### **Navigation Pattern (Page Changes)**
```html
<button hx-get="{{ route('feature.index') }}" 
        hx-target="#page-container" 
        hx-swap="innerHTML"
        hx-push-url="true">
    Navigate
</button>
```

#### **Content Update Pattern (Same Page)**
```html
<div hx-get="{{ route('feature.action') }}" 
     hx-target="#content-area"
     hx-trigger="customEvent from:body">
    Loading...
</div>
```

#### **Out-of-Band Updates**
```blade
{{-- Include in any response to update header --}}
@include('partials.header-update', [
    'title' => 'Updated Title',
    'subtitle' => 'Status message'
])
```

### Implementation Checklist for New Features

- [ ] Create shared content partial first (`partials/{feature}-content.blade.php`)
- [ ] Create HTMX page container using `@include` statements (`partials/{feature}-page.blade.php`)
- [ ] Use parameterized includes for reusable elements
- [ ] Main view includes shared components via `@include`
- [ ] Controller supports both HTMX and direct access patterns
- [ ] Test both navigation paths for consistency
- [ ] Verify no HTML duplication between files

## Error Handling Patterns

### Unified Response Pattern (Recommended)

**Philosophy:** Always return 200 OK with appropriate HTML fragments. Let the server decide what to render based on validation results.

#### **Why This Approach?**

- ✅ **Pure HTMX** - No extensions or custom JavaScript needed
- ✅ **Reliable** - Doesn't depend on HTTP status codes or browser extensions
- ✅ **Consistent** - Same target for both success and error responses
- ✅ **Maintainable** - Simple, declarative approach
- ✅ **Better UX** - Form validation errors are expected user behavior, not server errors

#### **Implementation: Clean Form Error Handling**

```html
<!-- Clean: Single target, server decides what to render -->
<form hx-post="{{ route('logs.store') }}" 
      hx-target="#form-response" 
      hx-swap="innerHTML"
      x-data="readingLogForm()"
      class="space-y-6">
    @csrf
    
    <div id="form-response">
        <!-- Success message OR error message appears here -->
    </div>
    
    <!-- Form fields... -->
</form>
```

#### **Controller Pattern: Unified Response**

```php
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'book_id' => 'required|integer|min:1|max:66',
            'chapter_input' => ['required', 'string', 'regex:/^(\d+|\d+-\d+)$/'],
            'date_read' => 'required|date|before_or_equal:today',
            'notes_text' => 'nullable|string|max:500'
        ]);

        // Create reading log
        $log = $this->readingLogService->logReading($request->user(), $validated);

        // Return success response (200 OK)
        if ($request->header('HX-Request')) {
            return view('partials.reading-log-success-message', compact('log'));
        }
        return redirect()->route('dashboard')->with('success', 'Reading logged successfully!');

    } catch (ValidationException $e) {
        // Return validation errors (200 OK with error HTML)
        if ($request->header('HX-Request')) {
            return view('partials.validation-errors', ['errors' => $e->errors()]);
        }
        return back()->withErrors($e->errors())->withInput();

    } catch (QueryException $e) {
        // Handle database constraint violations (200 OK with error HTML)
        if ($e->getCode() === '23000') {
            $error = ['chapter_input' => ['You have already logged one or more of these chapters for today.']];
            
            if ($request->header('HX-Request')) {
                return view('partials.validation-errors', ['errors' => $error]);
            }
            return back()->withErrors($error)->withInput();
        }
        throw $e;
    }
}
```

#### **Error Display Template**

```blade
{{-- partials/validation-errors.blade.php --}}
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">
                Please fix the following errors:
            </h3>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors as $field => $fieldErrors)
                        @foreach($fieldErrors as $error)
                            <li><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong> {{ $error }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
```

### ❌ Deprecated: Response-Targets Extension Approach

**Note:** The following approach using `response-targets` extension and HTTP error codes is **deprecated** in favor of the unified response pattern above.

<details>
<summary>Click to view deprecated approach (for reference only)</summary>

#### **Old Setup: Response-Targets Extension**

```html
<!-- ❌ DEPRECATED: Don't use this approach -->
<script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/ext/response-targets.js"></script>

<form hx-post="{{ route('logs.store') }}" 
      hx-target="#form-response" 
      hx-swap="innerHTML"
      hx-ext="response-targets"
      hx-target-error="#form-response">
    <!-- Form content -->
</form>
```

#### **Why This Approach Was Problematic:**

- ❌ **Fragile** - Depends on extension loading correctly
- ❌ **Complex** - Requires understanding of HTTP status codes vs HTML responses
- ❌ **Inconsistent** - Different behavior for success vs error cases
- ❌ **Network Noise** - Validation errors appear as HTTP errors in logs/network tab

</details>

### **When to Use HTTP Error Codes**

Reserve HTTP error codes for **actual errors**, not form validation:

- **500** - Server errors (database down, code bugs)
- **401/403** - Authentication/authorization failures  
- **404** - Resource not found
- **NOT 422** - Form validation (this is expected user behavior)

### **Key Principle**

> **Form validation errors are not HTTP errors** - they're expected user interactions that should be handled gracefully with appropriate HTML responses.

### Laravel Validation Integration

The unified response pattern integrates seamlessly with Laravel's validation system:

#### **Validation Exception Handling**
```php
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'book_id' => 'required|integer|min:1|max:66',
            'chapter_input' => ['required', 'string', 'regex:/^(\d+|\d+-\d+)$/'],
            'date_read' => 'required|date|before_or_equal:today',
            'notes_text' => 'nullable|string|max:500'
        ]);
        
        $log = $this->readingLogService->logReading($request->user(), $validated);
        
        // Always return 200 OK with success HTML
        if ($request->header('HX-Request')) {
            return view('partials.reading-log-success-message', compact('log'));
        }
        return redirect()->route('dashboard')->with('success', 'Reading logged!');
        
    } catch (ValidationException $e) {
        // Always return 200 OK with error HTML
        if ($request->header('HX-Request')) {
            return view('partials.validation-errors', ['errors' => $e->errors()]);
        }
        return back()->withErrors($e->errors())->withInput();
    }
}
```

#### **Database Constraint Violations**
```php
use Illuminate\Database\QueryException;

public function store(Request $request)
{
    try {
        // ... validation and creation logic ...
        
    } catch (QueryException $e) {
        // Handle unique constraint violations gracefully
        if ($e->getCode() === '23000') {
            $error = ['chapter_input' => ['You have already logged one or more of these chapters for today.']];
            
            // Always return 200 OK with error HTML
            if ($request->header('HX-Request')) {
                return view('partials.validation-errors', ['errors' => $error]);
            }
            return back()->withErrors($error)->withInput();
        }
        
        // Re-throw actual server errors
        throw $e;
    }
}
```

#### **Benefits of This Integration**
- ✅ **Consistent Response Format** - All validation errors use the same template
- ✅ **Laravel Compatibility** - Works seamlessly with Laravel's validation system
- ✅ **Error Consistency** - Database constraints and validation errors look the same to users
- ✅ **Graceful Degradation** - Non-HTMX requests still work with standard Laravel error handling

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

## Anti-Flashing Patterns for Server-Driven State

### The Flash Problem

When Alpine.js components use server-side state preferences, visual flashing can occur during the brief moment between initial HTML render and Alpine.js initialization. This creates jarring UX where users see:

1. **Modal flash** - Modals briefly appear before `x-show` takes effect
2. **Content flash** - Wrong content shows before Alpine sets correct state  
3. **Button flash** - Buttons show incorrect styling before bindings activate

### Comprehensive Anti-Flash Solution

#### **1. Modal Flash Prevention**

**Problem**: Modals with `x-show="modalOpen"` briefly appear on page load.

**Solution**: Add `x-cloak` to modal elements.

```html
<!-- Modal Backdrop -->
<div x-show="modalOpen" x-cloak x-transition.opacity 
     class="fixed inset-0 bg-black/40 z-40"
     @click="modalOpen = false">
</div>

<!-- Modal Panel -->
<aside x-show="modalOpen" x-cloak 
       x-transition:enter="transform transition ease-in-out duration-300"
       class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white shadow-xl z-50"
       x-trap.inert.noscroll="modalOpen">
    <!-- Modal content -->
</aside>
```

#### **2. Server-Side State Flash Prevention**

**Problem**: When user preference is stored server-side (session), Alpine.js initially shows default state before loading preference.

**Solution**: Combine server-side preference detection with conditional `x-cloak`.

```php
@php
    // Read server-side preference
    $testament = session('testament_preference', 'Old');
@endphp

<div x-data="bookProgressComponent('{{ $testament }}')">
    <!-- Default Testament Content (no x-cloak) -->
    <div x-show="activeTestament === 'Old'" 
         {{ $testament === 'New' ? 'x-cloak' : '' }}>
        @include('partials.old-testament-content')
    </div>

    <!-- Non-Default Testament Content (has x-cloak when not preferred) -->
    <div x-show="activeTestament === 'New'" 
         {{ $testament === 'Old' ? 'x-cloak' : '' }}>
        @include('partials.new-testament-content')
    </div>
</div>
```

**How it works**:
- **When preference is "Old"**: Old Testament shows immediately, New Testament has `x-cloak`
- **When preference is "New"**: New Testament shows immediately, Old Testament has `x-cloak`
- **Result**: Correct content displays from initial render with no flash

#### **3. Server-Side Button Styling**

**Problem**: Alpine.js `:class` bindings cause button styling to flash from default to preferred state.

**Solution**: Apply server-side styling that matches Alpine.js bindings.

```php
@php
    $currentTestament = session('testament_preference', 'Old');
@endphp

<button x-on:click="activeTestament = 'Old'"
        :class="{ 'bg-blue-600 text-white': activeTestament === 'Old', 'text-gray-600': activeTestament !== 'Old' }"
        class="px-3 py-1.5 rounded {{ $currentTestament === 'Old' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
    Old Testament
</button>

<button x-on:click="activeTestament = 'New'"
        :class="{ 'bg-blue-600 text-white': activeTestament === 'New', 'text-gray-600': activeTestament !== 'New' }"
        class="px-3 py-1.5 rounded {{ $currentTestament === 'New' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
    New Testament  
</button>
```

**Key principle**: Server-side classes match Alpine.js `:class` logic for seamless transition.

### Implementation Architecture

#### **Server-Side Preference Storage**

```php
// routes/web.php - HTMX preference endpoint
Route::post('/preferences/testament', function (Request $request) {
    $testament = $request->input('testament');
    
    if (!in_array($testament, ['Old', 'New'])) {
        return response('Invalid testament', 400);
    }
    
    session(['testament_preference' => $testament]);
    return response('', 200);
})->name('preferences.testament');
```

#### **HTMX Preference Updates**

```html
<button x-on:click="activeTestament = 'Old'"
        hx-post="{{ route('preferences.testament') }}"
        hx-vals='{"testament": "Old"}'
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
        hx-swap="none">
    Old Testament
</button>
```

**Benefits**:
- ✅ **Pure HTMX** - No JavaScript fetch() calls
- ✅ **CSRF Protected** - Uses Laravel's csrf_token() helper
- ✅ **Server-driven** - State lives on server, not localStorage
- ✅ **Immediate persistence** - Preference saved on every click

#### **Simplified Alpine.js Component**

```javascript
function bookProgressComponent(serverDefault) {
    return {
        // State - Use server preference (from session)
        activeTestament: serverDefault
        // No localStorage, no $watch - server handles persistence
    };
}
```

### Anti-Flash Checklist

For any server-driven component with user preferences:

- [ ] **Identify flash sources** - Modal visibility, content selection, button states
- [ ] **Add modal x-cloak** - For any `x-show` modals/overlays
- [ ] **Conditional content x-cloak** - Hide non-preferred content initially
- [ ] **Server-side styling** - Match Alpine.js `:class` logic with static classes
- [ ] **HTMX preference storage** - Save state server-side, not localStorage
- [ ] **Test both preferences** - Verify no flash in either state
- [ ] **Simplify Alpine component** - Remove client-side persistence logic

### Result: Zero-Flash Server-Driven UI

The complete implementation eliminates all visual artifacts:

1. **Page loads** → Correct content and styling appear immediately
2. **Alpine.js initializes** → Takes over seamlessly with no visual change
3. **User interactions** → State changes and server updates work normally
4. **Page reloads** → Preferences persist, no flash on subsequent visits

This pattern is essential for professional HTMX applications where server-driven state must feel as smooth as client-side SPAs.

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
3. **Single source of truth** – the reading-log-form partial is used exclusively for modal display.
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

    // Always return the form partial for modal display
    return view('partials.reading-log-form', compact('books'));
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
3. Modal-first approach – simplified architecture focused on slide-over experience.
4. Keyboard – Esc closes, focus returns to original *Log Reading* button.

This implementation guide provides the foundation for building robust, server-driven interactions using HTMX while maintaining clean separation of concerns and excellent user experience. 