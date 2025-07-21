<?php

namespace App\Services;

use App\Contracts\ReadingLogInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
        return Cache::remember(
            "user_dashboard_stats_{$user->id}",
            300, // 5 minutes TTL
            fn() => [
                'streaks' => $this->getStreakStatistics($user),
                'reading_summary' => $this->getReadingSummary($user),
                'book_progress' => $this->getBookProgressSummary($user),
                'recent_activity' => $this->getRecentActivity($user),
            ]
        );
    }

    /**
     * Get streak-related statistics.
     */
    public function getStreakStatistics(User $user): array
    {
        $currentStreak = Cache::remember(
            "user_current_streak_{$user->id}",
            900, // 15 minutes TTL
            fn() => $this->readingLogService->calculateCurrentStreak($user)
        );

        $longestStreak = Cache::remember(
            "user_longest_streak_{$user->id}",
            1800, // 30 minutes TTL (changes less frequently)
            fn() => $this->readingLogService->calculateLongestStreak($user)
        );

        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
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
        // Eager load book progress data with a single query instead of lazy loading
        $bookProgress = $user->bookProgress()->get();

        $completed = $bookProgress->where('is_completed', true)->count();
        $inProgress = $bookProgress->where('is_completed', false)
            ->where('completion_percent', '>', 0)
            ->count();
        $notStarted = 66 - $completed - $inProgress; // Total Bible books minus started

        // Calculate overall progress using the already loaded collection
        $totalChapters = 1189; // Total chapters in the Bible
        $chaptersRead = $bookProgress->sum(function ($progress) {
            return count($progress->chapters_read ?? []);
        });
        $overallProgressPercent = $totalChapters > 0 ? round(($chaptersRead / $totalChapters) * 100, 2) : 0;

        return [
            'books_completed' => $completed,
            'books_in_progress' => $inProgress,
            'books_not_started' => max(0, $notStarted),
            'total_bible_books' => 66,
            'overall_progress_percent' => $overallProgressPercent,
        ];
    }

    /**
     * Get recent reading activity.
     */
    public function getRecentActivity(User $user, int $limit = 5): array
    {
        // Apply limit at database level to avoid loading all records into memory
        // Use a more efficient query with specific index
        $recentReadings = $user->readingLogs()
            ->select('id', 'passage_text', 'date_read', 'notes_text', 'created_at')
            ->orderBy('date_read', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit * 3) // Get more records to account for potential duplicates
            ->get()
            ->unique(function ($log) {
                // Use a safer separator that won't appear in passage text
                return $log->passage_text . '::' . $log->date_read;
            })
            ->take($limit);

        return $recentReadings->map(function ($reading) {
            return [
                'id' => $reading->id,
                'passage_text' => $reading->passage_text,
                'date_read' => $reading->date_read,
                'notes_text' => $reading->notes_text,
                'time_ago' => $this->calculateSmartTimeAgo($reading),
            ];
        })->values()->toArray();
    }

    /**
     * Get calendar data for visualization (similar to GitHub contribution graph).
     */
    public function getCalendarData(User $user, ?string $year = null): array
    {
        $year = $year ?? now()->year;
        
        return Cache::remember(
            "user_calendar_{$user->id}_{$year}",
            600, // 10 minutes TTL
            function () use ($user, $year) {
                $startDate = Carbon::create($year, 1, 1);
                $endDate = Carbon::create($year, 12, 31);

                // Get all reading logs for the year and group by date using collections
                $readingLogs = $user->readingLogs()
                    ->whereBetween('date_read', [$startDate, $endDate])
                    ->get(['date_read']);

                // Group by date and count readings per date
                $readingData = $readingLogs
                    ->groupBy(function ($log) {
                        return Carbon::parse($log->date_read)->toDateString();
                    })
                    ->map(function ($readings) {
                        return $readings->count();
                    })
                    ->toArray();

                $calendar = [];
                $currentDate = $startDate->copy();

                while ($currentDate->lte($endDate)) {
                    $dateString = $currentDate->toDateString();
                    $readingCount = $readingData[$dateString] ?? 0;

                    $calendar[$dateString] = [
                        'date' => $dateString,
                        'reading_count' => $readingCount,
                        'has_reading' => $readingCount > 0,
                    ];
                    $currentDate->addDay();
                }

                return $calendar;
            }
        );
    }



    /**
     * Calculate smart time ago that considers the context of when reading was done vs when it was logged.
     */
    public function calculateSmartTimeAgo(ReadingLogInterface $reading): string
    {
        // Handle null date_read by falling back to created_at
        $dateRead = $reading->getDateRead();
        if (is_null($dateRead)) {
            return $this->formatTimeAgo($reading->getCreatedAt());
        }

        $dateReadCarbon = Carbon::parse($dateRead);
        $createdAt = $reading->getCreatedAt();

        // If the reading was done today, use created_at for more accurate "hours/minutes ago"
        if ($dateReadCarbon->isToday()) {
            return $this->formatTimeAgo($createdAt);
        }

        // If the reading was done yesterday, always show "1 day ago" regardless of when logged
        if ($dateReadCarbon->isYesterday()) {
            return '1 day ago';
        }

        // For older readings, use date_read to show accurate day count
        return $this->formatTimeAgo($dateReadCarbon);
    }

    /**
     * Format time difference with proper labels.
     */
    public function formatTimeAgo(Carbon $date): string
    {
        $now = now();
        $diffInSeconds = (int) $date->diffInSeconds($now);
        $diffInMinutes = (int) $date->diffInMinutes($now);
        $diffInHours = (int) $date->diffInHours($now);
        $diffInDays = (int) $date->diffInDays($now);

        // Just now (less than 1 minute)
        if ($diffInSeconds < 60) {
            return 'just now';
        }

        // Minutes ago (1-59 minutes)
        if ($diffInMinutes < 60) {
            return $diffInMinutes === 1 ? '1 minute ago' : "{$diffInMinutes} minutes ago";
        }

        // Hours ago (1-23 hours, or same day but 24+ hours)
        if ($diffInHours < 24) {
            return $diffInHours === 1 ? '1 hour ago' : "{$diffInHours} hours ago";
        }

        // Days ago (1+ days)
        if ($diffInDays === 1) {
            return '1 day ago';
        } else {
            return "{$diffInDays} days ago";
        }
    }

    /**
     * Invalidate all cached statistics for a user.
     */
    public function invalidateUserCache(User $user): void
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;
        
        // Clear all user-specific caches
        Cache::forget("user_dashboard_stats_{$user->id}");
        Cache::forget("user_current_streak_{$user->id}");
        Cache::forget("user_longest_streak_{$user->id}");
        Cache::forget("user_calendar_{$user->id}_{$currentYear}");
        Cache::forget("user_calendar_{$user->id}_{$previousYear}");
    }

    /**
     * Invalidate specific cache keys for a user.
     */
    public function invalidateSpecificCache(User $user, array $cacheKeys): void
    {
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
