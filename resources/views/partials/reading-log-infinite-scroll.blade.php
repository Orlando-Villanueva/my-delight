{{-- Reading Log Items Partial - Used for infinite scroll --}}
{{-- This partial renders individual log items and the intersection observer sentinel --}}

<div class="space-y-4{{ request()->has('page') && request()->get('page') > 1 ? ' mt-4' : '' }}">
    @foreach ($logs as $logsForDay)
        @if ($logsForDay->count() === 1)
            {{-- Single reading: use individual card --}}
            <x-bible.reading-log-card :log="$logsForDay->first()" />
        @else
            {{-- Multiple readings: use daily grouped card --}}
            <x-bible.daily-reading-card :logsForDay="$logsForDay" />
        @endif
    @endforeach
</div>

{{-- Intersection Observer Sentinel for Infinite Scroll (Universal) --}}
@if ($logs->hasMorePages())
    <div hx-get="{{ $logs->nextPageUrl() }}" hx-trigger="intersect once" hx-swap="outerHTML"
        hx-indicator=".htmx-indicator" class="absolute inset-x-0 bottom-0 h-1 flex justify-center z-0">
        {{-- Loading indicator that shows during fetch --}}
        <div class="htmx-indicator flex items-center space-x-2 text-gray-500 translate-y-full py-2">
            <div class="animate-spin h-5 w-5 border-2 border-primary-600 border-t-transparent rounded-full"></div>
            <span class="text-sm">Loading more readings...</span>
        </div>
    </div>
@endif
