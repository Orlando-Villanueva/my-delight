# Alpine.js Component Guide

## Overview

Alpine.js serves as the "sprinkles of interactivity" layer in the Bible Reading Habit Builder, providing lightweight client-side state management and reactive data binding. Alpine complements HTMX by handling local UI interactions while HTMX manages server communication.

## Core Alpine.js Principles

### Minimal Client-Side State

Alpine.js is used for:
- **Local UI State**: Dropdown visibility, form validation feedback, modal states
- **User Interface Interactions**: Toggles, accordions, tabs, form enhancements
- **Reactive Data Binding**: Dynamic content updates based on user input
- **Client-Side Validation**: Immediate feedback before server submission

Alpine.js is **NOT** used for:
- **Application State**: Business logic remains on the server
- **Data Persistence**: All persistent data managed via HTMX/server
- **Complex State Management**: Keep components simple and focused

## Component Organization Patterns

### 1. Inline Components (Preferred for Simple Interactions)

```html
<!-- Simple toggle component -->
<div x-data="{ open: false }">
    <button @click="open = !open">
        Menu
        <svg x-show="!open"><!-- down arrow --></svg>
        <svg x-show="open"><!-- up arrow --></svg>
    </button>
    
    <div x-show="open" 
         x-transition
         @click.away="open = false">
        <!-- Menu items -->
    </div>
</div>
```

### 2. Component Functions (For Reusable Logic)

```html
<!-- Reading log form with validation -->
<form x-data="readingLogForm()" @submit.prevent="submitForm">
    <div class="form-group">
        <label for="book_id">Bible Book</label>
        <select x-model="form.book_id" 
                @change="updateChapters()"
                name="book_id">
            <option value="">Select a book...</option>
            @foreach($books as $book)
                <option value="{{ $book['id'] }}">{{ $book['name'] }}</option>
            @endforeach
        </select>
        <div x-show="errors.book_id" 
             x-text="errors.book_id"></div>
    </div>
    
    <div class="form-group">
        <label for="chapter">Chapter</label>
        <select x-model="form.chapter" name="chapter">
            <option value="">Select chapter...</option>
            <template x-for="i in availableChapters" :key="i">
                <option :value="i" x-text="i"></option>
            </template>
        </select>
        <div x-show="errors.chapter" 
             x-text="errors.chapter" 
             class="error-message"></div>
    </div>
    
    <div class="form-group">
        <label for="notes_text">Notes (optional)</label>
        <textarea x-model="form.notes_text" 
                  @input="validateNotes()"
                  maxlength="500"
                  name="notes_text"></textarea>
        <div>
            <span x-text="form.notes_text.length"></span>/500 characters
        </div>
        <div x-show="errors.notes_text" 
             x-text="errors.notes_text" 
             class="error-message"></div>
    </div>
    
    <button type="submit" 
            :disabled="!isFormValid()">
        Save Reading Log
    </button>
</form>

<script>
function readingLogForm() {
    return {
        form: {
            book_id: '',
            chapter: '',
            notes_text: ''
        },
        errors: {},
        availableChapters: 0,
        
        // Bible book chapter counts (from server config)
        bookChapters: @json(config('bible.books')),
        
        updateChapters() {
            if (this.form.book_id) {
                const book = this.bookChapters.find(b => b.id == this.form.book_id);
                this.availableChapters = book ? book.chapter_count : 0;
                this.form.chapter = ''; // Reset chapter selection
                this.errors.chapter = ''; // Clear chapter errors
            } else {
                this.availableChapters = 0;
            }
        },
        
        validateNotes() {
            if (this.form.notes_text.length > 500) {
                this.errors.notes_text = 'Notes cannot exceed 500 characters';
            } else {
                this.errors.notes_text = '';
            }
        },
        
        isFormValid() {
            return this.form.book_id && 
                   this.form.chapter && 
                   this.form.notes_text.length <= 500 &&
                   Object.keys(this.errors).length === 0;
        },
        
        submitForm() {
            // Let HTMX handle the actual submission
            // This is just for client-side validation
            if (this.isFormValid()) {
                // Form will be submitted by HTMX
                return true;
            }
            return false;
        }
    }
}
</script>
```

### 3. Global Alpine Components

```html
<!-- In main layout for global components -->
<script>
// Global Alpine data
document.addEventListener('alpine:init', () => {
    Alpine.data('navigation', () => ({
        mobileMenuOpen: false,
        
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        
        closeMobileMenu() {
            this.mobileMenuOpen = false;
        }
    }));
    
    Alpine.data('notifications', () => ({
        notifications: [],
        
        addNotification(message, type = 'info') {
            const id = Date.now();
            this.notifications.push({ id, message, type });
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                this.removeNotification(id);
            }, 5000);
        },
        
        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }));
});
</script>
```

## Reactive Data Binding Patterns

### 1. Form Input Binding

```html
<!-- Two-way data binding with validation -->
<div x-data="{ 
    searchQuery: '', 
    searchResults: [],
    isSearching: false 
}">
    <input type="text" 
           x-model="searchQuery"
           @input.debounce.500ms="searchLogs()"
           placeholder="Search your reading logs..."
           class="search-input">
    
    <div x-show="isSearching" class="loading-indicator">
        Searching...
    </div>
    
    <div x-show="searchResults.length > 0" class="search-results">
        <template x-for="result in searchResults" :key="result.id">
            <div class="search-result-item">
                <h4 x-text="result.passage_text"></h4>
                <p x-text="result.notes_text"></p>
            </div>
        </template>
    </div>
    
    <div x-show="searchQuery.length > 0 && searchResults.length === 0 && !isSearching" 
         class="no-results">
        No reading logs found for "<span x-text="searchQuery"></span>"
    </div>
</div>
```

### 2. Dynamic Content Updates

```html
<!-- Calendar component with reactive date selection -->
<div x-data="calendarComponent()" class="calendar-widget">
    <div class="calendar-header">
        <button @click="previousMonth()" class="nav-button">‹</button>
        <h3 x-text="currentMonthYear"></h3>
        <button @click="nextMonth()" class="nav-button">›</button>
    </div>
    
    <div class="calendar-grid">
        <template x-for="day in calendarDays" :key="day.date">
            <div :class="getDayClasses(day)" 
                 @click="selectDate(day)"
                 x-text="day.day">
            </div>
        </template>
    </div>
    
    <div x-show="selectedDate" class="selected-date-info">
        <h4>Reading for <span x-text="formatSelectedDate()"></span></h4>
        <div x-show="selectedDateLogs.length > 0">
            <template x-for="log in selectedDateLogs" :key="log.id">
                <div class="log-summary">
                    <span x-text="log.passage_text"></span>
                </div>
            </template>
        </div>
        <div x-show="selectedDateLogs.length === 0" class="no-logs">
            No reading logged for this date
        </div>
    </div>
</div>

<script>
function calendarComponent() {
    return {
        currentDate: new Date(),
        selectedDate: null,
        readingLogs: @json($readingLogs ?? []),
        
        get currentMonthYear() {
            return this.currentDate.toLocaleDateString('en-US', { 
                month: 'long', 
                year: 'numeric' 
            });
        },
        
        get calendarDays() {
            // Generate calendar days for current month
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const days = [];
            
            // Add days from previous month to fill first week
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
            
            // Generate 42 days (6 weeks)
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                days.push({
                    date: date.toISOString().split('T')[0],
                    day: date.getDate(),
                    isCurrentMonth: date.getMonth() === month,
                    isToday: this.isToday(date),
                    hasReading: this.hasReadingOnDate(date)
                });
            }
            
            return days;
        },
        
        get selectedDateLogs() {
            if (!this.selectedDate) return [];
            return this.readingLogs.filter(log => 
                log.date_read === this.selectedDate
            );
        },
        
        previousMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.selectedDate = null;
        },
        
        nextMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.selectedDate = null;
        },
        
        selectDate(day) {
            this.selectedDate = day.date;
        },
        
        getDayClasses(day) {
            return {
                'calendar-day': true,
                'current-month': day.isCurrentMonth,
                'other-month': !day.isCurrentMonth,
                'today': day.isToday,
                'has-reading': day.hasReading,
                'selected': this.selectedDate === day.date
            };
        },
        
        isToday(date) {
            const today = new Date();
            return date.toDateString() === today.toDateString();
        },
        
        hasReadingOnDate(date) {
            const dateStr = date.toISOString().split('T')[0];
            return this.readingLogs.some(log => log.date_read === dateStr);
        },
        
        formatSelectedDate() {
            if (!this.selectedDate) return '';
            return new Date(this.selectedDate).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>
```

### 3. State Synchronization with HTMX

```html
<!-- Component that responds to HTMX events -->
<div x-data="streakCounter()" 
     @reading-logged.window="updateStreak()"
     class="streak-display">
    
    <div class="streak-number">
        <span x-text="currentStreak" class="streak-count"></span>
        <span class="streak-label">Day Streak</span>
    </div>
    
    <div x-show="showCelebration" 
         x-transition.scale.origin.center
         class="celebration-message">
        🔥 Streak updated!
    </div>
</div>

<script>
function streakCounter() {
    return {
        currentStreak: {{ $currentStreak ?? 0 }},
        showCelebration: false,
        
        updateStreak() {
            // Fetch updated streak from server
            fetch('/streak')
                .then(response => response.json())
                .then(data => {
                    if (data.current_streak > this.currentStreak) {
                        this.showCelebration = true;
                        setTimeout(() => {
                            this.showCelebration = false;
                        }, 2000);
                    }
                    this.currentStreak = data.current_streak;
                });
        }
    }
}
</script>
```

## Integration with HTMX

### 1. Alpine State Updates from HTMX Responses

```html
<!-- Form that updates Alpine state after HTMX submission -->
<div x-data="readingLogManager()">
    <form hx-post="/logs" 
          hx-target="#form-response"
          @htmx:after-request="handleFormResponse($event)">
        @csrf
        <!-- form fields -->
        <button type="submit">Save Reading</button>
    </form>
    
    <div id="form-response"></div>
    
    <!-- Reading logs list that updates -->
    <div class="reading-logs">
        <template x-for="log in recentLogs" :key="log.id">
            <div class="log-item">
                <span x-text="log.passage_text"></span>
                <span x-text="log.date_read"></span>
            </div>
        </template>
    </div>
</div>

<script>
function readingLogManager() {
    return {
        recentLogs: @json($recentLogs ?? []),
        
        handleFormResponse(event) {
            if (event.detail.xhr.status === 200) {
                // Success - refresh the logs list
                this.refreshLogs();
                
                // Trigger custom event for other components
                this.$dispatch('reading-logged');
            }
        },
        
        refreshLogs() {
            fetch('/logs?recent=5')
                .then(response => response.json())
                .then(data => {
                    this.recentLogs = data;
                });
        }
    }
}
</script>
```

### 2. HTMX Triggers from Alpine Actions

```html
<!-- Alpine component that triggers HTMX requests -->
<div x-data="bookSelector()">
    <select x-model="selectedBook" 
            @change="loadChapters()"
            name="book_id">
        <option value="">Select a book...</option>
        @foreach($books as $book)
            <option value="{{ $book['id'] }}">{{ $book['name'] }}</option>
        @endforeach
    </select>
    
    <select x-model="selectedChapter" 
            :disabled="!selectedBook"
            name="chapter">
        <option value="">Select chapter...</option>
        <template x-for="chapter in availableChapters" :key="chapter">
            <option :value="chapter" x-text="chapter"></option>
        </template>
    </select>
    
    <!-- Hidden element for HTMX trigger -->
    <div id="chapter-loader" 
         hx-get="/books/chapters" 
         hx-include="[name='book_id']"
         hx-target="#chapter-data"
         style="display: none;"></div>
    
    <div id="chapter-data" @htmx:after-request="updateChapters($event)"></div>
</div>

<script>
function bookSelector() {
    return {
        selectedBook: '',
        selectedChapter: '',
        availableChapters: [],
        
        loadChapters() {
            if (this.selectedBook) {
                // Trigger HTMX request
                htmx.trigger('#chapter-loader', 'htmx:trigger');
            } else {
                this.availableChapters = [];
                this.selectedChapter = '';
            }
        },
        
        updateChapters(event) {
            if (event.detail.xhr.status === 200) {
                const response = JSON.parse(event.detail.xhr.responseText);
                this.availableChapters = response.chapters;
                this.selectedChapter = '';
            }
        }
    }
}
</script>
```

## Common UI Patterns

### 1. Modal Components

```html
<!-- Modal with Alpine.js state management -->
<div x-data="{ modalOpen: false }" @keydown.escape.window="modalOpen = false">
    <button @click="modalOpen = true">
        Add Reading Log
    </button>
    
    <div x-show="modalOpen" 
         x-transition.opacity
         @click="modalOpen = false">
        
        <div x-show="modalOpen"
             x-transition.scale.origin.center
             @click.stop>
            
            <div>
                <h2>Add Reading Log</h2>
                <button @click="modalOpen = false">×</button>
            </div>
            
            <div>
                <form hx-post="/logs" 
                      hx-target="#form-response"
                      @htmx:after-request="if($event.detail.xhr.status === 200) modalOpen = false">
                    @csrf
                    <!-- form fields -->
                    <div id="form-response"></div>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
```

### 2. Accordion/Collapsible Content

```html
<!-- Reading log history with expandable notes -->
<div x-data="{ expandedLogs: new Set() }">
    @foreach($readingLogs as $log)
        <div>
            <div @click="expandedLogs.has({{ $log->id }}) ? 
                         expandedLogs.delete({{ $log->id }}) : 
                         expandedLogs.add({{ $log->id }})">
                
                <h3>{{ $log->passage_text }}</h3>
                <span>{{ $log->date_read->format('M d, Y') }}</span>
                
                <svg x-show="!expandedLogs.has({{ $log->id }})">
                    <!-- down arrow -->
                </svg>
                <svg x-show="expandedLogs.has({{ $log->id }})">
                    <!-- up arrow -->
                </svg>
            </div>
            
            <div x-show="expandedLogs.has({{ $log->id }})" 
                 x-transition.slide.down>
                @if($log->notes_text)
                    <p>{{ $log->notes_text }}</p>
                @else
                    <p>No notes for this reading</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
```

### 3. Tab Components

```html
<!-- Statistics dashboard with tabs -->
<div x-data="{ activeTab: 'overview' }">
    <div>
        <button @click="activeTab = 'overview'" 
                :class="{ 'active': activeTab === 'overview' }">
            Overview
        </button>
        <button @click="activeTab = 'books'" 
                :class="{ 'active': activeTab === 'books' }">
            Book Progress
        </button>
        <button @click="activeTab = 'calendar'" 
                :class="{ 'active': activeTab === 'calendar' }">
            Calendar
        </button>
    </div>
    
    <div>
        <div x-show="activeTab === 'overview'" 
             x-transition.fade>
            <!-- Overview content -->
            <div hx-get="/stats/summary" hx-trigger="load" hx-target="this">
                Loading overview...
            </div>
        </div>
        
        <div x-show="activeTab === 'books'" 
             x-transition.fade>
            <!-- Book progress content -->
            <div hx-get="/stats/books" hx-trigger="load" hx-target="this">
                Loading book progress...
            </div>
        </div>
        
        <div x-show="activeTab === 'calendar'" 
             x-transition.fade>
            <!-- Calendar content -->
            <div hx-get="/calendar" hx-trigger="load" hx-target="this">
                Loading calendar...
            </div>
        </div>
    </div>
</div>
```

## Performance Considerations

### 1. Lazy Loading Components

```html
<!-- Only initialize Alpine component when needed -->
<div x-data="expensiveComponent()" 
     x-init="$nextTick(() => { if (visible) initialize() })">
    <!-- Component content -->
</div>
```

### 2. Memory Management

```javascript
// Clean up event listeners and timers
function componentWithCleanup() {
    return {
        timer: null,
        
        init() {
            this.timer = setInterval(() => {
                // Do something
            }, 1000);
        },
        
        destroy() {
            if (this.timer) {
                clearInterval(this.timer);
            }
        }
    }
}
```

### 3. Efficient Reactivity

```html
<!-- Use x-effect for complex reactive computations -->
<div x-data="{ 
    items: [], 
    filter: '', 
    filteredItems: [] 
}" 
x-effect="filteredItems = items.filter(item => 
    item.name.toLowerCase().includes(filter.toLowerCase())
)">
    
    <input x-model="filter" placeholder="Filter items...">
    
    <template x-for="item in filteredItems" :key="item.id">
        <div x-text="item.name"></div>
    </template>
</div>
```

## Testing Alpine Components

### 1. Unit Testing with Alpine Test Utils

```javascript
// tests/js/alpine-components.test.js
import Alpine from 'alpinejs'
import { fireEvent, screen } from '@testing-library/dom'

test('reading log form validates correctly', async () => {
    document.body.innerHTML = `
        <div x-data="readingLogForm()">
            <select x-model="form.book_id" data-testid="book-select">
                <option value="">Select book</option>
                <option value="1">Genesis</option>
            </select>
            <button :disabled="!isFormValid()" data-testid="submit-btn">
                Submit
            </button>
        </div>
    `;
    
    Alpine.start();
    
    const bookSelect = screen.getByTestId('book-select');
    const submitBtn = screen.getByTestId('submit-btn');
    
    expect(submitBtn).toBeDisabled();
    
    fireEvent.change(bookSelect, { target: { value: '1' } });
    
    // Test form validation logic
});
```

### 2. Integration Testing with Laravel Dusk

```php
// tests/Browser/AlpineInteractionTest.php
class AlpineInteractionTest extends DuskTestCase
{
    public function test_book_selector_updates_chapters()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logs/create')
                   ->select('book_id', '1')
                   ->waitFor('[data-testid="chapter-select"] option[value="1"]')
                   ->assertSeeIn('[data-testid="chapter-select"]', '1');
        });
    }
}
```

This guide provides the foundation for implementing lightweight, reactive client-side interactions using Alpine.js while maintaining the server-driven architecture with HTMX. 