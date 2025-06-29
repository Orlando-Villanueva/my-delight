{{-- Reading Log Items Partial - Used for infinite scroll --}}
{{-- This partial renders individual log items and the intersection observer sentinel --}}

@foreach($logs as $log)
    @include('partials.reading-log-card', ['log' => $log])
@endforeach

{{-- Intersection Observer Sentinel for Infinite Scroll --}}
@if ($logs->hasMorePages())
    <div
        hx-get="{{ $logs->nextPageUrl() }}&filter={{ $filter }}"
        hx-trigger="intersect once"
        hx-swap="outerHTML"
        hx-indicator=".htmx-indicator"
        class="absolute inset-x-0 bottom-0 h-1 md:hidden flex justify-center">
        {{-- Loading indicator that shows during fetch --}}
        <div class="htmx-indicator flex items-center space-x-2 text-gray-500 translate-y-full py-2">
            <div class="animate-spin h-5 w-5 border-2 border-blue-600 border-t-transparent rounded-full"></div>
            <span class="text-sm">Loading more readings...</span>
        </div>
    </div>
@endif

{{-- Desktop Pagination (hidden on mobile) --}}
@if ($logs->hasPages())
    <div class="hidden md:block mt-8 pb-20 lg:pb-0">
        {{ $logs->links() }}
    </div>
@endif