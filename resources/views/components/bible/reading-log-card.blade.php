@props([
    'log',
    'showDate' => true,
    'showNotes' => true,
    'compact' => false,
    'contributedToStreak' => false
])

@php
    $cardClasses = 'bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200';
    if ($compact) {
        $cardClasses .= ' p-3';
    } else {
        $cardClasses .= ' p-4';
    }
@endphp

<div {{ $attributes->merge(['class' => $cardClasses]) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            {{-- Date & Reference --}}
            <div class="flex items-center space-x-3 mb-2">
                @if($showDate)
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $log->date_read->format('M j, Y') }}
                        </span>
                    </div>
                @endif
                
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-sm text-primary-600 dark:text-primary-400 font-semibold">
                        {{ $log->passage_text }}
                    </span>
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
            
            {{-- Time Since Reading (for recent entries) --}}
            @if(!$compact && $log->date_read->isToday())
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Today</span>
                </div>
            @elseif(!$compact && $log->date_read->isYesterday())
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Yesterday</span>
                </div>
            @elseif(!$compact && $log->date_read->diffInDays(now()) <= 7)
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $log->date_read->diffForHumans() }}</span>
                </div>
            @endif
        </div>
        
        {{-- Right Side Indicators --}}
        <div class="ml-4 flex flex-col items-end space-y-2">
            {{-- Streak Contribution Indicator --}}
            @if($contributedToStreak)
                <div class="flex items-center space-x-1 text-xs text-success-600 bg-success-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Streak</span>
                </div>
            @endif
            
            {{-- Has Notes Indicator --}}
            @if($log->notes_text && !$showNotes)
                <div class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <span>Notes</span>
                </div>
            @endif
        </div>
    </div>
</div> 