{{-- Dashboard Content Partial --}}
{{-- This partial follows the ui-prototype layout with 4-column grid structure --}}

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

    <!-- Main Dashboard Layout (4-column grid matching ui-prototype) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Left Column - Main Content (3/4 width on desktop) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Top Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <!-- Streak Card - More Prominent (2/5 width) -->
                <div class="md:col-span-2">
                    <x-ui.streak-counter 
                        :currentStreak="$stats['streaks']['current_streak']"
                        :longestStreak="$stats['streaks']['longest_streak']"
                        class="h-full"
                    />
                </div>

                <!-- Stats Panel - Compact (3/5 width) -->
                <div class="md:col-span-3">
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

            <!-- Mobile Calendar - Shows only on mobile, above Book Progress -->
            <div class="lg:hidden">
                <x-bible.calendar-heatmap 
                    :user="auth()->user()"
                    :months="1"
                    :showLegend="false"
                />
            </div>

            <!-- Book Progress Visualization -->
            <x-bible.book-completion-grid 
                testament="Old"
            />
        </div>

        <!-- Right Column - Desktop Sidebar (1/4 width on desktop) -->
        <div class="hidden lg:block lg:col-span-1 space-y-6"
             id="dashboard-sidebar"
             hx-trigger="readingLogAdded from:body" 
             hx-get="{{ route('dashboard') }}" 
             hx-target="this" 
             hx-swap="outerHTML"
             hx-select="#dashboard-sidebar">

            <!-- Reading Calendar -->
            <x-bible.calendar-heatmap 
                :user="auth()->user()"
                :months="1"
                :showLegend="false"
            />

            <!-- Recent Readings -->
            @if(!empty($stats['recent_activity']))
                <x-ui.card class="bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 transition-colors">
                    <div class="p-4">
                        <h3 class="font-semibold text-[#4A5568] dark:text-gray-200 mb-3">Recent Readings</h3>
                        <div class="space-y-2">
                            @foreach(array_slice($stats['recent_activity'], 0, 5) as $reading)
                                <div class="text-sm">
                                    <div class="font-medium text-[#4A5568] dark:text-gray-200">
                                        {{ $reading['passage_text'] }}
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $reading['time_ago'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>

</div> 