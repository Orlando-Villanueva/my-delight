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
        $this->assertEquals('bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-[#D1D7E0] dark:border-gray-700 shadow-md', $inactiveClasses['background']);
        $this->assertEquals('text-gray-400 dark:text-gray-500', $inactiveClasses['icon']);
        $this->assertFalse($inactiveClasses['showIcon']);
        $this->assertEquals('opacity-70', $inactiveClasses['opacity']);
        $this->assertEquals('border-[#D1D7E0] dark:border-gray-600', $inactiveClasses['border']);
        
        // Test active state classes
        $activeClasses = $service->getStateClasses('active');
        $this->assertEquals('bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white shadow-md', $activeClasses['background']);
        $this->assertEquals('text-accent-500', $activeClasses['icon']);
        $this->assertTrue($activeClasses['showIcon']);
        $this->assertEquals('opacity-90', $activeClasses['opacity']);
        $this->assertEquals('border-white/20', $activeClasses['border']);
        
        // Test warning state classes
        $warningClasses = $service->getStateClasses('warning');
        $this->assertEquals('bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md', $warningClasses['background']);
        $this->assertEquals('text-orange-200', $warningClasses['icon']);
        $this->assertTrue($warningClasses['showIcon']);
        $this->assertEquals('opacity-90', $warningClasses['opacity']);
        $this->assertEquals('border-white/20', $warningClasses['border']);
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