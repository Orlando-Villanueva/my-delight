{{-- Dashboard Content Partial --}}
{{-- This partial is loaded via HTMX for seamless content loading --}}

@php
    $statisticsService = app(\App\Services\UserStatisticsService::class);
    $stats = $statisticsService->getDashboardStatistics(auth()->user());
@endphp

<div class="space-y-6 pb-20 lg:pb-0" 
     hx-trigger="readingLogAdded from:body" 
     hx-get="{{ route('dashboard') }}" 
     hx-target="#main-content" 
     hx-swap="innerHTML"
     hx-select="#main-content > div">
    <!-- Top Widgets: Streak and Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Streak Widget -->
    <x-ui.card>
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">🔥 Reading Streak</h3>
                
                <!-- Current Streak -->
                <div class="mb-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $stats['streaks']['current_streak'] }}</div>
                    <div class="text-gray-600">
                        @if($stats['streaks']['current_streak'] === 1)
                            Day
                        @else
                            Days
                        @endif
                    </div>
                </div>

                <!-- Longest Streak -->
                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <div class="text-sm text-gray-600">Personal Best</div>
                    <div class="text-xl font-semibold text-gray-700">
                        {{ $stats['streaks']['longest_streak'] }} {{ Str::plural('day', $stats['streaks']['longest_streak']) }}
                    </div>
                </div>

                <!-- Motivational Messaging -->
                <div class="text-sm">
                    @if($stats['streaks']['current_streak'] >= 7)
                        <p class="text-green-600 font-medium">🎉 Amazing! You're on fire with your {{ $stats['streaks']['current_streak'] }}-day streak!</p>
                    @elseif($stats['streaks']['current_streak'] >= 3)
                        <p class="text-blue-600 font-medium">💪 Great momentum! {{ $stats['streaks']['current_streak'] }} days in a row!</p>
                    @elseif($stats['streaks']['current_streak'] >= 1)
                        <p class="text-blue-600">🌟 You're building the habit! Keep it up!</p>
                    @else
                        <p class="text-gray-600">📖 Ready to start your Bible reading journey?</p>
                    @endif
            </div>
            </div>
        </x-ui.card>

        <!-- Stats Widget -->
        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Your Progress</h3>
            
            <div class="space-y-4">
                <!-- This Week -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">This Week</span>
                    <span class="font-semibold text-green-600">{{ $stats['reading_summary']['this_week_days'] }}/7 days reading</span>
                </div>

                <!-- This Month -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">This Month</span>
                    <span class="font-semibold text-blue-600">{{ $stats['reading_summary']['this_month_days'] }}/{{ now()->daysInMonth }} days reading</span>
                </div>

                <!-- All-Time Total -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Chapters</span>
                    <span class="font-semibold text-purple-600">{{ $stats['reading_summary']['total_readings'] }} chapters</span>
                </div>

                <!-- Bible Progress -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Bible Progress</span>
                    <span class="font-semibold text-orange-600">{{ $stats['book_progress']['overall_progress_percent'] }}% completed</span>
                </div>
            </div>
        </x-ui.card>
        </div>



    <!-- Book Progress Section (Work in Progress) -->
    <x-ui.card class="bg-yellow-50 border-yellow-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">📖 Book Progress Visualization</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p><strong>Work in Progress:</strong> Visual Bible book completion grid coming soon! You'll see all 66 books with completion status, progress percentages, and testament toggling.</p>
                    <div class="mt-3 p-3 bg-yellow-100 rounded">
                        <p class="font-medium mb-2">Preview of what's coming:</p>
                        <ul class="text-xs space-y-1">
                            <li>• Old Testament (39 books) / New Testament (27 books) toggle</li>
                            <li>• Book completion grid with visual progress indicators</li>
                            <li>• Color-coded status: ✅ Completed, 🔄 In Progress, ⭕ Not Started</li>
                            <li>• Overall testament progress percentages</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>


</div> 