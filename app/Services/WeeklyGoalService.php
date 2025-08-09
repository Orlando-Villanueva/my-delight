<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class WeeklyGoalService
{
    /**
     * Default weekly reading goal (4 days per week).
     */
    private const int DEFAULT_WEEKLY_GOAL = 4;
    private const int FIRST_DAY_OF_WEEK = Carbon::SUNDAY;
    private const int LAST_DAY_OF_WEEK = Carbon::SATURDAY;

    public function __construct()
    {
        // Constructor ready for future dependencies if needed
    }


    /**
     * Get complete weekly goal data structure for a user.
     */
    public function getWeeklyGoalData(User $user): array
    {
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
            
            // Get distinct days with readings in the specified week using Eloquent
            return $user->readingLogs()
                ->whereBetween('date_read', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->get()
                ->pluck('date_read')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->unique()
                ->count();
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