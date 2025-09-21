@props([
    'books' => [],
    'allowYesterday' => false,
    'hasReadYesterday' => false,
    'hasReadToday' => false,
    'currentStreak' => 0
])

<div id="reading-log-form-container">

<form hx-post="{{ route('logs.store') }}"
      hx-target="#reading-log-form-container"
      hx-swap="outerHTML"
      class="w-full max-w-md mx-auto space-y-6"
      x-data="mobileReadingForm(@js($books))"
      x-init="init()">
    @csrf

    <!-- Header -->
    <div class="text-center space-y-2">
        <div class="flex items-center justify-center gap-2">
            <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h2 class="text-xl font-semibold">Add Reading Log</h2>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">Select a book and chapter(s) to log your reading</p>
    </div>

    <!-- Date Display -->
    <div class="flex items-center justify-center gap-2 text-sm">
        <span class="text-gray-600 dark:text-gray-400">Date:</span>
        <span class="font-medium text-gray-900 dark:text-gray-100" x-text="formatDateForDisplay(selectedDate)"></span>
        <button
            type="button"
            x-show="canLogYesterday"
            @click="setToYesterday()"
            class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline underline-offset-2 transition-colors">
            log yesterday?
        </button>
    </div>

    <!-- Book Selection with Testament Toggle and Search -->
    <div x-show="!selectedBook" x-transition>
        <x-ui.card class="p-4">
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Select Book</label>
                <div class="flex gap-1">
                    <button
                        type="button"
                        x-on:click="setTestament('old')"
                        :class="testament === 'old' ? 'btn btn-primary btn-sm' : 'btn btn-outline btn-sm'"
                        class="text-xs px-3 py-1 h-7">
                        Old
                    </button>
                    <button
                        type="button"
                        x-on:click="setTestament('new')"
                        :class="testament === 'new' ? 'btn btn-primary btn-sm' : 'btn btn-outline btn-sm'"
                        class="text-xs px-3 py-1 h-7">
                        New
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="relative mb-3">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <x-ui.input
                    type="search"
                    placeholder="Search books..."
                    x-model="searchTerm"
                    class="pl-10" />
            </div>

            <div class="max-h-48 overflow-y-auto">
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="book in filteredBooks" :key="book.id">
                        <button
                            type="button"
                            x-on:click="handleBookSelect(book)"
                            class="justify-start text-left h-auto py-2 px-3 rounded border-2 transition-all duration-200 hover:-translate-y-px active:translate-y-0"
                            :class="book.id === selectedBookId ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50'"
                            style="min-height: 44px; touch-action: manipulation;">
                            <div class="flex flex-col items-start">
                                <span class="text-xs font-medium" x-text="book.name"></span>
                                <span class="text-xs opacity-75" x-text="book.chapters + ' chapters'"></span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- No Results Message -->
            <div x-show="searchTerm && filteredBooks.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                <div class="text-lg mb-2">📚</div>
                <div>No books found</div>
                <div class="text-sm mt-1">Try a different search term</div>
            </div>
        </x-ui.card>
    </div>

    <!-- Chapter Selection -->
    <div x-show="selectedBookData" x-transition>
        <x-ui.card class="p-4">
            <div class="flex items-center gap-2 mb-3">
                <x-ui.button
                    type="button"
                    variant="ghost"
                    size="icon"
                    x-on:click="handleBackToBooks()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </x-ui.button>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Select Chapter(s) - <span x-text="selectedBookData ? selectedBookData.name : ''"></span>
                </label>
            </div>

            <!-- Instruction Text -->
            <div class="text-sm text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 mb-3">
                💡 <strong>Tip:</strong> Click a chapter, then click another to create a range (e.g., 3-7)
            </div>

            <div class="max-h-48 overflow-y-auto">
                <div class="grid grid-cols-6 gap-2">
                    <template x-for="chapter in chapterNumbers" :key="chapter">
                        <button
                            type="button"
                            x-on:click="handleChapterSelect(chapter)"
                            class="aspect-square flex items-center justify-center text-sm font-medium rounded border-2 transition-all duration-200 hover:-translate-y-px active:translate-y-0"
                            :class="getChapterButtonClass(chapter)"
                            style="min-height: 44px; touch-action: manipulation;"
                            x-text="chapter">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Reading Preview -->
            <div x-show="getReadingText()" class="mt-3 p-2 bg-gray-50 dark:bg-gray-700 rounded-md">
                <p class="text-sm font-medium text-center text-gray-900 dark:text-gray-100" x-text="getReadingText()"></p>
            </div>
        </x-ui.card>
    </div>

    <!-- Notes Section -->
    <div x-show="selectedBook">
        <x-ui.textarea
            name="notes_text"
            label="Reading Notes (Optional)"
            placeholder="Add any thoughts, insights, or reflections..."
            rows="4"
            maxlength="1000"
            :showCounter="true"
            :error="$errors->first('notes_text')"
            :value="old('notes_text')"
            help="Share your insights, questions, or reflections"
            class="resize-none" />
    </div>

    <!-- Submit Button -->
    <x-ui.button
        type="submit"
        variant="accent"
        size="lg"
        class="w-full"
        hx-indicator="#save-loading"
        x-bind:disabled="!isSubmitEnabled">
        <span id="save-loading" class="htmx-indicator hidden">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        </span>
        <span class="htmx-indicator-hidden flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Log Reading
        </span>
    </x-ui.button>

    <!-- Hidden Inputs for Form Submission -->
    <input type="hidden" name="book_id" x-model="selectedBookId">
    <input type="hidden" name="chapter_input" x-model="chapterInput">
    <input type="hidden" name="date_read" x-model="selectedDate">

    @if(session('success'))
        <p class="form-success mt-4">✅ {{ session('success') }}</p>
    @endif
</form>

<style>
    .htmx-indicator { display: none; }
    .htmx-request .htmx-indicator { display: flex; }
    .htmx-request .htmx-indicator-hidden { display: none !important; }
</style>

</div>

<script>
function mobileReadingForm(books) {
    return {
        // Book data
        allBooks: books,

        // State
        searchTerm: '',
        selectedBookId: '',
        selectedBook: null,
        selectedBookData: null,
        testament: 'old',
        startChapter: null,
        endChapter: null,
        chapterNumbers: [],
        selectedDate: '',

        init() {
            // Initialize date to today or existing form value
            this.selectedDate = '{{ old("date_read", today()->toDateString()) }}';

            // Parse any existing form values
            const oldBookId = '{{ old("book_id") }}';
            const oldChapterInput = '{{ old("chapter_input") }}';

            if (oldBookId) {
                this.selectedBookId = oldBookId;
                this.selectedBook = this.allBooks.find(book => book.id == oldBookId);
                this.selectedBookData = this.selectedBook;
                this.updateChapterNumbers();

                if (oldChapterInput) {
                    this.parseChapterInput(oldChapterInput);
                }
            }
        },

        get filteredBooks() {
            return this.allBooks.filter(book => {
                const matchesTestament = book.testament === this.testament;
                const matchesSearch = !this.searchTerm ||
                    book.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                return matchesTestament && matchesSearch;
            });
        },

        get chapterInput() {
            if (this.startChapter === null) return '';
            if (this.endChapter === null || this.endChapter === this.startChapter) {
                return this.startChapter.toString();
            }
            return `${this.startChapter}-${this.endChapter}`;
        },

        get isSubmitEnabled() {
            return this.selectedBook && this.startChapter !== null;
        },

        get canLogYesterday() {
            const today = new Date().toISOString().split('T')[0];
            const yesterday = new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            return this.selectedDate !== yesterday && {{ $allowYesterday ? 'true' : 'false' }};
        },

        formatDateForDisplay(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            return date.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            });
        },

        setToYesterday() {
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            this.selectedDate = yesterday.toISOString().split('T')[0];
        },

        setTestament(testament) {
            this.testament = testament;
        },

        handleBookSelect(book) {
            this.selectedBookId = book.id;
            this.selectedBook = book.name;
            this.selectedBookData = book;
            this.startChapter = null;
            this.endChapter = null;
            this.updateChapterNumbers();
        },

        handleBackToBooks() {
            this.selectedBook = null;
            this.selectedBookData = null;
            this.selectedBookId = '';
            this.startChapter = null;
            this.endChapter = null;
            this.chapterNumbers = [];
        },

        updateChapterNumbers() {
            if (this.selectedBookData) {
                this.chapterNumbers = Array.from({length: this.selectedBookData.chapters}, (_, i) => i + 1);
            }
        },

        handleChapterSelect(chapter) {
            if (this.startChapter === null) {
                // First click - select single chapter
                this.startChapter = chapter;
                this.endChapter = null;
                return;
            }

            if (this.startChapter === chapter && this.endChapter === null) {
                // Same chapter clicked - unselect
                this.startChapter = null;
                return;
            }

            if (this.endChapter === null && chapter > this.startChapter) {
                // Second click creates range if chapter > start
                this.endChapter = chapter;
            } else if (this.endChapter && chapter === this.startChapter) {
                // Clicked start again - remove range
                this.endChapter = null;
            } else if (chapter < this.startChapter) {
                // Clicked before start - new single selection
                this.startChapter = chapter;
                this.endChapter = null;
            } else {
                // Any other click - new single selection
                this.startChapter = chapter;
                this.endChapter = null;
            }
        },

        parseChapterInput(input) {
            if (!input) return;

            if (input.includes('-')) {
                const [start, end] = input.split('-').map(Number);
                if (start && end && start <= end) {
                    this.startChapter = start;
                    this.endChapter = end;
                }
            } else if (/^\d+$/.test(input)) {
                this.startChapter = parseInt(input);
                this.endChapter = null;
            }
        },

        getReadingText() {
            if (!this.selectedBook || this.startChapter === null) return '';
            if (this.endChapter && this.endChapter !== this.startChapter) {
                return `${this.selectedBook} ${this.startChapter}-${this.endChapter}`;
            }
            return `${this.selectedBook} ${this.startChapter}`;
        },

        getChapterButtonClass(chapter) {
            const isSelected = this.isChapterSelected(chapter);
            const isStart = chapter === this.startChapter;
            const isEnd = chapter === this.endChapter;

            if (isSelected) {
                let classes = 'bg-primary-500 text-white border-primary-500';
                if (isStart || isEnd) {
                    classes += ' ring-2 ring-primary-500 ring-offset-2';
                }
                return classes;
            }

            return 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50';
        },

        isChapterSelected(chapter) {
            if (this.startChapter === null) return false;
            if (this.endChapter === null) return chapter === this.startChapter;
            return chapter >= this.startChapter && chapter <= this.endChapter;
        }
    };
}
</script>