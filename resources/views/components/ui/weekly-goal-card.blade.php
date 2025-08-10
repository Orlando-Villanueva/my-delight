@props([
    'currentProgress' => 0,
    'weeklyTarget' => 4,
    'motivationalMessage' => '',
    'showResearchInfo' => true,
    'size' => 'default'
])

@php
    $sizeClasses = [
        'small' => 'p-4 lg:p-3 xl:p-4',
        'default' => 'p-6 lg:p-4 xl:p-6',
        'large' => 'p-8 lg:p-6 xl:p-8'
    ];
    
    // Determine progress state and styling
    $isGoalAchieved = $currentProgress >= $weeklyTarget;
    $progressPercentage = min(($currentProgress / $weeklyTarget) * 100, 100);
    
    // Clean, consistent styling that matches dashboard theme
    $baseBackgroundClass = 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700';
    
    // Determine accent styling based on progress (no left border)
    if ($isGoalAchieved) {
        $headerBgClass = 'bg-green-500/5 dark:bg-green-400/10';
        $headerBorderClass = 'border-b border-green-500/10 dark:border-green-400/10';
        $progressBarClass = 'bg-green-500';
        $iconClass = 'text-green-600 dark:text-green-400';
        $defaultMessage = 'Goal achieved! Research-backed target met.';
        $statusText = 'This Week\'s Goal';
    } elseif ($currentProgress > 0) {
        $headerBgClass = 'bg-primary-500/5 dark:bg-primary-400/10';
        $headerBorderClass = 'border-b border-primary-500/10 dark:border-primary-400/10';
        $progressBarClass = 'bg-primary-500';
        $iconClass = 'text-primary-600 dark:text-primary-400';
        $defaultMessage = 'Keep going! You\'re making progress.';
        $statusText = 'This Week\'s Goal';
    } else {
        $headerBgClass = 'bg-gray-500/5 dark:bg-gray-400/10';
        $headerBorderClass = 'border-b border-gray-500/10 dark:border-gray-400/10';
        $progressBarClass = 'bg-gray-400';
        $iconClass = 'text-gray-600 dark:text-gray-400';
        $defaultMessage = 'Start your week strong!';
        $statusText = 'This Week\'s Goal';
    }
    
    $displayMessage = $motivationalMessage ?: $defaultMessage;
@endphp

<div {{ $attributes->merge(['class' => $baseBackgroundClass . ' h-full rounded-lg shadow-lg transition-colors']) }}>
    <!-- Header with subtle accent -->
    <div class="{{ $headerBgClass }} {{ $headerBorderClass }} px-6 py-4 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
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
        <div class="flex items-center justify-between mb-4">
            <div class="text-3xl lg:text-4xl font-bold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
                {{ $currentProgress }}/{{ $weeklyTarget }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 text-right leading-[1.5]">
                {{ Str::plural('day', $currentProgress) }} this week
            </div>
        </div>
        
        <!-- Progress bar -->
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
            <div class="{{ $progressBarClass }} h-3 rounded-full transition-all duration-500" 
                 style="width: {{ $progressPercentage }}%"></div>
        </div>
        
        @if($displayMessage)
            <p class="text-sm text-gray-600 dark:text-gray-400 leading-[1.5] mb-3">
                {{ $displayMessage }}
            </p>
        @endif
        
        @if($showResearchInfo)
            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-[1.5]">
                    Research-backed weekly target for spiritual growth
                </p>
            </div>
        @endif
    </div>
</div>