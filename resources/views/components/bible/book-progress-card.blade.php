@props([
    'book',
    'compact' => false,
    'interactive' => true,
    'showChapterCount' => true,
    'clickable' => true
])

@php
    // Calculate progress values with explicit type casting
    $progressPercentage = (float) ($book['progress_percentage'] ?? 0);
    $chaptersRead = (int) ($book['chapters_read'] ?? 0);
    $totalChapters = (int) ($book['total_chapters'] ?? $book['chapters'] ?? 0);
    $isCompleted = $progressPercentage >= 100;
    $isStarted = $chaptersRead > 0;
    
    // Determine card styling based on progress
    if ($isCompleted) {
        $statusClasses = 'bg-success-50 border-success-200 hover:border-success-300';
        $progressColor = 'success';
        $iconColor = 'text-success-600';
    } elseif ($isStarted) {
        $statusClasses = 'bg-primary-50 border-primary-200 hover:border-primary-300';
        $progressColor = 'primary';
        $iconColor = 'text-primary-600';
    } else {
        $statusClasses = 'bg-white border-gray-200 hover:border-gray-300';
        $progressColor = 'gray';
        $iconColor = 'text-gray-400';
    }
    
    // Size variations
    $cardClasses = $compact 
        ? 'p-2 text-xs' 
        : 'p-3 text-sm';
        
    $iconSize = $compact ? 'w-3 h-3' : 'w-4 h-4';
    $titleSize = $compact ? 'text-xs' : 'text-sm';
@endphp

<div {{ $attributes->merge(['class' => "relative rounded-lg border transition-all duration-200 $statusClasses $cardClasses"]) }}
     @if($interactive && $clickable) 
         x-data="{ showTooltip: false }" 
         @mouseenter="showTooltip = true" 
         @mouseleave="showTooltip = false"
         role="button"
         tabindex="0"
         @keydown.enter="$dispatch('book-selected', { bookId: {{ $book['book_id'] ?? $book['id'] ?? 0 }}, bookName: '{{ $book['name'] }}' })"
         @click="$dispatch('book-selected', { bookId: {{ $book['book_id'] ?? $book['id'] ?? 0 }}, bookName: '{{ $book['name'] }}' })"
     @endif>
     
    {{-- Main Book Info --}}
    <div class="flex flex-col space-y-2">
        {{-- Book Title & Icon --}}
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-1 mb-1">
                    <svg class="{{ $iconSize }} {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    @if($isCompleted)
                        <svg class="{{ $iconSize }} text-success-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </div>
                
                <h3 class="{{ $titleSize }} font-semibold text-gray-900 truncate" title="{{ $book['name'] }}">
                    {{ $book['name'] }}
                </h3>
                
                @if($showChapterCount)
                    <div class="text-xs text-gray-500 mt-1">
                        {{ (int) $chaptersRead }}/{{ (int) $totalChapters }} chapters
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Progress Bar --}}
        <div class="space-y-1">
            <x-ui.progress-bar 
                :progress="$progressPercentage"
                :color="$progressColor"
                size="sm"
                :showLabel="false" />
                
            @if(!$compact)
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-500">{{ number_format($progressPercentage, 1) }}%</span>
                    @if($isCompleted)
                        <span class="text-success-600 font-medium">✓ Complete</span>
                    @elseif($isStarted)
                        <span class="text-primary-600 font-medium">In Progress</span>
                    @else
                        <span class="text-gray-400">Not Started</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    {{-- Interactive Tooltip (if enabled) --}}
    @if($interactive && $clickable)
        <div x-show="showTooltip" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 transform scale-100" 
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max">
             
            <div class="bg-gray-900 text-white text-xs rounded-lg py-2 px-3 shadow-lg">
                <div class="font-semibold">{{ $book['name'] }}</div>
                <div class="text-gray-300">{{ (int) $chaptersRead }}/{{ (int) $totalChapters }} chapters read</div>
                @if($book['last_read_date'] ?? false)
                    <div class="text-gray-400 text-xs mt-1">
                        Last read: {{ \Carbon\Carbon::parse($book['last_read_date'])->format('M j, Y') }}
                    </div>
                @endif
                <div class="text-xs text-gray-400 mt-1">Click to view details</div>
                
                {{-- Tooltip Arrow --}}
                <div class="absolute top-full left-1/2 transform -translate-x-1/2">
                    <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                </div>
            </div>
        </div>
    @endif
    
    {{-- Accessibility Label --}}
    @if($interactive && $clickable)
        <span class="sr-only">
            {{ $book['name'] }}: {{ (int) $chaptersRead }} of {{ (int) $totalChapters }} chapters read ({{ number_format($progressPercentage, 1) }}% complete)
        </span>
    @endif
</div> 