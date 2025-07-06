{{-- Dashboard Page Container Partial --}}
{{-- This partial is loaded via HTMX navigation and includes both content and sidebar structure --}}

@include('partials.header-update', [
    'title' => 'Dashboard',
    'subtitle' => 'Welcome back, ' . auth()->user()->name
])

<!-- Main Content (70% on desktop when sidebar present) -->
<div class="lg:flex-1 lg:max-w-[70%] p-4 lg:p-6">
    <div id="main-content" class="h-full">
        @include('partials.dashboard-content')
    </div>
</div>

<!-- Sidebar Content (30% on desktop) -->
<div class="hidden lg:block lg:w-[30%] lg:min-w-[300px] bg-white border-l border-gray-200 p-6">
    @include('partials.dashboard-sidebar')
</div> 