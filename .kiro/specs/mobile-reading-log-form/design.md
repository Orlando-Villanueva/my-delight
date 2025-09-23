# Design Document

## Overview

This design creates a mobile-first reading log form interface that replaces the current 66-book dropdown and text chapter input with touch-friendly, visual selection grids. The solution maintains all existing functionality while providing an optimized mobile experience through progressive disclosure and visual selection interfaces.

## Architecture

### Component Structure

The mobile reading log form will introduce new Blade components that work alongside the existing form infrastructure:

```
Grid Reading Log Form
├── Testament Toggle (reuse existing)
├── Grid Book Selector (NEW)
├── Grid Chapter Selector (NEW)
├── Selection Label Display (NEW)
└── Form Integration Layer
```

### State Management & Technology Boundaries

**Alpine.js Territory (Client-Side UI State)**:
- **Form Interactions**: Book/chapter selection, grid states, visual feedback
- **Progressive Disclosure**: Book grid → chapter grid → form submission
- **UI State**: Selected books, chapters, button states, animations
- **Testament Toggle**: Immediate UI switching (client-side filtering)
- **Form Validation**: Client-side feedback and enabling/disabling submit

**HTMX Territory (Server Communication)**:
- **Form Submission**: POST to `/logs` endpoint with form data
- **Cross-Section Updates**: Dashboard stats, recent logs refresh after submission
- **Server Validation**: Display validation errors from server response
- **Testament Persistence**: Save testament preference to server session
- **Success Handling**: Display success messages, trigger page updates

**Hybrid Approach**:
- **Testament Toggle**: Alpine.js for immediate UI + HTMX for server persistence
- **Form State**: Alpine.js manages selection, HTMX handles submission
- **Progressive Enhancement**: Works without JavaScript via standard form submission

## Components and Interfaces

### 1. Grid Book Selector Component

**Location**: `resources/views/components/bible/grid-book-selector.blade.php`

**Props**:
```php
@props([
    'books' => [],
    'selectedTestament' => 'Old',
    'name' => 'book_id',
    'error' => null,
    'searchPlaceholder' => 'Search books...'
])
```

**Component Structure**:
```blade
<div class="space-y-4" x-data="mobileBookSelector()">
    <!-- Testament Toggle -->
    <x-bible.testament-toggle />

    <!-- Search Input -->
    <div class="relative">
        <input type="search"
               placeholder="{{ $searchPlaceholder }}"
               x-model="searchQuery"
               class="form-input pl-10">
        <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <!-- Search icon -->
        </svg>
    </div>

    <!-- Book Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"
         x-show="filteredBooks.length > 0">
        <template x-for="book in filteredBooks" :key="book.id">
            <button type="button"
                    @click="selectBook(book)"
                    class="book-button"
                    :class="getBookButtonClass(book)">
                <div class="font-semibold text-sm mb-1" x-text="book.name"></div>
                <div class="text-xs opacity-75" x-text="book.chapters + ' chapters'"></div>
            </button>
        </template>
    </div>

    <!-- No Results Message -->
    <div x-show="searchQuery && filteredBooks.length === 0"
         class="text-center py-8 text-gray-500">
        No books found
    </div>

    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="{{ $name }}" x-model="selectedBookId">
</div>
```

**Alpine.js Controller**:
```javascript
function gridBookSelector() {
    return {
        searchQuery: '',
        selectedBookId: '',
        activeTestament: 'Old',

        get filteredBooks() {
            // Filter by testament and search query
            let books = this.getBooksByTestament();
            if (this.searchQuery) {
                books = books.filter(book =>
                    book.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }
            return books;
        },

        selectBook(book) {
            this.selectedBookId = book.id;
            this.$dispatch('book-selected', { book });
        }
    };
}
```

### 2. Grid Chapter Selector Component

**Location**: `resources/views/components/bible/grid-chapter-selector.blade.php`

**Props**:
```php
@props([
    'book' => null,
    'name' => 'chapter_input',
    'error' => null
])
```

**Component Structure**:
```blade
<div class="space-y-4" x-data="mobileChapterSelector({{ $book ? $book['chapters'] : 0 }})">
    <!-- Instruction Text -->
    <div class="text-sm text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
        💡 <strong>Tip:</strong> Click a chapter, then click another to create a range (e.g., 3-7)
    </div>

    <!-- Chapter Grid -->
    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-2">
        <template x-for="chapter in totalChapters" :key="chapter">
            <button type="button"
                    @click="selectChapter(chapter)"
                    class="chapter-button"
                    :class="getChapterButtonClass(chapter)"
                    x-text="chapter">
            </button>
        </template>
    </div>

    <!-- Back to Books Button -->
    <button type="button"
            @click="$dispatch('back-to-books')"
            class="btn btn-outline btn-sm">
        ← Back to Books
    </button>

    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="{{ $name }}" x-model="chapterInput">
</div>
```

**Alpine.js Controller**:
```javascript
function gridChapterSelector(totalChapters) {
    return {
        totalChapters: Array.from({length: totalChapters}, (_, i) => i + 1),
        selectedChapters: [],
        firstSelection: null,

        get chapterInput() {
            if (this.selectedChapters.length === 0) return '';
            if (this.selectedChapters.length === 1) return this.selectedChapters[0].toString();

            // Handle range: find consecutive sequences
            const sorted = [...this.selectedChapters].sort((a, b) => a - b);
            const first = sorted[0];
            const last = sorted[sorted.length - 1];

            // Check if it's a consecutive range
            const isConsecutive = sorted.every((num, index) =>
                index === 0 || num === sorted[index - 1] + 1
            );

            return isConsecutive ? `${first}-${last}` : sorted.join(',');
        },

        selectChapter(chapter) {
            if (!this.firstSelection) {
                // First click
                this.firstSelection = chapter;
                this.selectedChapters = [chapter];
            } else if (this.firstSelection === chapter) {
                // Same chapter clicked - unselect
                this.firstSelection = null;
                this.selectedChapters = [];
            } else {
                // Different chapter clicked
                const start = Math.min(this.firstSelection, chapter);
                const end = Math.max(this.firstSelection, chapter);

                if (Math.abs(this.firstSelection - chapter) === end - start) {
                    // Sequential - create range
                    this.selectedChapters = Array.from({length: end - start + 1}, (_, i) => start + i);
                } else {
                    // Non-sequential - reset to new single selection
                    this.firstSelection = chapter;
                    this.selectedChapters = [chapter];
                }
            }

            this.$dispatch('chapter-selected', {
                chapters: this.selectedChapters,
                input: this.chapterInput
            });
        },

        getChapterButtonClass(chapter) {
            return this.selectedChapters.includes(chapter)
                ? 'chapter-button-selected'
                : 'chapter-button-default';
        }
    };
}
```

### 3. Selection Label Component (HTMX-Alpine.js First)

**Location**: `resources/views/components/bible/selection-label.blade.php`

**Design Principle**: Simple display component using Alpine.js `x-bind` for reactive updates from parent state. No client-side state management.

**Props**:
```php
@props([
    'selectedBook' => null,
    'selectedChapters' => null
])
```

**Component Structure**:
```blade
<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 transition-all duration-200">
    <div class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
        <template x-if="selectedBook || selectedChapters">
            <div class="flex items-center gap-2 w-full">
                <div class="text-lg">📖</div>
                <div class="flex-1">
                    <span class="text-gray-600 dark:text-gray-400">Selection:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100 font-semibold"
                          x-text="selectedBook ? (selectedChapters ? `${selectedBook.name} ${selectedChapters}` : selectedBook.name) : ''"></span>
                </div>
            </div>
        </template>
        <template x-if="!selectedBook && !selectedChapters">
            <div class="flex items-center gap-2 w-full">
                <div class="text-lg">📚</div>
                <div class="flex-1">
                    <span class="text-gray-500 dark:text-gray-400 italic">No selection yet - choose a book to get started</span>
                </div>
            </div>
        </template>
    </div>
</div>
```

**Alpine.js Integration**: Receives data via `x-bind` from parent form component. No internal state management or event listeners.

### 4. Main Form Integration Component

**Location**: `resources/views/components/bible/grid-reading-form.blade.php`

**Props**:
```php
@props([
    'books' => [],
    'allowYesterday' => false,
    'hasReadYesterday' => false,
    'hasReadToday' => false,
    'currentStreak' => 0
])
```

**Component Structure**:
```blade
<div x-data="mobileReadingForm()" class="space-y-6">
    <!-- Date Selection (existing) -->
    @include('partials.date-selection', compact('allowYesterday', 'hasReadYesterday', 'hasReadToday', 'currentStreak'))

    <!-- Book Selection or Chapter Selection -->
    <div x-show="!selectedBook">
        <x-bible.grid-book-selector
            :books="$books"
            @book-selected="onBookSelected($event.detail.book)"
        />
    </div>

    <div x-show="selectedBook">
        <x-bible.grid-chapter-selector
            x-bind:book="selectedBook"
            @chapter-selected="onChapterSelected($event.detail)"
            @back-to-books="onBackToBooks()"
        />
    </div>

    <!-- Selection Label -->
    <x-bible.selection-label
        x-bind:book="selectedBook"
        x-bind:chapters="selectedChapters"
    />

    <!-- Notes Section (existing) -->
    <x-ui.textarea
        name="notes_text"
        label="Notes (Optional)"
        placeholder="Share any thoughts, insights, or questions from your reading..."
        :value="old('notes_text')"
        rows="4"
        maxlength="1000"
        :showCounter="true"
        :error="$errors->first('notes_text')"
    />

    <!-- Submit Button -->
    <x-ui.button
        type="submit"
        variant="accent"
        size="lg"
        class="w-full"
        x-bind:disabled="!canSubmit">
        Log Reading
    </x-ui.button>
</div>
```

## Data Models

### No Changes Required
The existing data models and validation remain unchanged:
- `ReadingLog` model
- `ReadingLogRequest` validation
- `ReadingLogService` business logic
- Database schema

### Data Flow
```
Reading Form Controller
├── Get books data (existing)
├── Get form context (existing)
└── Pass to mobile-reading-form component
```

## CSS Design System Integration

### Button Styles

Using existing theme colors and adding mobile-specific classes:

```css
/* Book Selection Buttons */
.book-button {
    @apply p-3 rounded-lg border-2 text-center transition-all duration-200 shadow-sm touch-target;
}

.book-button-default {
    @apply bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50 hover:shadow-md;
}

.book-button-selected {
    @apply bg-primary-500 text-white border-primary-500 shadow-md;
}

/* Chapter Selection Buttons */
.chapter-button {
    @apply aspect-square flex items-center justify-center text-sm font-medium rounded-lg border-2 transition-all duration-200 touch-target;
}

.chapter-button-default {
    @apply bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50;
}

.chapter-button-selected {
    @apply bg-primary-500 text-white border-primary-500;
}
```

### Responsive Grid System

Following existing patterns from `book-completion-grid.blade.php`:

```css
/* Book Grid - Mobile First */
.book-grid {
    @apply grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3;
}

/* Chapter Grid - More columns for numbers */
.chapter-grid {
    @apply grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 gap-2;
}
```

### Color Theme Integration

Using existing theme variables from `app.css`:
- **Primary**: `--color-primary-500` (#3366CC) for selected states
- **Success**: `--color-success-500` (#66CC99) for completed actions
- **Neutral**: Standard gray scales for default states
- **Dark Mode**: Existing dark mode variables

## Error Handling

### Validation Integration
- **Laravel Validation**: Use existing `ReadingLogRequest` validation rules
- **Error Display**: Integrate with existing error handling patterns
- **Form Persistence**: Use Laravel's `old()` helper for form state

### Progressive Enhancement
- **JavaScript Disabled**: Form falls back to existing dropdown/input approach
- **Network Errors**: HTMX handles connectivity issues gracefully
- **Component Failures**: Graceful degradation to basic form elements

## Testing Strategy

### Component Tests
1. **Book Selector Tests**
   - Testament filtering functionality
   - Search filtering accuracy
   - Book selection events
   - Responsive grid layout

2. **Chapter Selector Tests**
   - Single chapter selection
   - Range creation logic
   - Back navigation functionality
   - Input value generation

3. **Integration Tests**
   - Form submission with mobile components
   - Validation error handling
   - Success state management
   - HTMX compatibility

### Accessibility Testing
- **Screen Reader**: Proper ARIA labels and navigation
- **Keyboard Navigation**: Tab order and enter/space handling
- **Touch Targets**: Minimum 44px touch target compliance
- **Color Contrast**: WCAG AA compliance for all states

## Implementation Strategy

### Phase 1: Core Components
1. Create `grid-book-selector.blade.php` with basic grid
2. Implement testament filtering and search
3. Add Alpine.js state management

### Phase 2: Chapter Selection
1. Create `grid-chapter-selector.blade.php` with grid
2. Implement chapter selection logic (single/range)
3. Add back navigation functionality

### Phase 3: Form Integration
1. Create main `grid-reading-form.blade.php` component
2. Integrate with existing form validation
3. Add selection label display

### Phase 4: Polish & Testing
1. CSS styling and responsive design
2. Accessibility improvements
3. Comprehensive testing
4. Performance optimization

## Performance Considerations

### Client-Side Optimization
- **Alpine.js**: Minimal JavaScript footprint for state management
- **CSS Grid**: Hardware-accelerated layout with CSS Grid
- **Search Filtering**: Client-side filtering for instant results
- **Touch Optimization**: CSS transforms for smooth interactions

### Server-Side Efficiency
- **Reuse Existing APIs**: No additional database queries required
- **Component Caching**: Leverage existing Blade component caching
- **Data Structure**: Use existing book configuration from `config/bible.php`

## Browser Compatibility

### Modern Browser Support
- **CSS Grid**: Full support in all modern browsers
- **Alpine.js**: Works in all browsers supporting ES6
- **Touch Events**: Native support on mobile devices
- **HTMX**: Compatible with existing HTMX infrastructure

### Progressive Enhancement
- **Fallback**: Graceful degradation to existing dropdown approach
- **Feature Detection**: Alpine.js handles missing JavaScript gracefully
- **CSS Fallbacks**: Grid layouts fall back to flex/block as needed