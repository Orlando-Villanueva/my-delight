<div class="text-center py-8"
     x-data="{ countdown: 3 }"
     x-init="
        $dispatch('readingLogAdded');
        const timer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timer);
                modalOpen = false;
            }
        }, 1000);
        // Clear interval when component is destroyed (e.g., 'Log Another Reading' opens a new form)
        return () => clearInterval(timer);
     ">
    <!-- Success Icon -->
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    
    <!-- Success Message -->
    <h3 class="text-xl font-semibold text-gray-900 mb-2">Reading Logged Successfully! 🎉</h3>
    <p class="text-gray-600 mb-2">
        <strong>{{ $log->passage_text }}</strong> recorded for {{ $log->date_read->format('M d, Y') }}
    </p>
    
    @if($log->notes_text)
        <p class="text-sm text-gray-500 italic mb-4">
            Notes: {{ Str::limit($log->notes_text, 60) }}
        </p>
    @endif
    
    @php
        // Check if this was a multi-chapter reading by looking for range in passage_text
        $isRange = strpos($log->passage_text, '-') !== false;
    @endphp
    
    @if($isRange)
        <p class="text-sm text-gray-500 mb-4">
            💡 <em>Multiple chapters logged as separate entries for detailed progress tracking</em>
        </p>
    @endif
    
    <!-- Auto-close Countdown -->
    <p class="text-sm text-gray-500 mb-4">
        Modal will close in <span x-text="countdown" class="font-medium text-blue-600"></span> seconds...
    </p>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-center space-x-4">
        <button type="button"
                @click="modalOpen = false"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Close
        </button>
        
        <button hx-get="{{ route('logs.create') }}" 
                hx-target="#reading-log-modal-content" 
                hx-swap="innerHTML"
                hx-indicator="#modal-loading"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Log Another Reading
        </button>
    </div>
</div>

{{-- Pure HTMX approach - no complex JavaScript needed --}} 