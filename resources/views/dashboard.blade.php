@extends('layouts.authenticated')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Track your Bible reading progress')

@section('content')
    <!-- Main Dashboard Content Area (Full Width) -->
    <div id="main-content" class="h-full">
        @include(
            'partials.dashboard-content',
            compact('hasReadToday', 'streakState', 'streakStateClasses', 'streakMessage', 'stats', 'weeklyGoal'))
    </div>
@endsection
