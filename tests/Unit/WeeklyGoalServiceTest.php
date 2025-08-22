<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\ReadingLog;
use App\Services\WeeklyGoalService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeeklyGoalServiceTest extends TestCase
{
    use RefreshDatabase;

    private WeeklyGoalService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(WeeklyGoalService::class);
        $this->user = User::factory()->create();
    }

    public function test_get_weekly_goal_data_returns_correct_structure()
    {
        $data = $this->service->getWeeklyGoalData($this->user);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('current_progress', $data);
        $this->assertArrayHasKey('weekly_target', $data);
        $this->assertArrayHasKey('week_start', $data);
        $this->assertArrayHasKey('week_end', $data);
        $this->assertArrayHasKey('is_goal_achieved', $data);
        $this->assertArrayHasKey('progress_percentage', $data);
        $this->assertArrayHasKey('message', $data);

        $this->assertEquals(4, $data['weekly_target']);
        $this->assertIsInt($data['current_progress']);
        $this->assertIsBool($data['is_goal_achieved']);
        $this->assertIsNumeric($data['progress_percentage']);
        $this->assertIsString($data['message']);
    }

    public function test_calculate_week_progress_with_no_readings()
    {
        $progress = $this->service->calculateWeekProgress($this->user, now());
        $this->assertEquals(0, $progress);
    }

    public function test_calculate_week_progress_with_readings_this_week()
    {
        // Create readings for different days this week
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->toDateString(), // Sunday
        ]);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->copy()->addDay()->toDateString(), // Monday
        ]);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->copy()->addDays(2)->toDateString(), // Tuesday
        ]);

        $progress = $this->service->calculateWeekProgress($this->user, now());
        $this->assertEquals(3, $progress);
    }

    public function test_calculate_week_progress_with_multiple_readings_same_day()
    {
        // Create multiple readings on the same day - should only count as 1 day
        $today = now()->toDateString();
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $today,
            'book_id' => 1,
            'chapter' => 1,
        ]);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $today,
            'book_id' => 1,
            'chapter' => 2,
        ]);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $today,
            'book_id' => 2,
            'chapter' => 1,
        ]);

        $progress = $this->service->calculateWeekProgress($this->user, now());
        $this->assertEquals(1, $progress);
    }

    public function test_calculate_week_progress_excludes_readings_from_other_weeks()
    {
        $thisWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        $lastWeekStart = $thisWeekStart->copy()->subWeek();
        $nextWeekStart = $thisWeekStart->copy()->addWeek();
        
        // Reading from last week
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $lastWeekStart->toDateString(),
        ]);
        
        // Reading from this week
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $thisWeekStart->toDateString(),
        ]);
        
        // Reading from next week
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $nextWeekStart->toDateString(),
        ]);

        $progress = $this->service->calculateWeekProgress($this->user, now());
        $this->assertEquals(1, $progress);
    }

    public function test_get_this_week_reading_days()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->toDateString(),
        ]);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->copy()->addDays(3)->toDateString(),
        ]);

        $days = $this->service->getThisWeekReadingDays($this->user);
        $this->assertEquals(2, $days);
    }

    public function test_is_week_goal_achieved_with_sufficient_readings()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create 4 readings on different days
        for ($i = 0; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $weekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $achieved = $this->service->isWeekGoalAchieved($this->user, $weekStart);
        $this->assertTrue($achieved);
    }

    public function test_is_week_goal_achieved_with_insufficient_readings()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create only 3 readings on different days
        for ($i = 0; $i < 3; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $weekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $achieved = $this->service->isWeekGoalAchieved($this->user, $weekStart);
        $this->assertFalse($achieved);
    }

    public function test_calculate_weekly_streak_with_no_achieved_weeks()
    {
        // Create some readings but not enough to achieve weekly goal
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->copy()->subWeek()->toDateString(),
        ]);

        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(0, $streak);
    }

    public function test_calculate_weekly_streak_with_single_achieved_week()
    {
        $lastWeekStart = now()->startOfWeek(Carbon::SUNDAY)->subWeek();
        
        // Create 4 readings in last week to achieve goal
        for ($i = 0; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $lastWeekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(1, $streak);
    }

    public function test_calculate_weekly_streak_with_consecutive_achieved_weeks()
    {
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create 4 readings each for the last 3 weeks
        for ($weekOffset = 1; $weekOffset <= 3; $weekOffset++) {
            $weekStart = $currentWeekStart->copy()->subWeeks($weekOffset);
            
            for ($day = 0; $day < 4; $day++) {
                ReadingLog::factory()->create([
                    'user_id' => $this->user->id,
                    'date_read' => $weekStart->copy()->addDays($day)->toDateString(),
                ]);
            }
        }

        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(3, $streak);
    }

    public function test_calculate_weekly_streak_stops_at_first_unachieved_week()
    {
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Week 1 ago: achieved (4 readings)
        $week1 = $currentWeekStart->copy()->subWeek();
        for ($day = 0; $day < 4; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $week1->copy()->addDays($day)->toDateString(),
            ]);
        }
        
        // Week 2 ago: not achieved (only 2 readings)
        $week2 = $currentWeekStart->copy()->subWeeks(2);
        for ($day = 0; $day < 2; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $week2->copy()->addDays($day)->toDateString(),
            ]);
        }
        
        // Week 3 ago: achieved (4 readings)
        $week3 = $currentWeekStart->copy()->subWeeks(3);
        for ($day = 0; $day < 4; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $week3->copy()->addDays($day)->toDateString(),
            ]);
        }

        // Should only count week 1, stop at week 2 (unachieved)
        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(1, $streak);
    }

    public function test_get_weekly_streak_data_returns_correct_structure()
    {
        $data = $this->service->getWeeklyStreakData($this->user);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('streak_count', $data);
        $this->assertArrayHasKey('is_active', $data);
        $this->assertArrayHasKey('last_achieved_week', $data);
        $this->assertArrayHasKey('message', $data);

        $this->assertIsInt($data['streak_count']);
        $this->assertIsBool($data['is_active']);
        $this->assertIsString($data['message']);
    }

    public function test_get_weekly_streak_data_with_active_streak()
    {
        $lastWeekStart = now()->startOfWeek(Carbon::SUNDAY)->subWeek();
        
        // Create 4 readings in last week to achieve goal
        for ($i = 0; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $lastWeekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $data = $this->service->getWeeklyStreakData($this->user);

        $this->assertEquals(1, $data['streak_count']);
        $this->assertTrue($data['is_active']);
        $this->assertEquals($lastWeekStart->toDateString(), $data['last_achieved_week']);
        $this->assertStringContainsString('Great start', $data['message']);
    }

    public function test_get_weekly_streak_data_with_no_streak()
    {
        $data = $this->service->getWeeklyStreakData($this->user);

        $this->assertEquals(0, $data['streak_count']);
        $this->assertFalse($data['is_active']);
        $this->assertNull($data['last_achieved_week']);
        $this->assertStringContainsString('Start your first', $data['message']);
    }

    public function test_weekly_goal_progress_percentage_calculation()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create 2 readings (50% of 4-day goal)
        for ($i = 0; $i < 2; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $weekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $data = $this->service->getWeeklyGoalData($this->user);
        
        $this->assertEquals(2, $data['current_progress']);
        $this->assertEquals(50.0, $data['progress_percentage']);
        $this->assertFalse($data['is_goal_achieved']);
    }

    public function test_weekly_goal_achieved_status()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create exactly 4 readings (100% of goal)
        for ($i = 0; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $weekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $data = $this->service->getWeeklyGoalData($this->user);
        
        $this->assertEquals(4, $data['current_progress']);
        $this->assertEquals(100.0, $data['progress_percentage']);
        $this->assertTrue($data['is_goal_achieved']);
        $this->assertStringContainsString('achieved', $data['message']);
    }

    public function test_week_boundaries_sunday_to_saturday()
    {
        // Test that week correctly starts on Sunday and ends on Saturday
        $data = $this->service->getWeeklyGoalData($this->user);
        
        $weekStart = Carbon::parse($data['week_start']);
        $weekEnd = Carbon::parse($data['week_end']);
        
        $this->assertEquals(Carbon::SUNDAY, $weekStart->dayOfWeek);
        $this->assertEquals(Carbon::SATURDAY, $weekEnd->dayOfWeek);
        $this->assertEquals(6, $weekStart->diffInDays($weekEnd));
    }

    public function test_calculate_week_progress_with_specific_reference_date()
    {
        // Test with a specific date in the past
        $referenceDate = Carbon::create(2025, 1, 15); // A Wednesday
        $weekStart = $referenceDate->copy()->startOfWeek(Carbon::SUNDAY); // Sunday of that week
        
        // Create readings for that specific week
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->toDateString(),
        ]);
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $weekStart->copy()->addDays(2)->toDateString(),
        ]);

        $progress = $this->service->calculateWeekProgress($this->user, $referenceDate);
        $this->assertEquals(2, $progress);
    }

    public function test_weekly_streak_includes_current_week_if_goal_achieved()
    {
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create 3 readings in current week (goal not achieved)
        for ($day = 0; $day < 3; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $currentWeekStart->copy()->addDays($day)->toDateString(),
            ]);
        }
        
        // Current week should NOT count toward streak if goal is not achieved
        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(0, $streak);
    }

    public function test_weekly_streak_includes_current_week_when_goal_achieved()
    {
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create 4 readings in current week (goal achieved)
        for ($day = 0; $day < 4; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $currentWeekStart->copy()->addDays($day)->toDateString(),
            ]);
        }
        
        // Current week SHOULD count toward streak when goal is achieved
        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(1, $streak);
    }

    public function test_weekly_streak_with_year_boundary()
    {
        // Test streak calculation across year boundary
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create achieved week in current year (1 week ago)
        $currentYearWeek = $currentWeekStart->copy()->subWeek();
        for ($day = 0; $day < 4; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $currentYearWeek->copy()->addDays($day)->toDateString(),
            ]);
        }
        
        // Create achieved week spanning year boundary (2 weeks ago)
        $previousYearWeek = $currentWeekStart->copy()->subWeeks(2);
        for ($day = 0; $day < 4; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $previousYearWeek->copy()->addDays($day)->toDateString(),
            ]);
        }

        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(2, $streak);
    }

    public function test_weekly_goal_messages_change_based_on_progress()
    {
        // Test message when no progress
        $data = $this->service->getWeeklyGoalData($this->user);
        $this->assertStringContainsString('Start your week', $data['message']);
        
        // Test message when partial progress
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => now()->startOfWeek(Carbon::SUNDAY)->toDateString(),
        ]);
        
        $data = $this->service->getWeeklyGoalData($this->user);
        $this->assertStringContainsString('more day', $data['message']);
        
        // Test message when goal achieved
        for ($i = 1; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => now()->startOfWeek(Carbon::SUNDAY)->addDays($i)->toDateString(),
            ]);
        }
        
        $data = $this->service->getWeeklyGoalData($this->user);
        $this->assertStringContainsString('achieved', $data['message']);
    }

    public function test_weekly_streak_messages_change_based_on_count()
    {
        // Test message for no streak
        $data = $this->service->getWeeklyStreakData($this->user);
        $this->assertStringContainsString('Start your first', $data['message']);
        
        // Test message for 1 week streak
        $lastWeekStart = now()->startOfWeek(Carbon::SUNDAY)->subWeek();
        for ($i = 0; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $lastWeekStart->copy()->addDays($i)->toDateString(),
            ]);
        }
        
        $data = $this->service->getWeeklyStreakData($this->user);
        $this->assertStringContainsString('Great start', $data['message']);
        
        // Test message for multiple weeks
        $twoWeeksAgo = $lastWeekStart->copy()->subWeek();
        for ($i = 0; $i < 4; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $twoWeeksAgo->copy()->addDays($i)->toDateString(),
            ]);
        }
        
        $data = $this->service->getWeeklyStreakData($this->user);
        $this->assertStringContainsString('2 weeks', $data['message']);
    }


    public function test_edge_case_exactly_sunday_midnight()
    {
        // Test week boundary calculation at exactly Sunday midnight (start of new week)
        $sunday = now()->next(Carbon::SUNDAY)->startOfDay();
        
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'date_read' => $sunday->toDateString(),
        ]);

        $progress = $this->service->calculateWeekProgress($this->user, $sunday);
        $this->assertEquals(1, $progress);
    }

    public function test_large_weekly_streak_calculation()
    {
        // Test performance and correctness with a large streak (20 weeks)
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        for ($weekOffset = 1; $weekOffset <= 20; $weekOffset++) {
            $weekStart = $currentWeekStart->copy()->subWeeks($weekOffset);
            
            for ($day = 0; $day < 4; $day++) {
                ReadingLog::factory()->create([
                    'user_id' => $this->user->id,
                    'date_read' => $weekStart->copy()->addDays($day)->toDateString(),
                ]);
            }
        }

        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(20, $streak);
    }

    public function test_weekly_streak_with_gap_in_middle()
    {
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create achieved weeks 1 and 2 ago
        for ($weekOffset = 1; $weekOffset <= 2; $weekOffset++) {
            $weekStart = $currentWeekStart->copy()->subWeeks($weekOffset);
            
            for ($day = 0; $day < 4; $day++) {
                ReadingLog::factory()->create([
                    'user_id' => $this->user->id,
                    'date_read' => $weekStart->copy()->addDays($day)->toDateString(),
                ]);
            }
        }
        
        // Skip week 3 (no readings)
        
        // Create achieved week 4 ago
        $week4Start = $currentWeekStart->copy()->subWeeks(4);
        for ($day = 0; $day < 4; $day++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $week4Start->copy()->addDays($day)->toDateString(),
            ]);
        }

        // Should only count the consecutive weeks (1 and 2 ago), stop at gap
        $streak = $this->service->calculateWeeklyStreak($this->user);
        $this->assertEquals(2, $streak);
    }

    public function test_progress_percentage_over_100_percent()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        
        // Create 6 readings (150% of 4-day goal)
        for ($i = 0; $i < 6; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'date_read' => $weekStart->copy()->addDays($i)->toDateString(),
            ]);
        }

        $data = $this->service->getWeeklyGoalData($this->user);
        
        $this->assertEquals(6, $data['current_progress']);
        $this->assertEquals(150.0, $data['progress_percentage']);
        $this->assertTrue($data['is_goal_achieved']);
    }
}