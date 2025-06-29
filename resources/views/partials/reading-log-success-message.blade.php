<div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;" class="pb-20 lg:pb-0">
    <h3>Reading Logged Successfully! 🎉</h3>
    <p><strong>{{ $log->passage_text }}</strong> recorded for {{ $log->date_read->format('M d, Y') }}</p>
    @if($log->notes_text)
        <p><em>Notes: {{ $log->notes_text }}</em></p>
    @endif
    
    @php
        // Check if this was a multi-chapter reading by looking for range in passage_text
        $isRange = strpos($log->passage_text, '-') !== false;
    @endphp
    
    @if($isRange)
        <p class="text-sm text-gray-600 mt-2">
            💡 <em>Multiple chapters logged as separate entries for detailed progress tracking</em>
        </p>
    @endif
    
    <div style="margin-top: 10px;">
        <button hx-get="{{ route('dashboard') }}" 
                hx-target="#main-content" 
                hx-swap="innerHTML"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Return to Dashboard
        </button>
        | 
        <button hx-get="{{ route('logs.create') }}" 
                hx-target="#main-content" 
                hx-swap="innerHTML"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Log Another Reading
        </button>
    </div>
</div>

{{-- Pure HTMX approach - no complex JavaScript needed --}} 