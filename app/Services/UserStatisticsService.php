<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class UserStatisticsService
{
    public function __construct(
        private ReadingLogService $readingLogService
    ) {}

    /**
     * Get comprehensive dashboard statistics for a user.
     */
    public function getDashboardStatistics(User $user): array
    {
        return [
            'streaks' => $this->getStreakStatistics($user),
            'reading_summary' => $this->getReadingSummary($user),
            'book_progress' => $this->getBookProgressSummary($user),
            'recent_activity' => $this->getRecentActivity($user),
        ];
    }

    /**
     * Get streak-related statistics.
     */
    public function getStreakStatistics(User $user): array
    {
        return [
            'current_streak' => $this->readingLogService->calculateCurrentStreak($user),
            'longest_streak' => $this->readingLogService->calculateLongestStreak($user),
        ];
    }

    /**
     * Get reading summary statistics.
     */
    public function getReadingSummary(User $user): array
    {
        $totalReadings = $user->readingLogs()->count();
        $firstReading = $user->readingLogs()->oldest()->first();
        $lastReading = $user->readingLogs()->latest()->first();

        return [
            'total_readings' => $totalReadings,
            'first_reading_date' => $firstReading?->date_read,
            'last_reading_date' => $lastReading?->date_read,
            'days_since_first_reading' => $firstReading 
                ? Carbon::parse($firstReading->date_read)->diffInDays(now()) + 1
                : 0,
            'this_month_days' => $this->getThisMonthReadingDays($user),
            'this_week_days' => $this->getThisWeekReadingDays($user),
        ];
    }

    /**
     * Get reading days count for current month.
     */
    private function getThisMonthReadingDays(User $user): int
    {
        return $user->readingLogs()
            ->whereMonth('date_read', now()->month)
            ->whereYear('date_read', now()->year)
            ->distinct('date_read')
            ->count('date_read');
    }

    /**
     * Get reading days count for current week.
     */
    private function getThisWeekReadingDays(User $user): int
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return $user->readingLogs()
            ->whereBetween('date_read', [$startOfWeek, $endOfWeek])
            ->distinct('date_read')
            ->count('date_read');
    }

    /**
     * Get book progress summary.
     */
    public function getBookProgressSummary(User $user): array
    {
        $bookProgress = $user->bookProgress;
        
        $completed = $bookProgress->where('is_completed', true)->count();
        $inProgress = $bookProgress->where('is_completed', false)
                                  ->where('completion_percent', '>', 0)
                                  ->count();
        $notStarted = 66 - $completed - $inProgress; // Total Bible books minus started

        return [
            'books_completed' => $completed,
            'books_in_progress' => $inProgress,
            'books_not_started' => max(0, $notStarted),
            'total_bible_books' => 66,
            'overall_progress_percent' => $this->calculateOverallBibleProgress($user),
        ];
    }

    /**
     * Get recent reading activity.
     */
    public function getRecentActivity(User $user, int $limit = 5): array
    {
        // Group by reading session to avoid duplicates for chapter ranges
        $recentReadings = $user->readingLogs()
            ->recentFirst()
            ->get()
            ->groupBy(function ($log) {
                return $log->passage_text . '|' . $log->date_read . '|' . $log->created_at->format('Y-m-d H:i:s');
            })
            ->map(function ($group) {
                return $group->first(); // Take the first entry from each group
            })
            ->values()
            ->sortByDesc('created_at')
            ->take($limit);
        
        return $recentReadings->map(function ($reading) {
            return [
                'id' => $reading->id,
                'passage_text' => $reading->passage_text,
                'date_read' => $reading->date_read,
                'notes_text' => $reading->notes_text,
                'days_ago' => Carbon::parse($reading->date_read)->diffInDays(now()),
            ];
        })->toArray();
    }

    /**
     * Get calendar data for visualization (similar to GitHub contribution graph).
     */
    public function getCalendarData(User $user, ?string $year = null): array
    {
        $year = $year ?? now()->year;
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        $readingDates = $user->readingLogs()
            ->whereBetween('date_read', [$startDate, $endDate])
            ->selectRaw('date_read, COUNT(*) as reading_count')
            ->groupBy('date_read')
            ->pluck('reading_count', 'date_read')
            ->toArray();

        $calendar = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->toDateString();
            $calendar[$dateString] = [
                'date' => $dateString,
                'reading_count' => $readingDates[$dateString] ?? 0,
                'has_reading' => isset($readingDates[$dateString]),
            ];
            $currentDate->addDay();
        }

        return $calendar;
    }

    /**
     * Calculate overall Bible reading progress percentage.
     */
    private function calculateOverallBibleProgress(User $user): float
    {
        $totalChapters = 1189; // Total chapters in the Bible
        $chaptersRead = $user->readingLogs()->count();
        
        return $totalChapters > 0 ? round(($chaptersRead / $totalChapters) * 100, 2) : 0;
    }
} 