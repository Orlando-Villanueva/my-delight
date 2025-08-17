@props([
    'streakCount' => 0,
    'isActive' => false,
    'motivationalMessage' => '',
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
        'default' => 'text-3xl lg:text-4xl',
        'large' => 'text-4xl lg:text-5xl'
    ];
    
    $iconSizes = [
        'small' => 'w-5 h-5',
        'default' => 'w-6 h-6',
        'large' => 'w-8 h-8'
    ];
    
    // Design tokens for weekly streak styling - purple/indigo for secondary hierarchy
    $weeklyStreakGradient = 'bg-gradient-to-r from-purple-600 to-purple-500 dark:from-purple-800 dark:to-purple-700';
    
    // Base styling - consistent with other cards
    $baseBackgroundClass = 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700';
    
    // Consistent styling - secondary to weekly goal, but still prominent
    $headerBgClass = $weeklyStreakGradient;
    $headerBorderClass = 'border-b-0';
    $iconClass = 'text-white';
    $headerTextClass = 'text-white font-semibold';
    $numberColorClass = 'text-gray-700 dark:text-gray-200';
    $textColorClass = 'text-gray-600 dark:text-gray-300';
    
    // Generate appropriate message if none provided
    if (!$motivationalMessage) {
        if ($streakCount === 0) {
            $defaultMessage = 'Start your first weekly streak!';
        } elseif ($streakCount === 1) {
            $defaultMessage = 'Great start! Keep the momentum going.';
        } elseif ($streakCount >= 2 && $streakCount <= 3) {
            $defaultMessage = "Building consistency! {$streakCount} weeks in a row.";
        } else {
            $defaultMessage = "Amazing consistency! {$streakCount} weeks strong!";
        }
    } else {
        $defaultMessage = $motivationalMessage;
    }
    
    // Show icon for active streaks or encouraging state
    $showIcon = $isActive || $streakCount > 0;
@endphp

<div {{ $attributes->merge(['class' => $baseBackgroundClass . ' h-full rounded-lg shadow-lg transition-colors']) }}>
    <!-- Header with purple/indigo gradient for secondary hierarchy -->
    <div class="{{ $headerBgClass }} {{ $headerBorderClass }} px-6 py-4 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="text-lg {{ $headerTextClass }} leading-[1.5]">
                Weekly Streak
            </h3>
            @if($showIcon)
                <!-- Fire icon for active weekly streaks (same as daily streak) -->
                <svg class="{{ $iconSizes[$size] ?? $iconSizes['default'] }} {{ $iconClass }}" fill="currentColor" viewBox="0 0 384 512">
                    <path d="M216 23.86c0-23.8-30.65-32.77-44.15-13.04C48 191.85 224 200 224 288c0 35.63-29.11 64.46-64.85 63.99-35.17-.45-63.15-29.77-63.15-64.94v-85.51c0-21.7-26.47-32.4-41.6-16.9C21.22 216.4 0 268.2 0 320c0 105.87 86.13 192 192 192s192-86.13 192-192c0-170.29-168-193.17-168-296.14z"/>
                </svg>
            @endif
        </div>
    </div>
    
    <!-- Main content -->
    <div class="{{ $sizeClasses[$size] ?? $sizeClasses['default'] }}">
        <div class="text-center mb-4">
            <div class="{{ $numberSizes[$size] ?? $numberSizes['default'] }} font-bold {{ $numberColorClass }} leading-[1.5] mb-2">
                @if($streakCount === 0)
                    0
                @else
                    {{ $streakCount }}
                @endif
            </div>
            <div class="text-sm {{ $textColorClass }} leading-[1.5]">
                @if($streakCount === 0)
                    weeks streak
                @else
                    {{ $streakCount === 1 ? 'week' : 'weeks' }} in a row
                @endif
            </div>
        </div>
        
        @if($defaultMessage)
            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm {{ $textColorClass }} leading-[1.5] text-center">
                    {{ $defaultMessage }}
                </p>
            </div>
        @endif
    </div>
</div>