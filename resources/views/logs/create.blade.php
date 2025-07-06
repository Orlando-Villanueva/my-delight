@extends('layouts.authenticated')

@section('page-title', 'Log Reading')
@section('page-subtitle', 'Record your daily Bible reading')

@section('content')
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Log Bible Reading</h1>

        <form hx-post="{{ route('logs.store') }}" hx-target="#form-response" hx-swap="innerHTML" x-data="readingLogForm()"
            class="space-y-6">
            @csrf

            <div id="form-response">
                <!-- HTMX responses (success or errors) will appear here -->
            </div>

            <!-- Date Selection: Today or Yesterday (Late Logging Grace) -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">When did you read?</label>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="radio" id="today" name="date_read" value="{{ today()->toDateString() }}"
                            x-model="form.date_read" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="today" class="ml-3 block text-sm font-medium text-gray-700">
                            📖 Today ({{ today()->format('M d, Y') }})
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="yesterday" name="date_read"
                            value="{{ today()->subDay()->toDateString() }}" x-model="form.date_read"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="yesterday" class="ml-3 block text-sm font-medium text-gray-700">
                            📅 Yesterday ({{ today()->subDay()->format('M d, Y') }}) - <span class="text-gray-500 italic">I
                                forgot to log it</span>
                        </label>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">
                    💡 <strong>Grace Period:</strong> You can log today's reading or catch up on yesterday if you forgot.
                </div>
            </div>

            <!-- Bible Book Selection -->
            <div class="space-y-2">
                <label for="book_id" class="block text-sm font-medium text-gray-700">Bible Book:</label>
                <select id="book_id" name="book_id" x-model="form.book_id" @change="updateChapters()" required
                    class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">Select a book...</option>
                    @foreach ($books as $book)
                        <option value="{{ $book['id'] }}" data-chapters="{{ $book['chapters'] }}"
                            {{ old('book_id') == $book['id'] ? 'selected' : '' }}>
                            {{ $book['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Chapter Selection (Dynamic) - Supports Single or Range -->
            <div class="space-y-2">
                <label for="chapter_input" class="block text-sm font-medium text-gray-700">Chapter(s):</label>
                <div class="space-y-3">
                    <!-- Chapter Input Field -->
                    <input type="text" id="chapter_input" name="chapter_input" x-model="form.chapter_input"
                        :disabled="!form.book_id" @input="validateChapterInput()" placeholder="e.g., 3 or 1-5" required
                        class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed">

                    <!-- Helper Text -->
                    <div class="text-sm text-gray-500">
                        <div x-show="!form.book_id" x-cloak class="italic">
                            📚 Please select a book first
                        </div>
                        <div x-show="form.book_id" x-cloak>
                            💡 Enter a single chapter (e.g., <strong>3</strong>) or range (e.g., <strong>1-5</strong>)
                            <br>
                            📖 <span x-text="form.book_name"></span> has <span x-text="availableChapters"></span> chapters
                        </div>
                    </div>

                    <!-- Validation Feedback -->
                    <div x-show="form.chapter_validation_message" x-cloak
                        :class="form.chapter_validation_valid ? 'text-green-600' : 'text-red-600'"
                        class="text-sm font-medium">
                        <span x-text="form.chapter_validation_message"></span>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="space-y-2">
                <label for="notes_text" class="block text-sm font-medium text-gray-700">Notes (Optional):</label>
                <textarea id="notes_text" name="notes_text" rows="4" maxlength="500" x-model="form.notes_text"
                    @input="updateCharacterCount()" placeholder="Share any thoughts, insights, or questions from your reading..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical">{{ old('notes_text') }}</textarea>
                <div class="text-sm text-gray-500">
                    <span x-text="characterCount"></span>/500 characters
                    <span x-show="characterCount > 450" x-cloak class="text-orange-500">
                        (approaching limit)
                    </span>
                    <span x-show="characterCount === 500" x-cloak class="text-red-500">
                        (limit reached)
                    </span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-gray-200 flex items-center space-x-4">
                <button type="submit" hx-indicator="#save-loading" :disabled="!isFormValid()"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span id="save-loading" class="htmx-indicator">Saving...</span>
                    <span>Log Reading</span>
                </button>

                <button :hx-get="previousView === 'logs' ? '{{ route('logs.index') }}' : '{{ route('dashboard') }}'"
                    hx-target="#page-container" hx-swap="innerHTML" hx-push-url="true" @click="currentView = previousView"
                    type="button"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <script>
        function readingLogForm() {
            return {
                form: {
                    book_id: @json(old('book_id', '')),
                    book_name: '',
                    chapter_input: @json(old('chapter_input', '')),
                    date_read: @json(old('date_read', today()->toDateString())),
                    notes_text: @json(old('notes_text', '')),
                    chapter_validation_message: '',
                    chapter_validation_valid: false
                },
                availableChapters: 0,
                characterCount: @json(old('notes_text') ? strlen(old('notes_text')) : 0),

                init() {
                    // Initialize chapter count if book is pre-selected
                    if (this.form.book_id) {
                        this.updateChapters();
                    }
                    this.updateCharacterCount();
                    // Validate chapter input if pre-filled
                    if (this.form.chapter_input) {
                        this.validateChapterInput();
                    }
                },

                updateChapters() {
                    const bookSelect = document.getElementById('book_id');
                    const selectedOption = bookSelect.options[bookSelect.selectedIndex];

                    if (selectedOption && selectedOption.dataset.chapters) {
                        this.availableChapters = parseInt(selectedOption.dataset.chapters);
                        this.form.book_name = selectedOption.text;
                        // Re-validate chapter input when book changes
                        this.validateChapterInput();
                    } else {
                        this.availableChapters = 0;
                        this.form.book_name = '';
                        this.form.chapter_input = '';
                        this.form.chapter_validation_message = '';
                        this.form.chapter_validation_valid = false;
                    }
                },

                validateChapterInput() {
                    const input = this.form.chapter_input.trim();

                    // Clear validation if no book selected
                    if (!this.form.book_id || !this.availableChapters) {
                        this.form.chapter_validation_message = '';
                        this.form.chapter_validation_valid = false;
                        return;
                    }

                    // Clear validation if no input
                    if (!input) {
                        this.form.chapter_validation_message = '';
                        this.form.chapter_validation_valid = false;
                        return;
                    }

                    // Check for range (e.g., "1-3")
                    const rangeMatch = input.match(/^(\d+)-(\d+)$/);
                    if (rangeMatch) {
                        const start = parseInt(rangeMatch[1]);
                        const end = parseInt(rangeMatch[2]);

                        if (start > end) {
                            this.form.chapter_validation_message =
                                '❌ Start chapter must be less than or equal to end chapter';
                            this.form.chapter_validation_valid = false;
                            return;
                        }

                        if (start < 1 || end > this.availableChapters) {
                            this.form.chapter_validation_message =
                                `❌ Chapters must be between 1 and ${this.availableChapters}`;
                            this.form.chapter_validation_valid = false;
                            return;
                        }

                        const chapterCount = end - start + 1;
                        this.form.chapter_validation_message =
                            `✅ ${chapterCount} chapters (${this.form.book_name} ${start}-${end})`;
                        this.form.chapter_validation_valid = true;
                        return;
                    }

                    // Check for single chapter
                    const singleMatch = input.match(/^\d+$/);
                    if (singleMatch) {
                        const chapter = parseInt(input);

                        if (chapter < 1 || chapter > this.availableChapters) {
                            this.form.chapter_validation_message =
                                `❌ Chapter must be between 1 and ${this.availableChapters}`;
                            this.form.chapter_validation_valid = false;
                            return;
                        }

                        this.form.chapter_validation_message = `✅ ${this.form.book_name} ${chapter}`;
                        this.form.chapter_validation_valid = true;
                        return;
                    }

                    // Invalid format
                    this.form.chapter_validation_message = '❌ Enter a single chapter (e.g., 3) or range (e.g., 1-5)';
                    this.form.chapter_validation_valid = false;
                },

                updateCharacterCount() {
                    this.characterCount = this.form.notes_text.length;
                },

                isFormValid() {
                    // Temporarily relaxed for testing - allow submission even with some validation errors
                    return this.form.book_id &&
                        this.form.chapter_input &&
                        this.form.date_read;
                    // Removed strict chapter validation and character count to test backend
                }
            }
        }
    </script>

    <style>
        /* Basic HTMX loading indicator styles */
        .htmx-indicator {
            display: none;
        }

        .htmx-request .htmx-indicator {
            display: inline;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
