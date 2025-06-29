{{-- Reading Log Content --}}
{{-- This partial is loaded via HTMX to update only the log content --}}

@include('partials.reading-log-list', compact('logs', 'filter')) 