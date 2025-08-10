@props([
    'currentStreak' => 0,
    'longestStreak' => 0,
    'stateClasses' => [],
    'message' => '',
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

@php
    // Use consistent dashboard styling instead of dynamic colors
    $baseClass = 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 h-full rounded-lg shadow-lg transition-colors';
    
    // Add subtle accent based on streak state (no left border)
    if($stateClasses['showIcon'] ?? false) {
        $headerBgClass = 'bg-accent-500/5 dark:bg-accent-400/10';
        $headerBorderClass = 'border-b border-accent-500/10 dark:border-accent-400/10';
        $iconClass = 'text-accent-600 dark:text-accent-400';
    } else {
        $headerBgClass = 'bg-gray-500/5 dark:bg-gray-400/10';
        $headerBorderClass = 'border-b border-gray-500/10 dark:border-gray-400/10';
        $iconClass = 'text-gray-600 dark:text-gray-400';
    }
@endphp

<div {{ $attributes->merge(['class' => $baseClass]) }}>
    <!-- Header with subtle accent -->
    <div class="{{ $headerBgClass }} {{ $headerBorderClass }} px-6 py-4 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">Daily Streak</h3>
            @if($stateClasses['showIcon'] ?? false)
                <svg class="{{ $iconSizes[$size] ?? $iconSizes['default'] }} {{ $iconClass }}" fill="currentColor" viewBox="0 0 384 512">
                    <path d="M216 23.86c0-23.8-30.65-32.77-44.15-13.04C48 191.85 224 200 224 288c0 35.63-29.11 64.46-64.85 63.99-35.17-.45-63.15-29.77-63.15-64.94v-85.51c0-21.7-26.47-32.4-41.6-16.9C21.22 216.4 0 268.2 0 320c0 105.87 86.13 192 192 192s192-86.13 192-192c0-170.29-168-193.17-168-296.14z"/>
                </svg>
            @endif
        </div>
    </div>
    
    <!-- Main content -->
    <div class="{{ $sizeClasses[$size] ?? $sizeClasses['default'] }}">
        <div class="text-center mb-4">
            <div class="{{ $numberSizes[$size] ?? $numberSizes['default'] }} font-bold text-[#4A5568] dark:text-gray-200 mb-2 leading-[1.5]">
                {{ $currentStreak }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">
                {{ $currentStreak === 1 ? 'day' : 'days' }} in a row
            </div>
        </div>
        
        @if($longestStreak > 0)
            <div class="pt-3 border-t border-gray-200 dark:border-gray-600 mb-3">
                <div class="flex justify-between text-sm leading-[1.5]">
                    <span class="text-gray-500 dark:text-gray-400">Longest streak:</span>
                    <span class="font-semibold text-[#4A5568] dark:text-gray-200">{{ $longestStreak }} {{ Str::plural('day', $longestStreak) }}</span>
                </div>
            </div>
        @endif
        
        @if($message)
            <p class="text-sm text-gray-600 dark:text-gray-400 leading-[1.5] text-center">
                {{ $message }}
            </p>
        @endif
    </div>
</div> 