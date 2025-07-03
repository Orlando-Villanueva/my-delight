{{-- Logs Page Container Partial (No Sidebar) --}}
{{-- This partial is loaded via HTMX navigation and includes the page-container structure --}}

@include('partials.header-update', [
    'title' => 'Reading History',
    'subtitle' => 'View your Bible reading journey'
])

<!-- Full-width Content when no sidebar is defined -->
<div class="flex-1 p-4 lg:p-6">
    <div id="main-content" class="h-full">
        @include('partials.logs-content', compact('logs', 'filter'))
    </div>
</div> 