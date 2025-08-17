@props([
    'currentProgress' => 0,
    'weeklyTarget' => 4,
    'motivationalMessage' => '',
    'showResearchInfo' => true,
    'size' => 'default'
])

@php
    $statusText = 'Weekly Target';
    
    $sizeClasses = [
        'small' => 'p-4 lg:p-3 xl:p-4',
        'default' => 'p-6 lg:p-4 xl:p-6',
        'large' => 'p-8 lg:p-6 xl:p-8'
    ];
    
    // Design tokens for weekly goal styling - using app's custom success colors
    $weeklyGoalGradient = 'bg-gradient-to-r from-success-600 to-success-500 dark:from-success-600 dark:to-success-700';
    $weeklyGoalBorder = 'border-success-200 dark:border-success-700';
    
    // Determine progress state and styling
    $isGoalAchieved = $currentProgress >= $weeklyTarget;
    $progressPercentage = min(($currentProgress / $weeklyTarget) * 100, 100);
    
    // Weekly goal card - MOST vibrant styling to match app's bold aesthetic (primary hierarchy)
    $baseBackgroundClass = 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700';
    
    // Consistent styling across all states - using design tokens
    $headerBgClass = $weeklyGoalGradient;
    $headerBorderClass = 'border-b-0';
    $progressBarClass = $weeklyGoalGradient;
    $iconClass = 'text-white';
    $headerTextClass = 'text-white font-semibold';
    
    // State-specific messages
    if ($isGoalAchieved) {
        $defaultMessage = 'Goal achieved! Research-backed target met.';
    } elseif ($currentProgress > 0) {
        $defaultMessage = 'Keep going! You\'re making progress.';
    } else {
        $defaultMessage = 'Start your week strong!';
    }
    
    $displayMessage = $motivationalMessage ?: $defaultMessage;
@endphp

<div {{ $attributes->merge(['class' => $baseBackgroundClass . ' h-full rounded-lg shadow-lg transition-colors']) }}>
    <!-- Header with subtle accent -->
    <div class="{{ $headerBgClass }} {{ $headerBorderClass }} px-6 py-4 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="text-lg {{ $headerTextClass }} leading-[1.5]">
                {{ $statusText }}
            </h3>
            @if($isGoalAchieved)
                <svg class="w-6 h-6 {{ $iconClass }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            @endif
        </div>
    </div>
    
    <!-- Main content -->
    <div class="{{ $sizeClasses[$size] ?? $sizeClasses['default'] }}">
        <div class="text-center mb-4">
            <div class="text-3xl lg:text-4xl font-bold text-gray-700 dark:text-gray-200 leading-[1.5] mb-2">
                {{ $currentProgress }}/{{ $weeklyTarget }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-300 leading-[1.5]">
                {{ Str::plural('day', $currentProgress) }} this week
            </div>
        </div>
        
        <!-- Progress bar -->
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
            <div class="{{ $progressBarClass }} h-3 rounded-full transition-all duration-500" 
                 style="width: {{ $progressPercentage }}%"></div>
        </div>
        
        @if($displayMessage)
            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-[1.5] text-center">
                    {{ $displayMessage }}
                </p>
            </div>
        @endif
    </div>
</div>