<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class ReadingFormService
{
    public function __construct(
        private ReadingLogService $readingLogService
    ) {}

    /**
     * Check if the user has read today.
     */
    public function hasReadToday(User $user): bool
    {
        return $user->readingLogs()
            ->whereDate('date_read', today())
            ->exists();
    }

    /**
     * Get yesterday availability logic and user reading status for the form.
     * This determines if the "yesterday" option should be available based on streak preservation.
     */
    public function getFormContextData(User $user): array
    {
        $hasReadToday = $this->hasReadToday($user);
        
        $hasReadYesterday = $user->readingLogs()
            ->whereDate('date_read', today()->subDay())
            ->exists();
            
        $currentStreak = $this->readingLogService->calculateCurrentStreak($user);
        
        // Yesterday option logic:
        // 1. If already read yesterday, don't show the option
        // 2. If current streak > 0 AND haven't read today, yesterday could break the streak pattern
        // 3. Allow yesterday if: no streak OR has read today OR hasn't read yesterday
        $allowYesterday = !$hasReadYesterday && ($currentStreak === 0 || $hasReadToday);
        
        return [
            'allowYesterday' => $allowYesterday,
            'hasReadToday' => $hasReadToday,
            'currentStreak' => $currentStreak
        ];
    }
} 