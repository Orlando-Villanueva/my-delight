@extends('layouts.authenticated')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')
    <div id="main-content" class="h-full">
        @include('partials.dashboard-content')
    </div>
@endsection

@section('sidebar')
    <div class="space-y-6">

        <!-- User Profile Card -->
        <x-ui.card class="bg-gray-50">
            <h4 class="font-semibold text-gray-700 mb-3">Your Profile</h4>
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white text-lg font-medium">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-700">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="text-xs text-gray-500">
                Member since {{ auth()->user()->created_at->format('F j, Y') }}
            </div>
        </x-ui.card>

        <!-- Quick Actions -->
        <x-ui.card class="bg-gray-50">
            <h4 class="font-semibold text-gray-700 mb-3">Quick Actions</h4>
            <div class="space-y-2">
                <a href="{{ route('profile') }}" class="block w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-white rounded border border-transparent hover:border-gray-200">
                    Edit Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-white rounded border border-transparent hover:border-gray-200">
                        Sign Out
                    </button>
                </form>
            </div>
        </x-ui.card>

        <!-- Development Timeline -->
        <x-ui.card class="bg-gray-50">
            <h4 class="font-semibold text-gray-700 mb-3">Development Progress</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Week 1-2: Foundation</span>
                    <span class="text-green-600 font-medium">✅ Complete</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Week 3: Reading Log</span>
                    <span class="text-yellow-600 font-medium">🔄 In Progress</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Week 4: Streaks</span>
                    <span class="text-gray-400">⏳ Planned</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Week 5: Visualization</span>
                    <span class="text-gray-400">⏳ Planned</span>
                </div>
            </div>
        </x-ui.card>
    </div>
@endsection 