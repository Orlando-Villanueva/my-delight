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
    
    // Determine progress state and styling
    $isGoalAchieved = $currentProgress >= $weeklyTarget;
    $progressPercentage = min(($currentProgress / $weeklyTarget) * 100, 100);
    
    // Dynamic styling based on progress
    if ($isGoalAchieved) {
        $backgroundClass = 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800';
        $textClass = 'text-green-800 dark:text-green-200';
        $iconClass = 'text-green-600 dark:text-green-400';
        $progressBarClass = 'bg-green-500';
        $defaultMessage = 'Great job! Goal achieved this week! 🎉';
    } elseif ($currentProgress > 0) {
        $backgroundClass = 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800';
        $textClass = 'text-blue-800 dark:text-blue-200';
        $iconClass = 'text-blue-600 dark:text-blue-400';
        $progressBarClass = 'bg-blue-500';
        $defaultMessage = 'Keep it up! You\'re making progress.';
    } else {
        $backgroundClass = 'bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700';
        $textClass = 'text-gray-800 dark:text-gray-200';
        $iconClass = 'text-gray-600 dark:text-gray-400';
        $progressBarClass = 'bg-gray-400';
        $defaultMessage = 'Start your week strong!';
    }
    
    $displayMessage = $motivationalMessage ?: $defaultMessage;
@endphp

<div {{ $attributes->merge(['class' => $backgroundClass . ' h-full rounded-lg shadow-lg transition-colors ' . ($sizeClasses[$size] ?? $sizeClasses['default'])]) }}>
    <div class="flex flex-col justify-center h-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg lg:text-xl font-semibold leading-[1.5] {{ $textClass }}">Weekly Goal</h3>
            <div class="p-2 rounded-lg bg-white/50 dark:bg-gray-700/50">
                <svg class="{{ $iconSizes[$size] ?? $iconSizes['default'] }} {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        
        <div class="text-center flex-1 flex flex-col justify-center">
            <div class="{{ $numberSizes[$size] ?? $numberSizes['default'] }} font-bold mb-2 leading-[1.5] {{ $textClass }}">
                {{ $currentProgress }}/{{ $weeklyTarget }}
            </div>
            <div class="text-sm {{ $textClass }} opacity-80 leading-[1.5] mb-3">
                {{ Str::plural('day', $currentProgress) }} this week
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-white/30 dark:bg-gray-700/30 rounded-full h-2 mb-3">
                <div class="{{ $progressBarClass }} h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>
        
        @if($displayMessage)
            <div class="text-center mt-2">
                <p class="text-sm {{ $textClass }} opacity-80 leading-[1.5]">{{ $displayMessage }}</p>
            </div>
        @endif
        
        @if($showResearchInfo)
            <div class="mt-4 pt-4 border-t border-current/20">
                <div class="text-center">
                    <p class="text-xs {{ $textClass }} opacity-60 leading-[1.5]">Research-backed weekly target</p>
                </div>
            </div>
        @endif
    </div>
</div>