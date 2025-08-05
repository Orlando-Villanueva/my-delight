<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;
use App\Services\ReadingFormService;
use App\Services\UserStatisticsService;
use App\Services\StreakStateService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private DashboardController $controller;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        $this->controller = new DashboardController(
            app(ReadingFormService::class),
            app(UserStatisticsService::class),
            app(StreakStateService::class)
        );
    }

    public function test_index_returns_dashboard_view_for_regular_request()
    {
        $request = Request::create('/dashboard', 'GET');
        
        $response = $this->controller->index($request);
        
        $this->assertEquals('dashboard', $response->getName());
        $this->assertArrayHasKey('hasReadToday', $response->getData());
        $this->assertArrayHasKey('streakState', $response->getData());
        $this->assertArrayHasKey('stats', $response->getData());
    }

    public function test_index_returns_partial_view_for_htmx_request()
    {
        $request = Request::create('/dashboard', 'GET');
        $request->headers->set('HX-Request', 'true');
        
        $response = $this->controller->index($request);
        
        $this->assertEquals('partials.dashboard-page', $response->getName());
        $this->assertArrayHasKey('hasReadToday', $response->getData());
        $this->assertArrayHasKey('streakState', $response->getData());
        $this->assertArrayHasKey('stats', $response->getData());
    }

    public function test_index_computes_streak_state_correctly()
    {
        $request = Request::create('/dashboard', 'GET');
        
        $response = $this->controller->index($request);
        $data = $response->getData();
        
        // Verify streak state is one of the valid states
        $this->assertContains($data['streakState'], ['inactive', 'active', 'warning']);
        
        // Verify hasReadToday is boolean
        $this->assertIsBool($data['hasReadToday']);
        
        // Verify stats is an array with expected structure
        $this->assertIsArray($data['stats']);
        $this->assertArrayHasKey('streaks', $data['stats']);
        $this->assertArrayHasKey('current_streak', $data['stats']['streaks']);
    }
}