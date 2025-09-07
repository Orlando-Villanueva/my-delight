{{-- Infinite Scroll Sentinel Partial --}}
{{-- This partial renders the intersection observer element for infinite scroll --}}
<div id="infinite-scroll-sentinel" 
    hx-get="{{ $logs->nextPageUrl() }}" 
    hx-trigger="intersect once" 
    hx-target="#log-list-content"
    hx-swap="beforeend"
    hx-indicator=".htmx-indicator" 
    class="absolute inset-x-0 h-0 flex justify-center items-center pointer-events-none">
    {{-- Loading indicator that shows during fetch --}}
    <div class="htmx-indicator flex items-center space-x-2 text-gray-500">
        <div class="animate-spin h-5 w-5 border-2 border-primary-600 border-t-transparent rounded-full"></div>
        <span class="text-sm">Loading more readings...</span>
    </div>
</div>