@props([
    'currentStreak' => 0,
    'longestStreak' => 0,
    'stateClasses' => [],
    'size' => 'default'
])

@php
    $sizeClasses = [
        'small' => 'p-4 lg:p-3 xl:p-4',
        'default' => 'p-6 lg:p-4 xl:p-6',
        'large' => 'p-8 lg:p-6 xl:p-8'
    ];
    
    $numberSizes = [
        'small' => 'text-2xl lg:text-3xl',
        'default' => 'text-4xl lg:text-5xl',
        'large' => 'text-5xl lg:text-6xl'
    ];
    
    $iconSizes = [
        'small' => 'w-5 h-5',
        'default' => 'w-6 h-6',
        'large' => 'w-8 h-8'
    ];
@endphp

<div {{ $attributes->merge(['class' => $stateClasses['background'] . ($stateClasses['showIcon'] ? ' border-0' : '') . ' h-full rounded-lg ' . ($sizeClasses[$size] ?? $sizeClasses['default'])]) }}>
    <div class="flex flex-col justify-center h-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg lg:text-xl font-semibold leading-[1.5]">Current Streak</h3>
            @if($stateClasses['showIcon'])
                <svg class="{{ $iconSizes[$size] ?? $iconSizes['default'] }} {{ $stateClasses['icon'] }}" fill="currentColor" viewBox="0 0 384 512">
                    <path d="M216 23.86c0-23.8-30.65-32.77-44.15-13.04C48 191.85 224 200 224 288c0 35.63-29.11 64.46-64.85 63.99-35.17-.45-63.15-29.77-63.15-64.94v-85.51c0-21.7-26.47-32.4-41.6-16.9C21.22 216.4 0 268.2 0 320c0 105.87 86.13 192 192 192s192-86.13 192-192c0-170.29-168-193.17-168-296.14z"/>
                </svg>
            @endif
        </div>
        
        <div class="text-center flex-1 flex flex-col justify-center">
            <div class="{{ $numberSizes[$size] ?? $numberSizes['default'] }} font-bold mb-2 leading-[1.5]">{{ $currentStreak }}</div>
            <div class="text-sm {{ $stateClasses['opacity'] }} leading-[1.5]">
                {{ $currentStreak === 1 ? 'day' : 'days' }} in a row
            </div>
        </div>
        
        @if($longestStreak > 0)
            <div class="mt-4 pt-4 border-t {{ $stateClasses['border'] }}">
                <div class="flex justify-between text-sm leading-[1.5]">
                    <span class="{{ $stateClasses['opacity'] }}">Longest streak:</span>
                    <span class="font-semibold">{{ $longestStreak }} {{ Str::plural('day', $longestStreak) }}</span>
                </div>
            </div>
        @endif
        
        @if($currentStreak === 0)
            <div class="text-center mt-2">
                <p class="text-sm {{ $stateClasses['opacity'] }}">Start your reading journey today!</p>
            </div>
        @endif
    </div>
</div> 