{{-- Unified Reading Log Form Component --}}
{{-- This partial contains just the form logic and can be included in different layouts --}}

<div id="reading-log-form-container">

<form hx-post="{{ route('logs.store') }}" hx-target="#reading-log-form-container" hx-swap="outerHTML" class="space-y-6">
    @csrf


    <!-- Date Selection: Today or Yesterday -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">When did you read?</label>
        <div class="space-y-3">
            <div class="flex items-center">
                <input type="radio" id="today" name="date_read" value="{{ today()->toDateString() }}" 
                    {{ old('date_read', today()->toDateString()) == today()->toDateString() ? 'checked' : '' }}
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <label for="today" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    📖 Today ({{ today()->format('M d, Y') }})
                </label>
            </div>
            
            @if(isset($allowYesterday) && $allowYesterday)
                <div class="flex items-center">
                    <input type="radio" id="yesterday" name="date_read" value="{{ today()->subDay()->toDateString() }}" 
                        {{ old('date_read') == today()->subDay()->toDateString() ? 'checked' : '' }}
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <label for="yesterday" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        📅 Yesterday ({{ today()->subDay()->format('M d, Y') }}) - <span class="text-gray-500 dark:text-gray-400 italic">I forgot to log it</span>
                    </label>
                </div>
            @elseif(isset($allowYesterday))
                {{-- Show why yesterday is not available --}}
                <div class="flex items-center opacity-50">
                    <input type="radio" disabled 
                        class="h-4 w-4 text-gray-400 border-gray-300 dark:border-gray-600 cursor-not-allowed">
                    <label class="ml-3 block text-sm font-medium text-gray-400 dark:text-gray-500 cursor-not-allowed">
                        📅 Yesterday ({{ today()->subDay()->format('M d, Y') }}) - 
                        @if(isset($hasReadYesterday) && !$hasReadYesterday && isset($currentStreak) && $currentStreak > 0 && isset($hasReadToday) && !$hasReadToday)
                            <span class="italic">Would break your {{ $currentStreak }}-day streak</span>
                        @else
                            <span class="italic">Already logged</span>
                        @endif
                    </label>
                </div>
            @endif
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            💡 <strong>Grace Period:</strong> You can log today's reading or catch up on yesterday if you forgot.
        </div>
    </div>

    <!-- Bible Book Autocomplete -->
    <div
        x-data="bookAutocomplete(@js($books), @js($recentBooks ?? []))"
        @click.outside="showSuggestions = false"
        class="relative"
    >
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            📚 Bible Book
        </label>

        {{-- Search Input - Premium Styling with Dark Mode --}}
        <input
            type="text"
            x-model="search"
            @focus="showSuggestions = true"
            @keydown.escape="closeSuggestions()"
            @keydown.arrow-down.prevent="navigateDown()"
            @keydown.arrow-up.prevent="navigateUp()"
            @keydown.enter.prevent="selectFocused()"
            placeholder="Type book name... (e.g., Genesis, Psa, Matt)"
            class="w-full px-4 py-3.5 text-base border-2 border-gray-200 dark:border-gray-600 rounded-xl
                   bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                   focus:border-primary-500 focus:ring-4 focus:ring-primary-100 dark:focus:ring-primary-900/30
                   transition-all duration-200
                   placeholder:text-gray-400 dark:placeholder:text-gray-500"
        >

        {{-- Autocomplete Suggestions Dropdown - Premium Styling with Dark Mode --}}
        <div
            x-show="showSuggestions"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 rounded-xl shadow-xl border-2 border-gray-100 dark:border-gray-700 max-h-96 overflow-y-auto"
        >
            {{-- Recent Books Section - Premium Styling --}}
            <template x-if="!search && recentBooks.length > 0">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">
                        📖 Recent
                    </div>
                    <template x-for="(book, index) in recentBooks" :key="book.id">
                        <div
                            @click="selectBook(book)"
                            :class="{'bg-primary-50 dark:bg-primary-900/20': focusedIndex === index}"
                            class="px-3 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg cursor-pointer transition-colors mb-1"
                        >
                            <div class="font-medium text-gray-900 dark:text-white" x-text="book.name"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="book.last_read_human"></div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Filtered Books by Testament --}}
            <template x-if="search">
                <div>
                    <template x-if="filteredBooks.length === 0">
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No books found
                        </div>
                    </template>
                    <template x-if="filteredBooks.length > 0">
                        <template x-for="book in filteredBooks" :key="book.id">
                            <div
                                @click="selectBook(book)"
                                class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors rounded-lg mx-2 my-1"
                            >
                                <span class="font-medium text-gray-900 dark:text-white" x-text="book.name"></span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2" x-text="`(${book.chapters} chapters)`"></span>
                            </div>
                        </template>
                    </template>
                </div>
            </template>

            {{-- Default: Testament-Grouped List --}}
            @php
                $oldTestament = collect($books)->where('testament', 'old')->values();
                $newTestament = collect($books)->where('testament', 'new')->values();
            @endphp

            <template x-if="!search">
                <div>
                    @if($oldTestament->isNotEmpty())
                        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                📜 Old Testament ({{ $oldTestament->count() }} books)
                            </div>
                        </div>
                        @foreach($oldTestament as $book)
                            <div
                                @click="selectBook(@js($book))"
                                class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors rounded-lg mx-2 my-1"
                            >
                                <span class="font-medium text-gray-900 dark:text-white">{{ $book['name'] }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $book['chapters'] }} chapters)</span>
                            </div>
                        @endforeach
                    @endif

                    @if($newTestament->isNotEmpty())
                        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 mt-2">
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                ✝️ New Testament ({{ $newTestament->count() }} books)
                            </div>
                        </div>
                        @foreach($newTestament as $book)
                            <div
                                @click="selectBook(@js($book))"
                                class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors rounded-lg mx-2 my-1"
                            >
                                <span class="font-medium text-gray-900 dark:text-white">{{ $book['name'] }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $book['chapters'] }} chapters)</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </template>
        </div>

        {{-- Hidden input for form submission --}}
        <input type="hidden" name="book_id" :value="selectedBook ? selectedBook.id : ''">

        {{-- Error Display --}}
        @if($errors->has('book_id'))
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('book_id') }}</p>
        @endif
    </div>

    <!-- Chapter Input -->
    <x-ui.input 
        name="chapter_input" 
        label="Chapter(s)" 
        placeholder="e.g., 3 or 1-5"
        :value="old('chapter_input')"
        required
        :error="$errors->first('chapter_input')"
        class="max-w-md"
    />

    <!-- Notes Section -->
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

    <!-- Form Actions -->
    <div class="pt-6 border-t border-gray-200 dark:border-gray-600">
        <div class="flex items-start">
            <x-ui.button 
                type="submit" 
                variant="accent" 
                size="lg"
                hx-indicator="#save-loading"
                class="w-full sm:w-auto px-6 py-3 text-base font-medium shadow-sm"
            >
                <span id="save-loading" class="htmx-indicator hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
                <span class="htmx-indicator-hidden">Log Reading</span>
            </x-ui.button>
        </div>
        
        @if(session('success'))
            <p class="form-success mt-4">✅ {{ session('success') }}</p>
        @endif
    </div>
</form>

<style>
    /* Alpine.js cloak - hide until initialized */
    [x-cloak] {
        display: none !important;
    }

    /* HTMX loading indicator styles */
    .htmx-indicator {
        display: none;
    }

    .htmx-request .htmx-indicator {
        display: flex;
        pointer-events: all;
    }

    .htmx-request .htmx-indicator-hidden {
        display: none !important;
    }
</style>

<script>
// Book Autocomplete Component (extracted function pattern per CLAUDE.md)
function bookAutocomplete(books, recentBooks) {
    return {
        search: '',
        showSuggestions: false,
        selectedBook: null,
        recentBooks: recentBooks,
        allBooks: books,
        focusedIndex: -1,

        get filteredBooks() {
            if (!this.search) return [];

            const query = this.search.toLowerCase();
            return this.allBooks.filter(book => {
                return book.name.toLowerCase().includes(query) ||
                       book.abbreviation?.toLowerCase().includes(query);
            });
        },

        selectBook(book) {
            this.selectedBook = book;
            this.search = book.name;
            this.showSuggestions = false;

            // Trigger chapter grid to show (for future implementation)
            this.$dispatch('book-selected', book);
        },

        closeSuggestions() {
            this.showSuggestions = false;
            this.focusedIndex = -1;
        },

        navigateDown() {
            const items = this.search ? this.filteredBooks : this.recentBooks;
            const maxIndex = items.length - 1;
            this.focusedIndex = Math.min(this.focusedIndex + 1, maxIndex);
        },

        navigateUp() {
            this.focusedIndex = Math.max(this.focusedIndex - 1, 0);
        },

        selectFocused() {
            const items = this.search ? this.filteredBooks : this.recentBooks;
            if (this.focusedIndex >= 0 && items[this.focusedIndex]) {
                this.selectBook(items[this.focusedIndex]);
            }
        }
    }
}
</script>
</div>