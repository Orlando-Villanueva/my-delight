<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReadingLog;
use App\Models\BookProgress;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use InvalidArgumentException;

class ReadingLogService
{
    /**
     * Log a new Bible reading entry for a user (supports single chapter or chapter ranges).
     */
    public function logReading(User $user, array $data): ReadingLog
    {
        // Handle multiple chapters if provided
        if (isset($data['chapters']) && is_array($data['chapters'])) {
            return $this->logMultipleChapters($user, $data);
        }

        // Single chapter logging
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
     * Log multiple chapters as separate reading log entries.
     */
    private function logMultipleChapters(User $user, array $data): ReadingLog
    {
        $chapters = $data['chapters'];
        $firstLog = null;
        
        foreach ($chapters as $chapter) {
            $readingLog = $user->readingLogs()->create([
                'book_id' => $data['book_id'],
                'chapter' => $chapter,
                'passage_text' => $data['passage_text'], // Range text like "John 1-3"
                'date_read' => $data['date_read'] ?? now()->toDateString(),
                'notes_text' => $data['notes_text'] ?? null,
            ]);

            // Update book progress for each chapter
            $this->updateBookProgress($user, $data['book_id'], $chapter);
            
            // Return the first log for response consistency
            if ($firstLog === null) {
                $firstLog = $readingLog;
            }
        }

        return $firstLog;
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
        
        // Late Logging Grace: Check if user has read today or yesterday (1-day grace period for forgotten logs)
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
        $bibleService = app(BibleReferenceService::class);
        
        // Get book information from BibleReferenceService
        $book = $bibleService->getBibleBook($bookId);
        if (!$book) {
            throw new InvalidArgumentException("Invalid book ID: {$bookId}");
        }

        $bookProgress = $user->bookProgress()->firstOrCreate(
            ['book_id' => $bookId],
            [
                'book_name' => $book['name'],
                'total_chapters' => $book['chapters'],
                'chapters_read' => [],
                'completion_percent' => 0,
                'is_completed' => false,
            ]
        );

        // Get current chapters read
        $chaptersRead = $bookProgress->chapters_read ?? [];
        
        // Add new chapter if not already recorded
        if (!in_array($chapter, $chaptersRead)) {
            $chaptersRead[] = $chapter;
            sort($chaptersRead); // Keep chapters sorted
            
            // Update book progress
            $bookProgress->chapters_read = $chaptersRead;
            $bookProgress->completion_percent = round((count($chaptersRead) / $book['chapters']) * 100, 2);
            $bookProgress->is_completed = count($chaptersRead) >= $book['chapters'];
            $bookProgress->save();
        }
    }
} 