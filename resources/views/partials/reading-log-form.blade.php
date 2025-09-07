{{-- Unified Reading Log Form Component --}}
{{-- This partial contains just the form logic and can be included in different layouts --}}

<div class="px-2 sm:px-20 lg:px-32">
{{-- Success Message --}}
@if(session('success'))
    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-sm text-green-700 dark:text-green-300">
                    <strong>Reading logged successfully!</strong> {{ session('success') }}
                </p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

<form hx-post="{{ route('logs.store') }}" hx-target="#main-content" hx-swap="innerHTML" class="space-y-6">
    @csrf

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
            <div class="flex">
                <div class="text-red-400 dark:text-red-500">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400">Please fix the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
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
    <div class="pt-6 border-t border-gray-200 dark:border-gray-600 flex items-center">
        <x-ui.button 
            type="submit" 
            variant="accent" 
            size="lg"
            hx-indicator="#save-loading"
            class="px-6 py-3 text-base font-medium shadow-sm"
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