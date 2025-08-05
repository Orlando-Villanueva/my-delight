<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StreakStateService;
use Carbon\Carbon;

class StreakDemoController extends Controller
{
    public function __construct(
        private StreakStateService $streakStateService
    ) {}

    /**
     * Display demo page with all streak card states
     */
    public function index(Request $request)
    {
        // Define demo scenarios with different streak values and contexts
        $demoScenarios = [
            // Inactive State Examples
            [
                'title' => 'Inactive - No History',
                'currentStreak' => 0,
                'longestStreak' => 0,
                'hasReadToday' => false,
                'description' => 'New user with no reading history'
            ],
            [
                'title' => 'Inactive - With History', 
                'currentStreak' => 0,
                'longestStreak' => 15,
                'hasReadToday' => false,
                'description' => 'User who had a streak but lost it'
            ],
            
            // Active State Examples - Different Ranges
            [
                'title' => 'Active - Day 1',
                'currentStreak' => 1,
                'longestStreak' => 1,
                'hasReadToday' => true,
                'description' => 'First day of reading'
            ],
            [
                'title' => 'Active - Building Habit (Day 3)',
                'currentStreak' => 3,
                'longestStreak' => 5,
                'hasReadToday' => true,
                'description' => 'Building momentum (2-6 day range)'
            ],
            [
                'title' => 'Active - One Week Milestone (Day 7)',
                'currentStreak' => 7,
                'longestStreak' => 12,
                'hasReadToday' => false,
                'description' => 'MILESTONE DAY: One week achievement celebration'
            ],
            [
                'title' => 'Active - Building on Week (Day 10)',
                'currentStreak' => 10,
                'longestStreak' => 12,
                'hasReadToday' => true,
                'description' => 'Non-milestone: Building on week achievement'
            ],
            [
                'title' => 'Active - Two Weeks Milestone (Day 14)',
                'currentStreak' => 14,
                'longestStreak' => 20,
                'hasReadToday' => true,
                'description' => 'MILESTONE DAY: Two weeks achievement celebration'
            ],
            [
                'title' => 'Active - Building on Two Weeks (Day 20)',
                'currentStreak' => 20,
                'longestStreak' => 25,
                'hasReadToday' => true,
                'description' => 'Non-milestone: Building on two weeks achievement'
            ],
            [
                'title' => 'Active - One Month Milestone (Day 30)',
                'currentStreak' => 30,
                'longestStreak' => 45,
                'hasReadToday' => false,
                'description' => 'MILESTONE DAY: One month achievement celebration'
            ],
            [
                'title' => 'Active - Building on Month (Day 35)',
                'currentStreak' => 35,
                'longestStreak' => 45,
                'hasReadToday' => true,
                'description' => 'Non-milestone: Building on month achievement'
            ],
            [
                'title' => 'Active - Two Months Milestone (Day 60)',
                'currentStreak' => 60,
                'longestStreak' => 75,
                'hasReadToday' => true,
                'description' => 'MILESTONE DAY: Two months achievement celebration'
            ],
            [
                'title' => 'Active - Building on Two Months (Day 75)',
                'currentStreak' => 75,
                'longestStreak' => 85,
                'hasReadToday' => true,
                'description' => 'Non-milestone: Building on two months achievement'
            ],
            [
                'title' => 'Active - Three Months Milestone (Day 90)',
                'currentStreak' => 90,
                'longestStreak' => 100,
                'hasReadToday' => true,
                'description' => 'MILESTONE DAY: Three months achievement celebration'
            ],
            [
                'title' => 'Active - Building on Three Months (Day 105)',
                'currentStreak' => 105,
                'longestStreak' => 115,
                'hasReadToday' => true,
                'description' => 'Non-milestone: Building on three months achievement'
            ],
            [
                'title' => 'Active - Four Months (Day 120)',
                'currentStreak' => 120,
                'longestStreak' => 130,
                'hasReadToday' => true,
                'description' => 'Four month milestone (120-149 day range)'
            ],
            [
                'title' => 'Active - Five Months (Day 150)',
                'currentStreak' => 150,
                'longestStreak' => 160,
                'hasReadToday' => true,
                'description' => 'Five month milestone (150-179 day range)'
            ],
            [
                'title' => 'Active - Half Year (Day 180)',
                'currentStreak' => 180,
                'longestStreak' => 200,
                'hasReadToday' => true,
                'description' => 'Six month milestone (180-364 day range)'
            ],
            [
                'title' => 'Active - One Year+ (Day 365)',
                'currentStreak' => 365,
                'longestStreak' => 365,
                'hasReadToday' => true,
                'description' => 'One year milestone (365+ day range) - legendary achievement!'
            ],
            
            // Warning State Examples
            [
                'title' => 'Warning - Day 5 (Evening)',
                'currentStreak' => 5,
                'longestStreak' => 8,
                'hasReadToday' => false,
                'description' => 'Has not read today, after 6 PM',
                'forceWarning' => true
            ],
            [
                'title' => 'Warning - Day 20 (Evening)',
                'currentStreak' => 20,
                'longestStreak' => 25,
                'hasReadToday' => false,
                'description' => 'Long streak at risk, after 6 PM',
                'forceWarning' => true
            ],
            
            // Acknowledgment Examples
            [
                'title' => 'Acknowledgment - Day 10',
                'currentStreak' => 10,
                'longestStreak' => 15,
                'hasReadToday' => true,
                'description' => 'User completed reading today - acknowledgment message',
                'forceAcknowledgment' => true
            ]
        ];

        // Generate streak card data for each scenario
        $streakCards = [];
        foreach ($demoScenarios as $scenario) {
            // Determine time context for warning state
            if (isset($scenario['forceWarning']) && $scenario['forceWarning']) {
                $currentTime = Carbon::today()->setHour(20); // 8 PM
            } else {
                $currentTime = Carbon::today()->setHour(14); // 2 PM (before warning time)
            }

            // Determine streak state
            $streakState = $this->streakStateService->determineStreakState(
                $scenario['currentStreak'],
                $scenario['hasReadToday'],
                $currentTime
            );

            // Get state classes
            $stateClasses = $this->streakStateService->getStateClasses($streakState);

            // Get contextual message - force hasReadToday=false for demo to avoid random acknowledgments
            // except for the explicit acknowledgment scenario
            $demoHasReadToday = isset($scenario['forceAcknowledgment']) ? $scenario['hasReadToday'] : false;
            
            $streakMessage = $this->streakStateService->selectMessage(
                $scenario['currentStreak'],
                $streakState,
                $scenario['longestStreak'],
                $demoHasReadToday
            );

            // Override for acknowledgment demo if needed
            if (isset($scenario['forceAcknowledgment']) && $scenario['forceAcknowledgment']) {
                $acknowledgeMessages = [
                    'Well done! You\'ve read today!',
                    'Great job staying consistent!',
                    'Your streak is safe for today!',
                    'Another day of progress!'
                ];
                $streakMessage = $acknowledgeMessages[0]; // Use first message for demo
            }

            $streakCards[] = [
                'title' => $scenario['title'],
                'description' => $scenario['description'],
                'currentStreak' => $scenario['currentStreak'],
                'longestStreak' => $scenario['longestStreak'],
                'stateClasses' => $stateClasses,
                'message' => $streakMessage,
                'state' => $streakState,
                'hasReadToday' => $scenario['hasReadToday'],
                'timeContext' => isset($scenario['forceWarning']) ? 'Evening (8 PM)' : 'Afternoon (2 PM)'
            ];
        }

        return view('demo.streak-cards', compact('streakCards'));
    }
}