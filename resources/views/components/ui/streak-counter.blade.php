@props([
    'currentStreak' => 0,
    'longestStreak' => 0,
    'size' => 'default'
])

@php
    $sizeClasses = [
        'small' => 'p-4',
        'default' => 'p-6',
        'large' => 'p-8'
    ];
    
    $numberSizes = [
        'small' => 'text-2xl',
        'default' => 'text-4xl',
        'large' => 'text-6xl'
    ];
    
    $iconSizes = [
        'small' => 'w-6 h-6',
        'default' => 'w-8 h-8',
        'large' => 'w-12 h-12'
    ];
@endphp

<div {{ $attributes->merge(['class' => 'streak-counter ' . ($sizeClasses[$size] ?? $sizeClasses['default'])]) }}>
    <div class="flex items-center justify-center mb-2">
        <!-- Fire Icon -->
        <svg class="{{ $iconSizes[$size] ?? $iconSizes['default'] }} mr-3 text-accent" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.5 2C13.81 2 15.5 3.5 15.5 4.5C15.5 5.5 14.5 6.5 13.5 6.5C12.5 6.5 11.5 5.5 11.5 4.5C11.5 3.5 12.19 2 12.5 2M14.5 15.5C14.5 16.61 13.61 17.5 12.5 17.5S10.5 16.61 10.5 15.5 11.39 13.5 12.5 13.5 14.5 14.39 14.5 15.5M7.5 11C7.5 12.11 6.61 13 5.5 13S3.5 12.11 3.5 11 4.39 9 5.5 9 7.5 9.89 7.5 11M20.5 11C20.5 12.11 19.61 13 18.5 13S16.5 12.11 16.5 11 17.39 9 18.5 9 20.5 9.89 20.5 11M12.5 8C16.09 8 19 10.91 19 14.5C19 16.5 18 18.5 16.5 19.5C15 20.5 13 21 12.5 21C12 21 10 20.5 8.5 19.5C7 18.5 6 16.5 6 14.5C6 10.91 8.91 8 12.5 8Z"/>
        </svg>
        
        <div class="text-center">
            <div class="{{ $numberSizes[$size] ?? $numberSizes['default'] }} font-bold streak-number">
                {{ $currentStreak }}
            </div>
            <div class="streak-label">
                {{ $currentStreak === 1 ? 'Day Streak' : 'Days Streak' }}
            </div>
        </div>
    </div>
    
    @if($longestStreak > 0 && $longestStreak !== $currentStreak)
        <div class="text-center mt-3 pt-3 border-t border-white/20">
            <div class="text-sm opacity-90">
                Longest: <span class="font-semibold">{{ $longestStreak }} {{ $longestStreak === 1 ? 'day' : 'days' }}</span>
            </div>
        </div>
    @endif
    
    @if($currentStreak === 0)
        <div class="text-center mt-2">
            <p class="text-sm opacity-90">Start your reading journey today!</p>
        </div>
    @endif
</div> 