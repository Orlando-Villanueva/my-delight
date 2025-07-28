@props([
    'statistics' => [],
    'compact' => false,
    'showGoals' => true,
    'showComparisons' => true,
    'layout' => 'grid' // grid, list, cards
])

@php
    // Extract key statistics with defaults
    $stats = collect($statistics);
    $currentStreak = $stats->get('current_streak', 0);
    $longestStreak = $stats->get('longest_streak', 0);
    $totalReadings = $stats->get('total_readings', 0);
    $totalChapters = $stats->get('total_chapters_read', 0);
    $booksCompleted = $stats->get('books_completed', 0);
    $daysActive = $stats->get('days_active', 0);
    $averagePerDay = $stats->get('average_per_day', 0);
    $thisWeek = $stats->get('readings_this_week', 0);
    $thisMonth = $stats->get('readings_this_month', 0);
    $yearProgress = $stats->get('year_progress_percentage', 0);
    
    // Layout classes
    $containerClasses = match($layout) {
        'list' => 'space-y-3',
        'cards' => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4',
        default => 'grid grid-cols-2 md:grid-cols-4 gap-4'
    };
    
    // Size adjustments
    if ($compact) {
        $containerClasses = str_replace('gap-4', 'gap-2', $containerClasses);
    }
@endphp

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    {{-- Header --}}
    @if(!$compact)
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Reading Statistics</span>
            </h3>
            
            <div class="text-sm text-gray-500">
                Updated {{ now()->format('M j, Y') }}
            </div>
        </div>
    @endif
    
    {{-- Primary Statistics Grid --}}
    <div class="{{ $containerClasses }}">
        {{-- Current Streak --}}
        <x-bible.stat-card
            title="Current Streak"
            :value="$currentStreak"
            unit="days"
            icon="fire"
            :color="$currentStreak > 0 ? 'orange' : 'gray'"
            :compact="$compact">
            @if($currentStreak > 0)
                <x-slot:subtitle>Keep it going! 🔥</x-slot:subtitle>
            @else
                <x-slot:subtitle>Start a new streak</x-slot:subtitle>
            @endif
        </x-bible.stat-card>
        
        {{-- Total Readings --}}
        <x-bible.stat-card
            title="Total Readings"
            :value="$totalReadings"
            unit="entries"
            icon="book"
            color="blue"
            :compact="$compact">
            <x-slot:subtitle>{{ $totalChapters }} chapters total</x-slot:subtitle>
        </x-bible.stat-card>
        
        {{-- Books Completed --}}
        <x-bible.stat-card
            title="Books Completed"
            :value="$booksCompleted"
            unit="of 66"
            icon="check-circle"
            color="success"
            :compact="$compact">
            <x-slot:subtitle>{{ round(($booksCompleted / 66) * 100, 1) }}% of Bible</x-slot:subtitle>
        </x-bible.stat-card>
        
        {{-- Consistency --}}
        <x-bible.stat-card
            title="Consistency"
            :value="round(($daysActive / max(1, now()->diffInDays(now()->subMonth()))) * 100)"
            unit="%"
            icon="calendar"
            color="purple"
            :compact="$compact">
            <x-slot:subtitle>{{ $daysActive }} active days</x-slot:subtitle>
        </x-bible.stat-card>
    </div>
    
    {{-- Secondary Statistics (if not compact) --}}
    @if(!$compact)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Recent Activity --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Recent Activity</span>
                </h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">This Week</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $thisWeek }} readings</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">This Month</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $thisMonth }} readings</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Daily Average</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($averagePerDay, 1) }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Streak Information --}}
            <div class="bg-accent-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-accent-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Streak Progress</span>
                </h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Current Streak</span>
                        <span class="text-lg font-bold text-accent-600">{{ $currentStreak }} days</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Longest Streak</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $longestStreak }} days</span>
                    </div>
                    
                    @if($currentStreak > 0)
                        <div class="mt-2 text-xs text-gray-600">
                            @if($currentStreak >= $longestStreak)
                                🎉 New personal record!
                            @elseif($currentStreak >= 7)
                                🔥 Great consistency!
                            @elseif($currentStreak >= 3)
                                💪 Building momentum!
                            @else
                                📈 Keep going!
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Year Progress --}}
            <div class="bg-primary-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>{{ now()->year }} Progress</span>
                </h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Year Complete</span>
                        <span class="text-sm font-semibold text-gray-900">{{ round((now()->dayOfYear / (now()->isLeapYear() ? 366 : 365)) * 100, 1) }}%</span>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Reading Goal</span>
                            <span class="text-lg font-bold text-primary-600">{{ $yearProgress }}%</span>
                        </div>
                        <x-ui.progress-bar 
                            :progress="$yearProgress"
                            color="primary"
                            size="sm" />
                    </div>
                    
                    @if($showGoals && $yearProgress < 100)
                        @php
                            $daysRemaining = now()->endOfYear()->diffInDays(now());
                            $readingsNeeded = max(0, (365 - $totalReadings));
                            $dailyGoal = $daysRemaining > 0 ? ceil($readingsNeeded / $daysRemaining) : 0;
                        @endphp
                        
                        @if($dailyGoal > 0)
                            <div class="text-xs text-gray-600">
                                💡 Read {{ $dailyGoal }} chapter{{ $dailyGoal !== 1 ? 's' : '' }} daily to reach year goal
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    {{-- Motivational Message --}}
    @if(!$compact)
        <div class="bg-gradient-to-r from-primary-50 to-success-50 rounded-lg p-4 border border-primary-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 mb-1">
                        @if($currentStreak >= 30)
                            Amazing dedication! 🏆
                        @elseif($currentStreak >= 14)
                            Fantastic consistency! 🌟
                        @elseif($currentStreak >= 7)
                            Great weekly habit! 📖
                        @elseif($currentStreak > 0)
                            Building momentum! 💪
                        @else
                            Ready to start your journey? ✨
                        @endif
                    </h4>
                    <p class="text-sm text-gray-600">
                        @if($booksCompleted >= 33)
                            You've read over half the Bible! Consider diving deeper into study.
                        @elseif($booksCompleted >= 10)
                            You're making excellent progress through Scripture!
                        @elseif($totalReadings >= 30)
                            Your consistent reading is building a strong foundation.
                        @elseif($totalReadings >= 7)
                            Great start! Consider setting a daily reading goal.
                        @else
                            Every journey begins with a single step. Start reading today!
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif
</div> 