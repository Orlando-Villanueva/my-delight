<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReadingLog;
use App\Models\BookProgress;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ReadingLogService
{
    /**
     * Log a new Bible reading entry for a user.
     */
    public function logReading(User $user, array $data): ReadingLog
    {
        // Create the reading log
        $readingLog = $user->readingLogs()->create([
            'book_id' => $data['book_id'],
            'chapter' => $data['chapter'],
            'passage_text' => $data['passage_text'],
            'date_read' => $data['date_read'] ?? now()->toDateString(),
            'notes_text' => $data['notes_text'] ?? null,
        ]);

        // Update book progress
        $this->updateBookProgress($user, $data['book_id'], $data['chapter']);

        return $readingLog;
    }

    /**
     * Get reading history for a user with optional filtering.
     */
    public function getReadingHistory(User $user, ?int $limit = null, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = $user->readingLogs()->recentFirst();

        if ($startDate) {
            $query->dateRange($startDate, $endDate);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Calculate the current reading streak for a user.
     */
    public function calculateCurrentStreak(User $user): int
    {
        $readingDates = $user->readingLogs()
            ->select('date_read')
            ->distinct()
            ->orderBy('date_read', 'desc')
            ->pluck('date_read')
            ->map(fn($date) => Carbon::parse($date));

        if ($readingDates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $currentDate = Carbon::today();
        
        // Check if user has read today or yesterday (1-day grace period)
        $hasRecentReading = $readingDates->contains(fn($date) => 
            $date->isSameDay($currentDate) || $date->isSameDay($currentDate->subDay())
        );

        if (!$hasRecentReading) {
            return 0;
        }

        // Calculate consecutive days
        $previousDate = null;
        foreach ($readingDates as $date) {
            if ($previousDate === null) {
                $streak = 1;
                $previousDate = $date;
                continue;
            }

            $daysDifference = $previousDate->diffInDays($date);
            
            if ($daysDifference <= 1) {
                $streak++;
                $previousDate = $date;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Calculate the longest streak ever for a user.
     */
    public function calculateLongestStreak(User $user): int
    {
        $readingDates = $user->readingLogs()
            ->select('date_read')
            ->distinct()
            ->orderBy('date_read', 'asc')
            ->pluck('date_read')
            ->map(fn($date) => Carbon::parse($date));

        if ($readingDates->isEmpty()) {
            return 0;
        }

        $longestStreak = 1;
        $currentStreak = 1;
        $previousDate = $readingDates->first();

        foreach ($readingDates->skip(1) as $date) {
            $daysDifference = $previousDate->diffInDays($date);
            
            if ($daysDifference <= 1) {
                $currentStreak++;
                $longestStreak = max($longestStreak, $currentStreak);
            } else {
                $currentStreak = 1;
            }
            
            $previousDate = $date;
        }

        return $longestStreak;
    }

    /**
     * Update book progress when a chapter is read.
     */
    private function updateBookProgress(User $user, int $bookId, int $chapter): void
    {
        // This will be implemented with Bible configuration
        // For now, we'll create a placeholder
        $bookProgress = $user->bookProgress()->firstOrCreate(
            ['book_id' => $bookId],
            [
                'book_name' => "Book {$bookId}", // Will be replaced with actual book name
                'total_chapters' => 50, // Will be replaced with actual chapter count
                'chapters_read' => [],
                'completion_percent' => 0,
                'is_completed' => false,
            ]
        );

        $bookProgress->addChapter($chapter);
        $bookProgress->save();
    }
} 