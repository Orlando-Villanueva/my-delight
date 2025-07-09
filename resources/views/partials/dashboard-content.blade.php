{{-- Dashboard Content Partial --}}
{{-- This partial is loaded via HTMX for seamless content loading --}}

@php
    $statisticsService = app(\App\Services\UserStatisticsService::class);
    $stats = $statisticsService->getDashboardStatistics(auth()->user());
@endphp

<div class="space-y-6 pb-20 lg:pb-0" 
     id="dashboard-main-content-wrapper"
     hx-trigger="readingLogAdded from:body" 
     hx-get="{{ route('dashboard') }}" 
     hx-target="#main-content" 
     hx-swap="outerHTML"
     hx-select="#main-content">
    <!-- Top Widgets: Streak and Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Enhanced Streak Card -->
        <div class="lg:col-span-2">
            <x-ui.streak-counter 
                :currentStreak="$stats['streaks']['current_streak']"
                :longestStreak="$stats['streaks']['longest_streak']"
                class="h-full"
            />
        </div>

        <!-- Enhanced Summary Stats Card -->
        <div class="lg:col-span-3">
            <x-ui.summary-stats 
                :thisWeekDays="$stats['reading_summary']['this_week_days']"
                :thisMonthDays="$stats['reading_summary']['this_month_days']"
                :daysInMonth="now()->daysInMonth"
                :totalChapters="$stats['reading_summary']['total_readings']"
                :bibleProgress="$stats['book_progress']['overall_progress_percent']"
                class="h-full"
            />
        </div>
    </div>

    <!-- Motivational Messaging Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-lg p-6 text-center border border-[#D1D7E0] dark:border-gray-600">
        @if($stats['streaks']['current_streak'] >= 7)
            <p class="text-lg text-green-600 dark:text-green-400 font-medium">🎉 Amazing! You're on fire with your {{ $stats['streaks']['current_streak'] }}-day streak!</p>
        @elseif($stats['streaks']['current_streak'] >= 3)
            <p class="text-lg text-[#3366CC] dark:text-blue-400 font-medium">💪 Great momentum! {{ $stats['streaks']['current_streak'] }} days in a row!</p>
        @elseif($stats['streaks']['current_streak'] >= 1)
            <p class="text-lg text-[#3366CC] dark:text-blue-400">🌟 You're building the habit! Keep it up!</p>
        @else
            <p class="text-lg text-[#4A5568] dark:text-gray-300">📖 Ready to start your Bible reading journey?</p>
        @endif
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