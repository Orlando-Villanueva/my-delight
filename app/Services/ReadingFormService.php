<?php

namespace App\Services;

use App\Models\ReadingLog;
use App\Models\User;
use Carbon\Carbon;

class ReadingFormService
{
    public function __construct(
        private ReadingLogService $readingLogService,
        private BibleReferenceService $bibleService
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
     * Get user's recently read books with formatted timestamps.
     * Uses idx_recent_books composite index for optimal performance.
     */
    public function getRecentBooks(User $user, int $limit = 5): array
    {
        return ReadingLog::where('user_id', $user->id)
            ->orderBy('date_read', 'desc')
            ->get()
            ->groupBy('book_id')
            ->map(fn ($logs) => $logs->first())
            ->sortByDesc('date_read')
            ->take($limit)
            ->map(fn ($log) => $this->formatRecentBookEntry($log))
            ->values()
            ->toArray();
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

    /**
     * Format a reading log entry with book details and human-readable timestamp.
     */
    private function formatRecentBookEntry(ReadingLog $log): array
    {
        $bookDetails = $this->bibleService->getBibleBook($log->book_id);
        $lastReadDate = Carbon::parse($log->date_read);

        // Format as "today", "yesterday", or "X days ago"
        $lastReadHuman = $lastReadDate->isToday() ? 'today'
            : ($lastReadDate->isYesterday() ? 'yesterday'
            : $lastReadDate->diffForHumans(['parts' => 1, 'short' => false]));

        return [
            'id' => $log->book_id,
            'name' => $bookDetails['name'],
            'chapters' => $bookDetails['chapters'],
            'testament' => $bookDetails['testament'],
            'last_read' => $log->date_read,
            'last_read_human' => $lastReadHuman,
        ];
    }
}
