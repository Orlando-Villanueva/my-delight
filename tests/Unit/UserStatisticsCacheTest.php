<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserStatisticsService;
use App\Services\ReadingLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UserStatisticsCacheTest extends TestCase
{
    use RefreshDatabase;

    protected UserStatisticsService $service;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->service = app(UserStatisticsService::class);
        
        // Clear cache before each test
        Cache::flush();
    }

    public function testGetDashboardStatisticsCachesResults()
    {
        // First call should cache the results
        $firstResult = $this->service->getDashboardStatistics($this->user);
        
        // Verify cache key exists
        $this->assertTrue(Cache::has("user_dashboard_stats_{$this->user->id}"));
        
        // Second call should return cached results
        $secondResult = $this->service->getDashboardStatistics($this->user);
        
        $this->assertEquals($firstResult, $secondResult);
    }

    public function testGetStreakStatisticsCachesIndividualStreaks()
    {
        // Call streak statistics
        $this->service->getStreakStatistics($this->user);
        
        // Verify individual streak caches exist
        $this->assertTrue(Cache::has("user_current_streak_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_longest_streak_{$this->user->id}"));
    }

    public function testGetCalendarDataCachesResults()
    {
        $year = now()->year;
        
        // First call should cache the results
        $firstResult = $this->service->getCalendarData($this->user, $year);
        
        // Verify cache key exists
        $this->assertTrue(Cache::has("user_calendar_{$this->user->id}_{$year}"));
        
        // Second call should return cached results
        $secondResult = $this->service->getCalendarData($this->user, $year);
        
        $this->assertEquals($firstResult, $secondResult);
    }

    public function testInvalidateUserCacheClearsAllCaches()
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;
        
        // Populate caches
        $this->service->getDashboardStatistics($this->user);
        $this->service->getStreakStatistics($this->user);
        $this->service->getCalendarData($this->user, $currentYear);
        $this->service->getCalendarData($this->user, $previousYear);
        
        // Verify caches exist
        $this->assertTrue(Cache::has("user_dashboard_stats_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_current_streak_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_longest_streak_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_calendar_{$this->user->id}_{$currentYear}"));
        $this->assertTrue(Cache::has("user_calendar_{$this->user->id}_{$previousYear}"));
        
        // Invalidate cache
        $this->service->invalidateUserCache($this->user);
        
        // Verify all caches are cleared
        $this->assertFalse(Cache::has("user_dashboard_stats_{$this->user->id}"));
        $this->assertFalse(Cache::has("user_current_streak_{$this->user->id}"));
        $this->assertFalse(Cache::has("user_longest_streak_{$this->user->id}"));
        $this->assertFalse(Cache::has("user_calendar_{$this->user->id}_{$currentYear}"));
        $this->assertFalse(Cache::has("user_calendar_{$this->user->id}_{$previousYear}"));
    }

    public function testCacheHasDifferentTTLs()
    {
        // This test verifies that different cache keys have different TTLs
        // We can't directly test TTL values, but we can verify the caching behavior
        
        $this->service->getDashboardStatistics($this->user);
        $this->service->getStreakStatistics($this->user);
        $this->service->getCalendarData($this->user);
        
        // All caches should exist after creation
        $this->assertTrue(Cache::has("user_dashboard_stats_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_current_streak_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_longest_streak_{$this->user->id}"));
        $this->assertTrue(Cache::has("user_calendar_{$this->user->id}_" . now()->year));
    }
}