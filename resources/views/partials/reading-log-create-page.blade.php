{{-- Reading Log Create Page Container Partial --}}
{{-- This partial is loaded via HTMX navigation and includes the page-container structure --}}

@include('partials.header-update', [
    'title' => 'Log Reading',
    'subtitle' => 'Record your Bible reading progress'
])

<!-- Full-width Content when no sidebar is defined -->
<div class="flex-1 p-4 xl:p-6 pb-5 md:pb-20 lg:pb-6">
    <div id="main-content" class="h-full">
        @include('partials.reading-log-form', compact('books', 'errors', 'allowYesterday', 'hasReadYesterday', 'currentStreak', 'hasReadToday'))
    </div>
</div>