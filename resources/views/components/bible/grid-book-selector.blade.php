@props([
    'books' => [],
    'selectedTestament' => 'old',
    'name' => 'book_id',
    'error' => null,
    'searchPlaceholder' => 'Search books...',
    'value' => '',
    'required' => true
])

@php
    $hasError = !empty($error);
    $componentId = 'book-selector-' . uniqid();
@endphp

<div class="space-y-4"
     x-data="gridBookSelector(@js($books), '{{ $selectedTestament }}', '{{ old($name, $value) }}')"
     x-init="init()"
     id="{{ $componentId }}">

    <!-- Testament Toggle -->
    <div class="flex bg-gray-50 dark:bg-gray-700 rounded-lg p-1">
        <button type="button"
                x-on:click="setActiveTestament('old')"
                :class="{
                    'bg-primary-500 text-white shadow-sm': activeTestament === 'old',
                    'text-gray-600 dark:text-gray-400 hover:text-primary-500 hover:bg-white dark:hover:bg-gray-600': activeTestament !== 'old'
                }"
                class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded">
            Old Testament
        </button>
        <button type="button"
                x-on:click="setActiveTestament('new')"
                :class="{
                    'bg-primary-500 text-white shadow-sm': activeTestament === 'new',
                    'text-gray-600 dark:text-gray-400 hover:text-primary-500 hover:bg-white dark:hover:bg-gray-600': activeTestament !== 'new'
                }"
                class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded">
            New Testament
        </button>
    </div>

    <!-- Search Input -->
    <div class="relative">
        <input type="search"
               placeholder="{{ $searchPlaceholder }}"
               x-model="searchQuery"
               class="form-input pl-10 w-full">
        <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>

    <!-- Book Grid -->
    <div x-show="filteredBooks.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        <template x-for="book in filteredBooks" :key="book.id">
            <button type="button"
                    @click="selectBook(book)"
                    class="book-button p-3 rounded-lg border-2 text-center transition-all duration-200 shadow-sm"
                    :class="getBookButtonClass(book)"
                    style="min-height: 44px; min-width: 44px;">
                <div class="font-semibold text-sm mb-1" x-text="book.name"></div>
                <div class="text-xs opacity-75" x-text="book.chapters + ' chapters'"></div>
            </button>
        </template>
    </div>

    <!-- No Results Message -->
    <div x-show="searchQuery && filteredBooks.length === 0"
         class="text-center py-8 text-gray-500 dark:text-gray-400">
        <div class="text-lg mb-2">📚</div>
        <div>No books found</div>
        <div class="text-sm mt-1">Try a different search term</div>
    </div>

    <!-- Hidden Input for Form Submission -->
    <input type="hidden"
           name="{{ $name }}"
           x-model="selectedBookId"
           {{ $required ? 'required' : '' }}>

    <!-- Error Message -->
    @if($hasError)
        <p class="form-error" role="alert">
            {{ $error }}
        </p>
    @endif
</div>

<script>
function gridBookSelector(books, initialTestament, initialValue) {
    return {
        allBooks: books,
        searchQuery: '',
        selectedBookId: initialValue || '',
        selectedBook: null,
        activeTestament: initialTestament,

        init() {
            if (this.selectedBookId) {
                this.selectedBook = this.allBooks.find(book => book.id == this.selectedBookId);
            }
        },

        setActiveTestament(testament) {
            this.activeTestament = testament;
        },

        get filteredBooks() {
            let books = this.allBooks.filter(book => book.testament === this.activeTestament);

            if (this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase();
                books = books.filter(book =>
                    book.name.toLowerCase().includes(query)
                );
            }

            return books;
        },

        selectBook(book) {
            this.selectedBookId = book.id;
            this.selectedBook = book;

            this.$dispatch('book-selected', {
                book: book,
                bookId: book.id,
                bookName: book.name,
                chapters: book.chapters
            });
        },

        getBookButtonClass(book) {
            return this.selectedBookId == book.id
                ? 'bg-primary-500 text-white border-primary-500 shadow-md book-button-selected'
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50 hover:shadow-md book-button-default';
        }
    }
}
</script>

<style>
.book-button {
    touch-action: manipulation;
}

.book-button:hover {
    transform: translateY(-1px);
}

.book-button:active {
    transform: translateY(0);
}

.book-button-selected {
    animation: selectPulse 0.3s ease-out;
}

@keyframes selectPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Touch target compliance */
@media (max-width: 768px) {
    .book-button {
        min-height: 48px;
        padding: 12px 8px;
    }
}
</style>