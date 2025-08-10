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
    // Determine state from backend stateClasses (inactive, active, warning)
    $state = 'active'; // default
    if (!($stateClasses['showIcon'] ?? false)) {
        $state = 'inactive';
    } elseif (str_contains($stateClasses['background'] ?? '', 'orange')) {
        $state = 'warning';
    }
    
    // Daily streak styling - vibrant and lively like production, but secondary to weekly goals
    $baseClass = 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 h-full rounded-lg shadow-lg transition-colors';
    
    // Better balance - vibrant but secondary to weekly goal, flipped gradients for better contrast
    switch ($state) {
        case 'warning':
            // Warning state: darker to lighter for better text/icon contrast
            $headerBgClass = 'bg-gradient-to-r from-accent-400 to-accent-300 dark:from-accent-700 dark:to-accent-600';
            $headerBorderClass = 'border-b-0';
            $iconClass = 'text-accent-600 dark:text-accent-300 animate-pulse';  // Fading fire - getting weaker but alarming!
            $headerTextClass = 'text-white font-medium';
            $numberColorClass = 'text-gray-700 dark:text-gray-200';
            $textColorClass = 'text-gray-600 dark:text-gray-300';
            break;
            
        case 'active':
            // Active state: beautiful blue gradient with fiery orange icon like production!
            $headerBgClass = 'bg-gradient-to-r from-primary-600 to-primary-500 dark:from-primary-600 dark:to-primary-500';
            $headerBorderClass = 'border-b-0';
            $iconClass = 'text-accent-500 dark:text-accent-400';  // Fire icon back to fiery orange!
            $headerTextClass = 'text-white font-semibold';  // Bold white text like weekly card
            $numberColorClass = 'text-gray-700 dark:text-gray-200';
            $textColorClass = 'text-gray-600 dark:text-gray-300';
            break;
            
        case 'inactive':
        default:
            // Inactive state: now uses blue header like weekly goal - consistent visual identity
            $headerBgClass = 'bg-gradient-to-r from-primary-600 to-primary-500 dark:from-primary-700 dark:to-primary-600';
            $headerBorderClass = 'border-b-0';
            $iconClass = 'text-accent-500 dark:text-accent-400';  // Fire is always fiery!
            $headerTextClass = 'text-white font-semibold';
            $numberColorClass = 'text-gray-700 dark:text-gray-200';
            $textColorClass = 'text-gray-600 dark:text-gray-300';
            break;
    }
@endphp

<div {{ $attributes->merge(['class' => $baseClass]) }}>
    <!-- Header with subtle accent -->
    <div class="{{ $headerBgClass }} {{ $headerBorderClass }} px-6 py-4 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="text-lg {{ $headerTextClass }} leading-[1.5]">Daily Streak</h3>
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
            <div class="{{ $numberSizes[$size] ?? $numberSizes['default'] }} font-bold {{ $numberColorClass }} mb-2 leading-[1.5]">
                {{ $currentStreak }}
            </div>
            <div class="text-sm {{ $textColorClass }} leading-[1.5]">
                {{ $currentStreak === 1 ? 'day' : 'days' }} in a row
            </div>
        </div>
        
        @if($longestStreak > 0)
            <div class="mb-3">
                <div class="flex justify-between text-sm leading-[1.5]">
                    <span class="{{ $textColorClass }}">Longest streak:</span>
                    <span class="font-semibold {{ $numberColorClass }}">{{ $longestStreak }} {{ Str::plural('day', $longestStreak) }}</span>
                </div>
            </div>
        @endif
        
        @if($message)
            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm {{ $textColorClass }} leading-[1.5] text-center">
                    {{ $message }}
                </p>
            </div>
        @endif
    </div>
</div> 