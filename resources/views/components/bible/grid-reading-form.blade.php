@props([
    'books' => []
])

<div id="reading-log-form-container">

<form hx-post="{{ route('logs.store') }}"
      hx-target="#reading-log-form-container"
      hx-swap="outerHTML"
      class="space-y-6"
      x-data="{ selectedBook: null, selectedChapters: null }">
    @csrf

    <!-- Getting Started Instructions -->
    <div x-show="!selectedBook && !selectedChapters"
         class="text-center py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
        <div class="text-2xl mb-2">📚</div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Log Your Bible Reading</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm max-w-md mx-auto">
            Start by selecting a testament, then choose the book you read from. You can select individual chapters or create ranges.
        </p>
    </div>

    <!-- Progressive Disclosure: Book Selection or Chapter Selection -->
    <div x-show="!selectedBook" x-transition>
        <x-bible.grid-book-selector
            :books="$books"
            name="book_id"
            @book-selected="selectedBook = $event.detail.book; selectedChapters = null"
            :error="$errors->first('book_id')"
            :value="old('book_id')"
        />
    </div>

    <div x-show="selectedBook" x-transition>
        <x-bible.grid-chapter-selector
            x-bind:book="selectedBook"
            name="chapter_input"
            @chapter-selected="selectedChapters = $event.detail.input"
            @back-to-books="selectedBook = null; selectedChapters = null"
            :error="$errors->first('chapter_input')"
            :value="old('chapter_input')"
        />
    </div>

    <!-- Selection Label Display -->
    <div x-show="selectedBook"
         class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 transition-all duration-200">
        <div class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            <div class="text-lg">📖</div>
            <div class="flex-1">
                <span class="text-gray-600 dark:text-gray-400">Selection:</span>
                <span class="ml-2 text-gray-900 dark:text-gray-100 font-semibold" x-text="selectedBook ? (selectedChapters ? `${selectedBook.name} ${selectedChapters}` : selectedBook.name) : ''"></span>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="space-y-2">
        <label class="form-label" for="notes_text">Notes (Optional)</label>
        <textarea
            name="notes_text"
            id="notes_text"
            placeholder="Share any thoughts, insights, or questions from your reading..."
            rows="4"
            maxlength="1000"
            class="form-input">{{ old('notes_text') }}</textarea>
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>Share your insights, questions, or reflections</span>
            <span x-data="{ count: 0 }" x-init="count = $refs.textarea?.value.length || 0">
                <span x-text="count"></span>/1000
                <textarea x-ref="textarea" x-on:input="count = $event.target.value.length" style="display: none;">{{ old('notes_text') }}</textarea>
            </span>
        </div>
        @if($errors->first('notes_text'))
            <p class="form-error">{{ $errors->first('notes_text') }}</p>
        @endif
    </div>

    <!-- Submit Button -->
    <div class="pt-6 border-t border-gray-200 dark:border-gray-600">
        <button type="submit"
                hx-indicator="#save-loading"
                class="btn btn-accent w-full sm:w-auto px-6 py-3 text-base font-medium"
                x-bind:disabled="!selectedBook || !selectedChapters"
                x-bind:class="{ 'opacity-50 cursor-not-allowed': !selectedBook || !selectedChapters }">
            <span id="save-loading" class="htmx-indicator hidden">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            </span>
            <span class="htmx-indicator-hidden">Log Reading</span>
        </button>

        @if(session('success'))
            <p class="form-success mt-4">✅ {{ session('success') }}</p>
        @endif
    </div>
</form>

<style>
    .htmx-indicator { display: none; }
    .htmx-request .htmx-indicator { display: flex; }
    .htmx-request .htmx-indicator-hidden { display: none !important; }
</style>

</div>