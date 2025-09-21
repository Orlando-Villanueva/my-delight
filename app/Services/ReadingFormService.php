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
        // 3. Allow yesterday if user had readings before yesterday (potential streak to recover)
        $hadReadingsBeforeYesterday = $user->readingLogs()
            ->whereDate('date_read', '<', today()->subDay())
            ->exists();

        $allowYesterday = ! $hasReadYesterday && ! $isNewUser && $hadReadingsBeforeYesterday;

        return [
            'allowYesterday' => $allowYesterday,
            'hasReadToday' => $hasReadToday,
            'hasReadYesterday' => $hasReadYesterday,
            'currentStreak' => $currentStreak,
        ];
    }
}
