<?php

namespace App\Services;

use Carbon\Carbon;

class StreakStateService
{
    /**
     * Determine the visual state of the streak counter component
     * 
     * @param int $currentStreak
     * @param bool $hasReadToday
     * @param Carbon|null $currentTime
     * @return string
     */
    public function determineStreakState(int $currentStreak, bool $hasReadToday, ?Carbon $currentTime = null): string
    {
        $currentTime = $currentTime ?? now();

        // Inactive state: current streak is 0
        if ($currentStreak === 0) {
            return 'inactive';
        }

        // Warning state: has streak but hasn't read today and it's past warning time (6 PM)
        if ($currentStreak > 0 && !$hasReadToday && $currentTime->hour >= 18) {
            return 'warning';
        }

        // Active state: has streak and either read today or not past warning time
        return 'active';
    }

    /**
     * Get the CSS classes for a given streak state
     * 
     * @param string $state
     * @return array
     */
    public function getStateClasses(string $state): array
    {
        $stateClasses = [
            'inactive' => 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-[#D1D7E0] dark:border-gray-700 shadow-md',
            'active' => 'bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white shadow-md',
            'warning' => 'bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md'
        ];

        $iconColors = [
            'inactive' => 'text-gray-400 dark:text-gray-500',
            'active' => 'text-accent-500',
            'warning' => 'text-orange-200'
        ];

        return [
            'background' => $stateClasses[$state] ?? $stateClasses['active'],
            'icon' => $iconColors[$state] ?? $iconColors['active'],
            'showIcon' => $state !== 'inactive',
            'opacity' => $state === 'inactive' ? 'opacity-70' : 'opacity-90',
            'border' => $state === 'inactive' ? 'border-[#D1D7E0] dark:border-gray-600' : 'border-white/20'
        ];
    }
}
