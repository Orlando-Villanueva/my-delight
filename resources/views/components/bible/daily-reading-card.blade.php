@props([
    'logsForDay', // Collection of logs for this day
    'showNotes' => true,
    'compact' => false,
    'contributedToStreak' => false
])

@php
    $firstLog = $logsForDay->first();
    $date = $firstLog->date_read;
    $cardClasses = 'bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200';
    if ($compact) {
        $cardClasses .= ' p-3';
    } else {
        $cardClasses .= ' p-4';
    }
@endphp

<div {{ $attributes->merge(['class' => $cardClasses]) }}>
    {{-- Subtle Date Header --}}
    <div class="flex items-center justify-between mb-3">
        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
            @php
                // Use time_ago from the most recent log (first in the sorted collection)
                // Since logs are sorted by created_at desc, first() gives us the most recent activity
                $mostRecentLog = $logsForDay->first();
                $timeAgo = $mostRecentLog->time_ago ?? $date->diffForHumans();
            @endphp
            {{ $date->format('M j, Y') }} • {{ $timeAgo }}
        </div>
        <div class="text-xs text-gray-400 dark:text-gray-500">
            {{ $logsForDay->count() }} {{ Str::plural('reading', $logsForDay->count()) }}
        </div>
    </div>

    {{-- Reading logs for this day --}}
    <div class="space-y-3">
        @foreach ($logsForDay as $index => $log)
            {{-- Subtle separator between readings (not before first) --}}
            @if ($index > 0)
                <div class="border-t border-gray-50 dark:border-gray-700 pt-3"></div>
            @endif

            <div class="reading-entry">
                {{-- Bible Reading Content - Make it prominent like single cards --}}
                <div class="mb-3">
                    <div class="flex items-center space-x-2 mb-1">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                            {{ $log->passage_text }}
                        </h3>
                    </div>
                </div>

                {{-- Notes Section --}}
                @if($showNotes && $log->notes_text)
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400" x-data="{ expanded: false, needsExpansion: false }" x-init="needsExpansion = $el.querySelector('.notes-content').scrollHeight > 60">
                        <div class="notes-content overflow-hidden transition-all duration-200" :class="expanded ? 'max-h-none' : 'max-h-15'">
                            <div class="whitespace-pre-wrap leading-relaxed">{{ $log->notes_text }}</div>
                        </div>
                        
                        {{-- Expand/Collapse Button --}}
                        <button x-show="needsExpansion" @click="expanded = !expanded" 
                                class="text-primary-600 hover:text-primary-800 text-xs mt-2 font-medium flex items-center space-x-1 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 rounded">
                            <span x-show="!expanded">Show more</span>
                            <span x-show="expanded">Show less</span>
                            <svg x-show="!expanded" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <svg x-show="expanded" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>


</div> 