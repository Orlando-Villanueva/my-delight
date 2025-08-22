<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

class WeeklyGoalService
{
    /**
     * Default weekly reading goal (4 days per week).
     */
    private const int DEFAULT_WEEKLY_GOAL = 4;
    private const int FIRST_DAY_OF_WEEK = Carbon::SUNDAY;
    private const int LAST_DAY_OF_WEEK = Carbon::SATURDAY;
    private const int MAX_WEEKS_TO_CHECK = 52;

    public function __construct()
    {
        // Self-contained service with no dependencies
    }

    /**
     * Generate consistent cache keys for weekly goal related data
     */
    private function getCacheKey(string $type, User $user, string $suffix = ''): string
    {
        $baseKey = "weekly_goal_{$type}_{$user->id}";
        return $suffix ? "{$baseKey}_{$suffix}" : $baseKey;
    }


    /**
     * Get complete weekly goal data structure for a user.
     */
    public function getWeeklyGoalData(User $user): array
    {
        if (!$user || !$user->id) {
            throw new InvalidArgumentException('Valid user with ID required');
        }
        
        try {
            $weekStart = now()->startOfWeek(self::FIRST_DAY_OF_WEEK);
            $currentProgress = $this->calculateWeekProgress($user, now());
            $weeklyTarget = self::DEFAULT_WEEKLY_GOAL;
            
            return [
                'current_progress' => $currentProgress,
                'weekly_target' => $weeklyTarget,
                'week_start' => $weekStart->toDateString(),
                'week_end' => $weekStart->copy()->endOfWeek(self::LAST_DAY_OF_WEEK)->toDateString(),
                'is_goal_achieved' => $currentProgress >= $weeklyTarget,
                'progress_percentage' => $weeklyTarget > 0 ? round(($currentProgress / $weeklyTarget) * 100, 2) : 0,
                'message' => $this->getProgressMessage($currentProgress, $weeklyTarget),
            ];
        } catch (Exception $e) {
            Log::error('Error getting weekly goal data', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->getDefaultWeeklyGoalData();
        }
    }

    /**
     * Calculate reading progress for a specific week.
     * Returns the number of distinct days with readings in the specified week.
     */
    public function calculateWeekProgress(User $user, Carbon $referenceDate): int
    {
        try {
            $weekStart = $referenceDate->copy()->startOfWeek(self::FIRST_DAY_OF_WEEK);
            $weekEnd = $referenceDate->copy()->endOfWeek(self::LAST_DAY_OF_WEEK);
            
            // Use efficient database-level distinct count instead of collection manipulation
            return $user->readingLogs()
                ->whereBetween('date_read', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->distinct('date_read')
                ->count('date_read');
        } catch (Exception $e) {
            Log::error('Error calculating week progress', [
                'user_id' => $user->id,
                'reference_date' => $referenceDate->toDateString(),
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Get reading days count for current week (Sunday to Saturday).
     * Convenience method that uses calculateWeekProgress with current date.
     */
    public function getThisWeekReadingDays(User $user): int
    {
        return $this->calculateWeekProgress($user, now());
    }

    /**
     * Get a motivational message based on progress.
     */
    private function getProgressMessage(int $currentProgress, int $weeklyTarget): string
    {
        if ($currentProgress >= $weeklyTarget) {
            return 'Great job! You\'ve achieved your weekly goal!';
        } elseif ($currentProgress > 0) {
            $remaining = $weeklyTarget - $currentProgress;
            return "You're making progress! {$remaining} more day" . ($remaining === 1 ? '' : 's') . " to reach your goal.";
        } else {
            return 'Start your week strong with your first reading!';
        }
    }


    /**
     * Calculate the current weekly streak for a user.
ma     * Counts consecutive weeks with achieved goals (4+ days) working backwards from current week (if goal achieved) or most recent completed week.
     */
    public function calculateWeeklyStreak(User $user): int
    {
        if (!$user || !$user->id) {
            throw new InvalidArgumentException('Valid user with ID required');
        }
        
        try {
            $currentWeekStart = now()->startOfWeek(self::FIRST_DAY_OF_WEEK);
            $streakCount = 0;
            $maxWeeksToCheck = self::MAX_WEEKS_TO_CHECK;
            
            // Check if current week goal is achieved
            $currentWeekProgress = $this->calculateWeekProgress($user, now());
            $currentWeekAchieved = $currentWeekProgress >= self::DEFAULT_WEEKLY_GOAL;
            
            // If current week goal is achieved, include it in the streak
            if ($currentWeekAchieved) {
                $streakCount = 1;
            }
            
            // Get weekly data for the specified range (excluding current week)
            $weeklyData = $this->getWeeklyDataWithDateRange($user, $maxWeeksToCheck, false);
            
            // Check each completed week backwards
            foreach ($weeklyData as $weekData) {
                if ($weekData['is_goal_achieved']) {
                    $streakCount++;
                } else {
                    // Smart break detection - stop immediately when week with <4 days is found
                    break;
                }
            }
            
            return $streakCount;
        } catch (QueryException $e) {
            Log::error('Database error calculating weekly streak', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'sql_state' => $e->getCode()
            ]);
            
            return 0;
        } catch (Exception $e) {
            Log::error('Unexpected error calculating weekly streak', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'type' => get_class($e)
            ]);
            
            return 0;
        }
    }

    /**
     * Check if a user achieved their weekly goal for a specific week.
     * Returns true if the user read on 4 or more days during the specified week.
     */
    public function isWeekGoalAchieved(User $user, Carbon $weekStart): bool
    {
        try {
            $daysRead = $this->calculateWeekProgress($user, $weekStart);
            return $daysRead >= self::DEFAULT_WEEKLY_GOAL;
        } catch (Exception $e) {
            Log::error('Error checking if week goal is achieved', [
                'user_id' => $user->id,
                'week_start' => $weekStart->toDateString(),
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get complete weekly streak data structure for a user.
     * Returns streak count, status, and motivational messaging.
     */
    public function getWeeklyStreakData(User $user): array
    {
        try {
            $streakCount = $this->calculateWeeklyStreak($user);
            $isActive = $streakCount > 0;
            
            // Find the last achieved week start date
            $lastAchievedWeek = null;
            if ($isActive) {
                $currentWeekStart = now()->startOfWeek(self::FIRST_DAY_OF_WEEK);
                $lastAchievedWeek = $currentWeekStart->copy()->subWeek()->toDateString();
            }
            
            return [
                'streak_count' => $streakCount,
                'is_active' => $isActive,
                'last_achieved_week' => $lastAchievedWeek,
                'message' => $this->getStreakMessage($streakCount),
            ];
        } catch (Exception $e) {
            Log::error('Error getting weekly streak data', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->getDefaultWeeklyStreakData();
        }
    }

    /**
     * Get weekly data for a specified date range with optimized queries.
     * Returns array of weeks with their reading progress and goal achievement status.
     */
    private function getWeeklyDataWithDateRange(User $user, int $weeksBack, bool $includeCurrentWeek = true): array
    {
        try {
            $currentWeekStart = now()->startOfWeek(self::FIRST_DAY_OF_WEEK);
            
            if ($includeCurrentWeek) {
                // Start from current week
                $endDate = $currentWeekStart->copy();
            } else {
                // Start from most recent completed week
                $endDate = $currentWeekStart->copy()->subWeek();
            }
            
            $startDate = $endDate->copy()->subWeeks($weeksBack - 1);
            
            $weeklyData = [];
            $currentDate = $endDate->copy();
            
            // Process each week backwards and calculate days read for each week
            for ($i = 0; $i < $weeksBack; $i++) {
                $weekStart = $currentDate->copy();
                $weekEnd = $currentDate->copy()->endOfWeek(self::LAST_DAY_OF_WEEK);
                
                // Calculate days read for this specific week using existing method
                $daysRead = $this->calculateWeekProgress($user, $weekStart);
                
                $weeklyData[] = [
                    'week_start' => $weekStart->toDateString(),
                    'week_end' => $weekEnd->toDateString(),
                    'days_read' => $daysRead,
                    'is_goal_achieved' => $daysRead >= self::DEFAULT_WEEKLY_GOAL,
                ];
                
                $currentDate->subWeek();
            }
            
            return $weeklyData;
        } catch (Exception $e) {
            Log::error('Error getting weekly data with date range', [
                'user_id' => $user->id,
                'weeks_back' => $weeksBack,
                'include_current_week' => $includeCurrentWeek,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Get motivational message based on weekly streak count.
     */
    private function getStreakMessage(int $streakCount): string
    {
        if ($streakCount === 0) {
            return 'Start your first weekly streak!';
        } elseif ($streakCount === 1) {
            return 'Great start! Keep the momentum going.';
        } elseif ($streakCount >= 2 && $streakCount <= 3) {
            return "Building consistency! {$streakCount} weeks in a row.";
        } else {
            return "Amazing consistency! {$streakCount} weeks strong!";
        }
    }

    /**
     * Get default weekly streak data for error fallback.
     */
    private function getDefaultWeeklyStreakData(): array
    {
        return [
            'streak_count' => 0,
            'is_active' => false,
            'last_achieved_week' => null,
            'message' => 'Start your first weekly streak!',
        ];
    }

    /**
     * Get default weekly goal data for error fallback.
     */
    private function getDefaultWeeklyGoalData(): array
    {
        $weekStart = now()->startOfWeek(self::FIRST_DAY_OF_WEEK);
        $weeklyTarget = self::DEFAULT_WEEKLY_GOAL;
        
        return [
            'current_progress' => 0,
            'weekly_target' => $weeklyTarget,
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekStart->copy()->endOfWeek(self::LAST_DAY_OF_WEEK)->toDateString(),
            'is_goal_achieved' => false,
            'progress_percentage' => 0,
            'message' => 'Start your week strong with your first reading!',
        ];
    }

}