<?php

namespace App\Services;

use App\Models\User;

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

        // Check if user is new (created today) to prevent logging for yesterday before they existed
        $isNewUser = $user->created_at->isToday();

        // Yesterday option logic:
        // 1. If already read yesterday, don't show the option
        // 2. If user is new (created today), don't allow yesterday (they didn't exist)
        // 3. If current streak > 0 AND haven't read today, yesterday could break the streak pattern
        // 4. Allow yesterday if: no streak OR has read today OR hasn't read yesterday
        $allowYesterday = ! $hasReadYesterday && ! $isNewUser && ($currentStreak === 0 || $hasReadToday);

        return [
            'allowYesterday' => $allowYesterday,
            'hasReadToday' => $hasReadToday,
            'hasReadYesterday' => $hasReadYesterday,
            'currentStreak' => $currentStreak,
        ];
    }
}
