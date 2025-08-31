# Design Document

## Overview

This design converts the existing reading log modal into a dedicated page that integrates seamlessly with the current HTMX-based navigation system. The solution maintains all existing functionality while providing a more focused user experience through dedicated page navigation.

## Architecture

### Navigation Integration
The reading log page will integrate with the existing HTMX navigation pattern used by Dashboard and History pages:

- **Desktop**: Add third navigation item in sidebar
- **Mobile**: Utilize existing floating action button
- **HTMX Integration**: Use same content swapping mechanism (`hx-get`, `hx-target="#page-container"`, `hx-swap="innerHTML"`)
- **URL Management**: Implement proper URL routing with `hx-push-url="true"`

### Page Structure
The reading log page will follow the established pattern:
```
resources/views/logs/create.blade.php (main page template)
resources/views/partials/reading-log-page-content.blade.php (HTMX-swappable content)
```

## Components and Interfaces

### 1. Navigation Components

#### Desktop Sidebar Navigation
- **Location**: `resources/views/layouts/authenticated.blade.php`
- **Implementation**: Add third navigation button between History and user profile section
- **Styling**: Use existing navigation button classes with Alpine.js state management
- **Route**: `{{ route('logs.create') }}`

#### Mobile Navigation
- **Floating Action Button**: Modify existing FAB to use HTMX navigation instead of modal
- **Bottom Navigation**: No changes required (remains Dashboard + History only)

#### Header Button
- **Location**: Desktop header "Log Reading" button
- **Change**: Replace modal trigger with HTMX navigation
- **Behavior**: Navigate to reading log page instead of opening modal

### 2. Page Layout Components

#### Main Page Template (`logs/create.blade.php`)
```blade
@extends('layouts.authenticated')
@section('page-title', 'Log Reading')
@section('page-subtitle', 'Record your Bible reading progress')
@section('content')
<div id="main-content" class="h-full">
    @include('partials.reading-log-page-content', compact('books', 'allowYesterday', ...))
</div>
@endsection
```

#### Content Partial (`partials/reading-log-page-content.blade.php`)
- **Source**: Adapt existing `reading-log-form.blade.php`
- **Changes**: Remove modal-specific elements (close button, modal title ID)
- **Layout**: Use full-page layout instead of modal constraints
- **Form Target**: Update HTMX target to refresh current page content

### 3. Form Components

#### Form Structure
The form will maintain identical functionality with these adaptations:

**Date Selection**
- Keep existing today/yesterday logic with grace period
- Maintain all validation and business rules

**Book Selection**  
- Preserve Old Testament/New Testament grouping
- Keep chapter count display in options

**Chapter Input**
- Maintain range support (e.g., "1-5")
- Keep existing validation

**Notes Textarea**
- Preserve 1000 character limit with counter
- Maintain optional status

#### Form Submission Handling
```blade
<form hx-post="{{ route('logs.store') }}" 
      hx-target="#main-content" 
      hx-swap="innerHTML"
      class="space-y-6">
```

### 4. Success Message Component

#### Success State Design
After successful submission, display:
- **Success Message**: Dismissable confirmation using existing error message styling
- **Form Reset**: Clean form ready for next entry with draft data cleared
- **Close Button**: Allow users to dismiss the success message manually

#### Implementation
```blade
@if(session('success'))
<div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4" x-data="{ show: true }" x-show="show">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-400 mr-3">...</svg>
            <div>
                <h3 class="text-sm font-medium text-green-800 dark:text-green-400">
                    Reading logged successfully!
                </h3>
                <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                    {{ session('success') }}
                </p>
            </div>
        </div>
        <button @click="show = false" class="text-green-400 hover:text-green-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
@endif
```

## Data Models

### No Changes Required
The existing data models and validation remain unchanged:
- `ReadingLog` model
- `ReadingLogRequest` validation
- `ReadingLogService` business logic
- Database schema

## Error Handling

### Validation Errors
- **Display**: Same error handling as current modal implementation
- **Styling**: Maintain existing error message styling
- **Behavior**: Show errors inline with form fields

### Form State Management
- **Persistence**: Use `old()` helper for form field persistence on validation errors
- **Reset**: Clear form after successful submission
- **Loading States**: Maintain HTMX loading indicators

## Testing Strategy

### Feature Tests
1. **Navigation Tests**
   - Test desktop sidebar navigation to reading log page
   - Test mobile floating action button navigation
   - Test header button navigation
   - Verify URL updates and browser history

2. **Form Functionality Tests**
   - Test all existing form validation scenarios
   - Test successful submission and success message display
   - Test form reset after successful submission
   - Test error handling and form persistence

3. **Integration Tests**
   - Test HTMX content swapping
   - Test dashboard updates after reading log creation
   - Test responsive behavior across device sizes

4. **Regression Tests**
   - Verify Dashboard and History pages remain unaffected
   - Test existing modal removal doesn't break other functionality
   - Verify all existing reading log business logic works identically

### Manual Testing Scenarios
1. **Desktop Navigation Flow**
   - Navigate between Dashboard → Log Reading → History
   - Test form submission and success state
   - Verify header button behavior

2. **Mobile Navigation Flow**  
   - Test floating action button navigation
   - Verify mobile form usability
   - Test success message display on mobile

3. **Cross-Device Testing**
   - Test responsive layout on tablet sizes
   - Verify touch interactions work properly
   - Test form accessibility with keyboard navigation

## Implementation Notes

### HTMX Configuration
- **Targets**: Use `#page-container` for consistent page content swapping
- **URL Management**: Enable `hx-push-url="true"` for proper browser history support
- **Triggers**: Maintain existing `readingLogAdded` event for dashboard updates
- **Loading**: Implement loading states for form submission
- **History**: Handle `HX-Current-URL` header for proper routing support

### Alpine.js State Management
- **Current View**: Update `currentView` state to include 'create' option
- **Navigation**: Extend existing navigation state management
- **Modal Cleanup**: Remove modal-related Alpine.js state and handlers
- **Draft Persistence**: Use Alpine.js `$persist` for form state preservation

### Draft Persistence System
- **Storage**: Use Alpine.js `$persist` with sessionStorage for form draft data
- **Scope**: Preserve book selection, chapter input, date selection, and notes
- **Lifecycle**: Clear drafts on successful submission, preserve on navigation
- **Reset**: Clear drafts on page reload (browser refresh)

#### Draft Implementation Example
```javascript
x-data="{
    formData: $persist({
        book: '',
        chapter: '',
        date_read: 'today',
        notes_text: ''
    }).using(sessionStorage),
    clearDraft() {
        this.formData = { book: '', chapter: '', date_read: 'today', notes_text: '' }
    }
}"
```

### Route Configuration
- **New Route**: `GET /logs/create` for reading log page with same auth middleware
- **Existing Routes**: `POST /logs` remains unchanged for form submission
- **HTMX Routes**: Ensure proper HTMX response handling with URL updates
- **Authentication**: Apply same middleware as dashboard and history routes

### Browser History Integration
- **URL Updates**: All navigation links include `hx-push-url="true"`
- **Back/Forward**: HTMX handles browser navigation seamlessly
- **Direct Access**: `/logs/create` accessible with proper authentication
- **Bookmarking**: Full support for bookmarking the reading log page

### Accessibility Considerations
- **Focus Management**: Proper focus handling when navigating to page
- **Screen Readers**: Update ARIA labels and descriptions for page context
- **Keyboard Navigation**: Ensure all interactive elements are keyboard accessible
- **Success Messages**: Ensure dismissable success messages are announced to screen readers