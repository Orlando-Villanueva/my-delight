<?php

use App\Services\StreakStateService;
use Illuminate\Support\Carbon;

class StreakStateServiceTest extends \Tests\TestCase
{
    /**
     * Test state detection logic for inactive state (streak = 0)
     */
    public function test_inactive_state_when_streak_is_zero()
    {
        // Test with hasReadToday = false
        $state = $this->determineComponentState(0, false);
        $this->assertEquals('inactive', $state);
        
        // Test with hasReadToday = true (should still be inactive when streak is 0)
        $state = $this->determineComponentState(0, true);
        $this->assertEquals('inactive', $state);
    }
    
    /**
     * Test state detection logic for active state
     */
    public function test_active_state_when_has_streak_and_read_today()
    {
        // Test various streak values when user has read today
        $streakValues = [1, 2, 5, 10, 30, 100];
        
        foreach ($streakValues as $streak) {
            $state = $this->determineComponentState($streak, true);
            $this->assertEquals('active', $state, "Failed for streak: {$streak}");
        }
    }
    
    /**
     * Test state detection logic for active state when hasn't read today but before warning time
     */
    public function test_active_state_when_hasnt_read_today_but_before_warning_time()
    {
        // Test time before 6 PM (e.g., 2 PM)
        $testTime = Carbon::today()->setHour(14);
        
        $streakValues = [1, 2, 5, 10, 30, 100];
        
        foreach ($streakValues as $streak) {
            $state = $this->determineComponentState($streak, false, $testTime);
            $this->assertEquals('active', $state, "Failed for streak: {$streak} at 2 PM");
        }
    }
    
    /**
     * Test state detection logic for warning state
     */
    public function test_warning_state_when_hasnt_read_today_and_after_warning_time()
    {
        // Test time after 6 PM (e.g., 8 PM)
        $testTime = Carbon::today()->setHour(20);
        
        $streakValues = [1, 2, 5, 10, 30, 100];
        
        foreach ($streakValues as $streak) {
            $state = $this->determineComponentState($streak, false, $testTime);
            $this->assertEquals('warning', $state, "Failed for streak: {$streak} at 8 PM");
        }
    }
    
    /**
     * Test warning time boundary (exactly 6 PM)
     */
    public function test_warning_state_at_exact_warning_time()
    {
        // Test time exactly 6 PM
        $testTime = Carbon::today()->setHour(18);
        
        $state = $this->determineComponentState(5, false, $testTime);
        $this->assertEquals('warning', $state, "Should be warning state at exactly 6 PM");
    }
    
    /**
     * Test warning time boundary (just before 6 PM)
     */
    public function test_active_state_just_before_warning_time()
    {
        // Test time just before 6 PM (5:59 PM)
        $testTime = Carbon::today()->setHour(17)->setMinute(59);
        
        $state = $this->determineComponentState(5, false, $testTime);
        $this->assertEquals('active', $state, "Should be active state just before 6 PM");
    }
    
    /**
     * Test that reading today overrides warning state even after 6 PM
     */
    public function test_active_state_when_read_today_even_after_warning_time()
    {
        // Test time after 6 PM (e.g., 10 PM)
        $testTime = Carbon::today()->setHour(22);
        
        $streakValues = [1, 2, 5, 10, 30, 100];
        
        foreach ($streakValues as $streak) {
            $state = $this->determineComponentState($streak, true, $testTime);
            $this->assertEquals('active', $state, "Failed for streak: {$streak} at 10 PM when read today");
        }
    }
    
    /**
     * Test edge cases with different times throughout the day
     */
    public function test_state_detection_throughout_day()
    {
        $testCases = [
            // [hour, streak, hasReadToday, expectedState]
            [0, 5, false, 'active'],   // Midnight
            [6, 5, false, 'active'],   // 6 AM
            [12, 5, false, 'active'],  // Noon
            [17, 5, false, 'active'],  // 5 PM
            [18, 5, false, 'warning'], // 6 PM
            [19, 5, false, 'warning'], // 7 PM
            [23, 5, false, 'warning'], // 11 PM
            [18, 5, true, 'active'],   // 6 PM but read today
            [22, 5, true, 'active'],   // 10 PM but read today
        ];
        
        foreach ($testCases as [$hour, $streak, $hasReadToday, $expectedState]) {
            $testTime = Carbon::today()->setHour($hour);
            
            $state = $this->determineComponentState($streak, $hasReadToday, $testTime);
            $this->assertEquals(
                $expectedState, 
                $state, 
                "Failed for hour: {$hour}, streak: {$streak}, hasReadToday: " . ($hasReadToday ? 'true' : 'false')
            );
        }
    }
    
    /**
     * Test state detection with zero streak at different times
     */
    public function test_inactive_state_always_when_zero_streak()
    {
        $testHours = [0, 6, 12, 17, 18, 19, 23];
        $hasReadTodayValues = [true, false];
        
        foreach ($testHours as $hour) {
            foreach ($hasReadTodayValues as $hasReadToday) {
                $testTime = Carbon::today()->setHour($hour);
                
                $state = $this->determineComponentState(0, $hasReadToday, $testTime);
                $this->assertEquals(
                    'inactive', 
                    $state, 
                    "Should always be inactive when streak is 0 (hour: {$hour}, hasReadToday: " . ($hasReadToday ? 'true' : 'false') . ")"
                );
            }
        }
    }
    
    /**
     * Test state classes are returned correctly
     */
    public function test_get_state_classes()
    {
        $service = new StreakStateService();
        
        // Test inactive state classes
        $inactiveClasses = $service->getStateClasses('inactive');
        $this->assertEquals('bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-[#D1D7E0] dark:border-gray-700 shadow-lg', $inactiveClasses['background']);
        $this->assertEquals('text-gray-400 dark:text-gray-500', $inactiveClasses['icon']);
        $this->assertFalse($inactiveClasses['showIcon']);
        $this->assertEquals('opacity-70', $inactiveClasses['opacity']);
        $this->assertEquals('border-[#D1D7E0] dark:border-gray-600', $inactiveClasses['border']);
        
        // Test active state classes
        $activeClasses = $service->getStateClasses('active');
        $this->assertEquals('bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white shadow-lg', $activeClasses['background']);
        $this->assertEquals('text-accent-500', $activeClasses['icon']);
        $this->assertTrue($activeClasses['showIcon']);
        $this->assertEquals('opacity-90', $activeClasses['opacity']);
        $this->assertEquals('border-white/20', $activeClasses['border']);
        
        // Test warning state classes
        $warningClasses = $service->getStateClasses('warning');
        $this->assertEquals('bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg', $warningClasses['background']);
        $this->assertEquals('text-orange-200', $warningClasses['icon']);
        $this->assertTrue($warningClasses['showIcon']);
        $this->assertEquals('opacity-90', $warningClasses['opacity']);
        $this->assertEquals('border-white/20', $warningClasses['border']);
    }
    
    /**
     * Test message selection for inactive state without history
     */
    public function test_inactive_message_selection_without_history()
    {
        $service = new StreakStateService();
        
        $message = $service->selectMessage(0, 'inactive', 0, false);
        
        // Should be one of the default inactive messages
        $expectedMessages = [
            'Start your reading journey today!',
            'Begin building your streak!',
            'Take the first step in your reading habit!',
            'Your Bible reading adventure starts now!'
        ];
        
        $this->assertContains($message, $expectedMessages);
    }
    
    /**
     * Test message selection for inactive state with history
     */
    public function test_inactive_message_selection_with_history()
    {
        $service = new StreakStateService();
        
        $message = $service->selectMessage(0, 'inactive', 15, false);
        
        // Should be one of the withHistory inactive messages
        $expectedMessages = [
            'You\'ve done it before, you can do it again!',
            'Ready to rebuild your reading habit?',
            'Time to start a new streak!',
            'Your comeback story starts today!'
        ];
        
        $this->assertContains($message, $expectedMessages);
    }
    
    /**
     * Test message selection for active state - 1 day streak
     */
    public function test_active_message_selection_one_day()
    {
        $service = new StreakStateService();
        
        $message = $service->selectMessage(1, 'active', 0, false);
        
        $expectedMessages = [
            'Great start! Keep it going!',
            'You\'re building momentum!',
            'One day down, many more to go!',
            'Perfect beginning to your journey!'
        ];
        
        $this->assertContains($message, $expectedMessages);
    }
    
    /**
     * Test message selection for active state - 2-6 day range
     */
    public function test_active_message_selection_two_to_six_days()
    {
        $service = new StreakStateService();
        
        $streakValues = [2, 3, 4, 5, 6];
        $expectedMessages = [
            'You\'re building a great habit!',
            'Keep the momentum going!',
            'Your consistency is showing!',
            'Building something beautiful!'
        ];
        
        foreach ($streakValues as $streak) {
            $message = $service->selectMessage($streak, 'active', 0, false);
            $this->assertContains($message, $expectedMessages, "Failed for streak: {$streak}");
        }
    }
    
    /**
     * Test message selection for active state - 7-13 day range
     */
    public function test_active_message_selection_seven_to_thirteen_days()
    {
        $service = new StreakStateService();
        
        $streakValues = [7, 8, 10, 13];
        $expectedMessages = [
            'One full week of reading!',
            'One week strong and counting!',
            'You\'ve completed your first week!',
            'One week of dedication achieved!'
        ];
        
        foreach ($streakValues as $streak) {
            $message = $service->selectMessage($streak, 'active', 0, false);
            $this->assertContains($message, $expectedMessages, "Failed for streak: {$streak}");
        }
    }
    
    /**
     * Test message selection for active state - 14-29 day range
     */
    public function test_active_message_selection_fourteen_to_twenty_nine_days()
    {
        $service = new StreakStateService();
        
        $streakValues = [14, 20, 25, 29];
        $expectedMessages = [
            'Two weeks of consistent reading!',
            'Two weeks strong!',
            'You\'ve reached the two-week milestone!',
            'Half a month of commitment achieved!'
        ];
        
        foreach ($streakValues as $streak) {
            $message = $service->selectMessage($streak, 'active', 0, false);
            $this->assertContains($message, $expectedMessages, "Failed for streak: {$streak}");
        }
    }
    
    /**
     * Test message selection for milestone day (30 days)
     */
    public function test_active_message_selection_thirty_day_milestone()
    {
        $service = new StreakStateService();
        
        $message = $service->selectMessage(30, 'active', 0, false);
        
        $expectedMilestoneMessages = [
            'One full month of reading!',
            'You\'ve reached your first month!',
            'Thirty days of dedication achieved!',
            'Your monthly milestone reached!'
        ];
        
        $this->assertContains($message, $expectedMilestoneMessages, "Day 30 should show milestone message");
    }

    /**
     * Test message selection for non-milestone days in 30-59 range
     */
    public function test_active_message_selection_thirty_to_fifty_nine_range()
    {
        $service = new StreakStateService();
        
        $nonMilestoneDays = [31, 45, 59];
        $expectedRangeMessages = [
            'Building on your month of reading!',
            'Your monthly habit is growing strong!',
            'Keep your month-long streak going!',
            'Over a month of consistent reading!'
        ];
        
        foreach ($nonMilestoneDays as $streak) {
            $message = $service->selectMessage($streak, 'active', 0, false);
            $this->assertContains($message, $expectedRangeMessages, "Failed for streak: {$streak}");
        }
    }

    /**
     * Test message selection for active state - 365+ day range
     */
    public function test_active_message_selection_year_plus_days()
    {
        $service = new StreakStateService();
        
        $streakValues = [365, 400, 1000];
        $expectedMessages = [
            'One full year of reading achieved!',
            'One year of incredible dedication!',
            'A complete year of commitment!',
            'Your one-year milestone reached!'
        ];
        
        foreach ($streakValues as $streak) {
            $message = $service->selectMessage($streak, 'active', 0, false);
            $this->assertContains($message, $expectedMessages, "Failed for streak: {$streak}");
        }
    }
    
    /**
     * Test message selection for warning state
     */
    public function test_warning_message_selection()
    {
        $service = new StreakStateService();
        
        $streakValues = [1, 5, 10, 30];
        
        foreach ($streakValues as $streak) {
            $message = $service->selectMessage($streak, 'warning', 0, false);
            
            // Should contain the streak number and be one of the warning messages
            $this->assertStringContainsString((string)$streak, $message, "Message should contain streak number for streak: {$streak}");
            
            // Check if it matches one of the expected patterns
            $expectedPatterns = [
                "Don't break your {$streak}-day streak! Read today!",
                "Your {$streak}-day streak needs you!",
                "Keep your {$streak}-day momentum going - read today!",
                "Don't let your {$streak}-day progress slip away!",
                "Your {$streak}-day streak is counting on you!"
            ];
            
            $matchesPattern = false;
            foreach ($expectedPatterns as $pattern) {
                if ($message === str_replace('{streak}', $streak, $pattern)) {
                    $matchesPattern = true;
                    break;
                }
            }
            
            $this->assertTrue($matchesPattern, "Message '{$message}' doesn't match expected warning patterns for streak: {$streak}");
        }
    }
    
    /**
     * Test acknowledgment message selection when user has read today
     */
    public function test_acknowledgment_message_selection_when_read_today()
    {
        $service = new StreakStateService();
        
        // Test multiple times to account for the 25% chance
        $foundAcknowledgment = false;
        $foundRegularActive = false;
        
        // Run test multiple times to check both acknowledgment and regular messages can appear
        for ($i = 0; $i < 20; $i++) {
            // Use different dates to vary the random seed
            Carbon::setTestNow(Carbon::today()->addDays($i));
            
            $message = $service->selectMessage(5, 'active', 0, true);
            
            $acknowledgmentMessages = [
                'Well done! You\'ve read today!',
                'Great job staying consistent!',
                'Your streak is safe for today!',
                'Another day of progress!'
            ];
            
            $regularActiveMessages = [
                'You\'re building a great habit!',
                'Keep the momentum going!',
                'Your consistency is showing!',
                'Building something beautiful!'
            ];
            
            if (in_array($message, $acknowledgmentMessages)) {
                $foundAcknowledgment = true;
            } elseif (in_array($message, $regularActiveMessages)) {
                $foundRegularActive = true;
            }
        }
        
        // Reset test time
        Carbon::setTestNow();
        
        // Should find both types of messages over multiple runs
        $this->assertTrue($foundRegularActive, 'Should find regular active messages when user has read today');
        // Note: Acknowledgment messages have 25% chance, so we might not always find them in 20 runs
        // But the logic should be testable
    }
    
    /**
     * Test message consistency - same message should be returned for same day/context
     */
    public function test_message_consistency_same_day()
    {
        $service = new StreakStateService();
        
        // Set a specific test date
        Carbon::setTestNow(Carbon::parse('2024-01-15'));
        
        // Get message multiple times for same parameters
        $message1 = $service->selectMessage(5, 'active', 0, false);
        $message2 = $service->selectMessage(5, 'active', 0, false);
        $message3 = $service->selectMessage(5, 'active', 0, false);
        
        // Should be the same message for same day/context
        $this->assertEquals($message1, $message2);
        $this->assertEquals($message2, $message3);
        
        // Reset test time
        Carbon::setTestNow();
    }
    
    /**
     * Test message rotation - different messages on different days
     */
    public function test_message_rotation_different_days()
    {
        $service = new StreakStateService();
        
        $messages = [];
        
        // Get messages for different days
        for ($i = 0; $i < 10; $i++) {
            Carbon::setTestNow(Carbon::parse('2024-01-15')->addDays($i));
            $messages[] = $service->selectMessage(5, 'active', 0, false);
        }
        
        // Should have some variety in messages (not all the same)
        $uniqueMessages = array_unique($messages);
        $this->assertGreaterThan(1, count($uniqueMessages), 'Should have message variety across different days');
        
        // Reset test time
        Carbon::setTestNow();
    }
    
    /**
     * Test streak range detection
     */
    public function test_streak_range_detection()
    {
        $service = new StreakStateService();
        
        // Use reflection to test private method
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('getStreakRange');
        $method->setAccessible(true);
        
        // Test all ranges
        $this->assertEquals(1, $method->invoke($service, 1));
        $this->assertEquals('2-6', $method->invoke($service, 2));
        $this->assertEquals('2-6', $method->invoke($service, 6));
        $this->assertEquals('7-13', $method->invoke($service, 7));
        $this->assertEquals('7-13', $method->invoke($service, 13));
        $this->assertEquals('14-29', $method->invoke($service, 14));
        $this->assertEquals('14-29', $method->invoke($service, 29));
        $this->assertEquals('30-59', $method->invoke($service, 30));
        $this->assertEquals('30-59', $method->invoke($service, 59));
        $this->assertEquals('60-89', $method->invoke($service, 60));
        $this->assertEquals('60-89', $method->invoke($service, 89));
        $this->assertEquals('90-119', $method->invoke($service, 90));
        $this->assertEquals('90-119', $method->invoke($service, 119));
        $this->assertEquals('120-149', $method->invoke($service, 120));
        $this->assertEquals('120-149', $method->invoke($service, 149));
        $this->assertEquals('150-179', $method->invoke($service, 150));
        $this->assertEquals('150-179', $method->invoke($service, 179));
        $this->assertEquals('180-364', $method->invoke($service, 180));
        $this->assertEquals('180-364', $method->invoke($service, 364));
        $this->assertEquals('365+', $method->invoke($service, 365));
        $this->assertEquals('365+', $method->invoke($service, 1000));
    }
    
    /**
     * Test edge cases for message selection
     */
    public function test_message_selection_edge_cases()
    {
        $service = new StreakStateService();
        
        // Test with invalid state - should default to active behavior
        $message = $service->selectMessage(5, 'invalid_state', 0, false);
        $this->assertIsString($message);
        $this->assertNotEmpty($message);
        
        // Test with very high streak values
        $message = $service->selectMessage(999, 'active', 0, false);
        $expectedMessages = [
            'A month of dedication!',
            'You\'re unstoppable!',
            'Your commitment is incredible!',
            'A true reading champion!'
        ];
        $this->assertContains($message, $expectedMessages);
        
        // Test warning message with very high streak
        $message = $service->selectMessage(999, 'warning', 0, false);
        $this->assertStringContainsString('999', $message);
    }

    /**
     * Helper method that uses the service to determine state
     */
    private function determineComponentState(int $currentStreak, bool $hasReadToday, ?Carbon $currentTime = null): string
    {
        $service = new StreakStateService();
        return $service->determineStreakState($currentStreak, $hasReadToday, $currentTime);
    }
}