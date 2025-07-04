{{-- Reading Log List Partial --}}
{{-- This partial is loaded via HTMX for seamless filtering --}}

@if($logs->count() > 0)
    {{-- Reading Log Entries Container --}}
    <div id="log-list" class="relative flex flex-col gap-y-4">
        {{-- Include the infinite scroll partial which handles both items and scroll sentinel --}}
        @include('partials.reading-log-infinite-scroll', compact('logs', 'filter'))
    </div>
@else
    {{-- Empty State --}}
    <div class="text-center py-12 pb-20 lg:pb-12">
        <div class="w-16 h-16 mx-auto mb-4 text-gray-400">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        
        <h3 class="text-lg font-medium text-gray-900 mb-2">No reading logs found</h3>
        
        @if($filter === 'all')
            <p class="text-gray-600 mb-6">You haven't logged any Bible readings yet. Start building your reading habit!</p>
            <button hx-get="{{ route('logs.create') }}" 
                    hx-target="#reading-log-modal-content" 
                    hx-swap="innerHTML"
                    hx-indicator="#modal-loading"
                    @click="modalOpen = true"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                📖 Log Your First Reading
            </button>
        @else
            @php
                $filterText = match($filter) {
                    '7' => 'the last 7 days',
                    '30' => 'the last 30 days', 
                    '90' => 'the last 90 days',
                    default => 'this time period'
                };
            @endphp
            <p class="text-gray-600 mb-6">No readings logged in {{ $filterText }}. Try expanding your date range or log a new reading.</p>
            <div class="space-x-4">
                <button hx-get="{{ route('logs.index', ['filter' => 'all']) }}" 
                        hx-target="#reading-content" 
                        hx-swap="innerHTML"
                        hx-indicator="#loading"
                        onclick="updateActiveFilter(this, 'all')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    View All Readings
                </button>
                <button hx-get="{{ route('logs.create') }}" 
                        hx-target="#reading-log-modal-content" 
                        hx-swap="innerHTML"
                        hx-indicator="#modal-loading"
                        @click="modalOpen = true"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    📖 Log Reading
                </button>
            </div>
        @endif
    </div>
@endif 