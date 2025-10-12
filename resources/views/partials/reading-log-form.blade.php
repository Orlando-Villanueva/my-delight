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

    <!-- Bible Book Selection -->
    <x-ui.select 
        name="book_id" 
        label="📚 Bible Book" 
        placeholder="Select a Bible book..."
        required
        :error="$errors->first('book_id')"
        class="max-w-md"
    >
        {{-- Old Testament Group --}}
        @php
            $oldTestament = collect($books)->where('testament', 'old')->values();
            $newTestament = collect($books)->where('testament', 'new')->values();
        @endphp
        
        @if($oldTestament->isNotEmpty())
            <optgroup label="📜 Old Testament ({{ $oldTestament->count() }} books)">
                @foreach($oldTestament as $book)
                    <option value="{{ $book['id'] }}" {{ old('book_id') == $book['id'] ? 'selected' : '' }}>
                        {{ $book['name'] }} ({{ $book['chapters'] }} chapters)
                    </option>
                @endforeach
            </optgroup>
        @endif
        
        {{-- New Testament Group --}}
        @if($newTestament->isNotEmpty())
            <optgroup label="✝️ New Testament ({{ $newTestament->count() }} books)">
                @foreach($newTestament as $book)
                    <option value="{{ $book['id'] }}" {{ old('book_id') == $book['id'] ? 'selected' : '' }}>
                        {{ $book['name'] }} ({{ $book['chapters'] }} chapters)
                    </option>
                @endforeach
            </optgroup>
        @endif
        
        {{-- Fallback: All books without grouping if testament data not available --}}
        @if($oldTestament->isEmpty() && $newTestament->isEmpty())
            @foreach($books as $book)
                <option value="{{ $book['id'] }}" {{ old('book_id') == $book['id'] ? 'selected' : '' }}>
                    {{ $book['name'] }} ({{ $book['chapters'] }} chapters)
                </option>
            @endforeach
        @endif
    </x-ui.select>

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
</div>