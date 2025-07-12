{{-- Reading Log Form Content Partial --}}
{{-- This partial is loaded via HTMX for modal display --}}

<div>
    <div class="flex items-center justify-between mb-6">
        <h2 id="modal-title" class="text-2xl font-bold text-gray-900">Log Bible Reading</h2>

        {{-- Modal Close Button --}}
        <button type="button" @click="modalOpen = false"
            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <p id="modal-description" class="sr-only">
        Form to log your daily Bible reading with book, chapter, and optional notes
    </p>

    <form hx-post="{{ route('logs.store') }}" hx-target="#reading-log-modal-content" hx-swap="innerHTML" class="space-y-6">
        @csrf

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="text-red-400">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Date Selection: Today or Yesterday -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">When did you read?</label>
            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="radio" id="today" name="date_read" value="{{ today()->toDateString() }}" 
                        {{ old('date_read', today()->toDateString()) == today()->toDateString() ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <label for="today" class="ml-3 block text-sm font-medium text-gray-700">
                        📖 Today ({{ today()->format('M d, Y') }})
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="yesterday" name="date_read" value="{{ today()->subDay()->toDateString() }}" 
                        {{ old('date_read') == today()->subDay()->toDateString() ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <label for="yesterday" class="ml-3 block text-sm font-medium text-gray-700">
                        📅 Yesterday ({{ today()->subDay()->format('M d, Y') }}) - <span class="text-gray-500 italic">I forgot to log it</span>
                    </label>
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-2">
                💡 <strong>Grace Period:</strong> You can log today's reading or catch up on yesterday if you forgot.
            </div>
        </div>

        <!-- Bible Book Selection -->
        <div class="space-y-2">
            <label for="book_id" class="block text-sm font-medium text-gray-700">Bible Book</label>
            <select id="book_id" name="book_id" required 
                class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('book_id') ? 'border-red-300' : '' }}">
                <option value="">Select a Bible book...</option>
                @foreach($books as $book)
                    <option value="{{ $book['id'] }}" {{ old('book_id') == $book['id'] ? 'selected' : '' }}>
                        {{ $book['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Chapter Input -->
        <div class="space-y-2">
            <label for="chapter_input" class="block text-sm font-medium text-gray-700">Chapter</label>
            <input type="number" id="chapter_input" name="chapter_input" min="1" required 
                value="{{ old('chapter_input') }}"
                placeholder="Enter chapter number (e.g., 3)"
                class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('chapter_input') ? 'border-red-300' : '' }}">
            <div class="text-xs text-gray-500">
                Enter a single chapter number. Chapter ranges will be added in a future update.
            </div>
        </div>

        <!-- Notes Section -->
        <div class="space-y-2">
            <label for="notes_text" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
            <textarea id="notes_text" name="notes_text" rows="4" maxlength="500" 
                placeholder="Share any thoughts, insights, or questions from your reading..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical {{ $errors->has('notes_text') ? 'border-red-300' : '' }}">{{ old('notes_text') }}</textarea>
            <div class="text-xs text-gray-500">
                Maximum 500 characters
            </div>
        </div>

        <!-- Form Actions -->
        <div class="pt-6 border-t border-gray-200 flex items-center space-x-4">
            <button type="submit" hx-indicator="#save-loading"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span id="save-loading" class="htmx-indicator hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
                <span class="htmx-indicator:hidden">Log Reading</span>
            </button>

            <button type="button" @click="modalOpen = false"
                class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </button>
        </div>
    </form>
</div>

<style>
    /* HTMX loading indicator styles */
    .htmx-indicator {
        display: none;
    }
    
    .htmx-request .htmx-indicator {
        display: inline-flex;
    }
    
    .htmx-request .htmx-indicator\:hidden {
        display: none;
    }
</style>
