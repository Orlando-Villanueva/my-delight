{{-- Dashboard Page Container Partial --}}
{{-- This partial is loaded via HTMX navigation and follows the ui-prototype layout --}}

<title>Dashboard - {{ config('app.name') }}</title>

@include('partials.header-update', [
    'title' => 'Dashboard',
    'subtitle' => 'Track your Bible reading progress'
])

<!-- Main Dashboard Content Area (Full Width) -->
<div class="w-full p-4 lg:p-6">
    <div id="main-content" class="h-full">
        @include('partials.dashboard-content', compact('hasReadToday', 'streakState', 'streakStateClasses', 'streakMessage', 'stats', 'weeklyGoal'))
    </div>
</div> 