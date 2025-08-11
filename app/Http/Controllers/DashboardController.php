<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReadingFormService;
use App\Services\UserStatisticsService;
use App\Services\StreakStateService;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct(
        private ReadingFormService $readingFormService,
        private UserStatisticsService $statisticsService,
        private StreakStateService $streakStateService
    ) {}

    /**
     * Display the dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get reading status for today
        $hasReadToday = $this->readingFormService->hasReadToday($user);
        
        // Get dashboard statistics
        $stats = $this->statisticsService->getDashboardStatistics($user);

        // Extract weekly goal data for easier access in views
        $weeklyGoal = $stats['weekly_goal'];

        // Compute streak state and classes for the component
        $streakState = $this->streakStateService->determineStreakState(
            $stats['streaks']['current_streak'],
            $hasReadToday
        );
        $streakStateClasses = $this->streakStateService->getStateClasses($streakState);

        // Get contextual message for the streak counter
        $streakMessage = $this->streakStateService->selectMessage(
            $stats['streaks']['current_streak'],
            $streakState,
            $stats['streaks']['longest_streak'],
            $hasReadToday
        );

        // Return partial for HTMX navigation, full page for direct access
        if ($request->header('HX-Request')) {
            return view('partials.dashboard-page', compact('hasReadToday', 'streakState', 'streakStateClasses', 'streakMessage', 'stats', 'weeklyGoal'));
        }

        // Return full page for direct access (browser URL)
        return view('dashboard', compact('hasReadToday', 'streakState', 'streakStateClasses', 'streakMessage', 'stats', 'weeklyGoal'));
    }
}
