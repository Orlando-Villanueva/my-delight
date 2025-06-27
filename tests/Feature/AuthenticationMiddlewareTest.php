<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_protected_routes()
    {
        // Test dashboard redirect
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        // Test other protected routes
        $protectedRoutes = ['/history', '/profile', '/logs/create'];
        
        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    public function test_authenticated_users_can_access_protected_routes()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();

        $response = $this->actingAs($user)->get('/history');
        $response->assertOk();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();

        $response = $this->actingAs($user)->get('/logs/create');
        $response->assertOk();
    }

    public function test_authenticated_users_are_redirected_away_from_guest_routes()
    {
        $user = User::factory()->create();

        // Authenticated users should be redirected away from login
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/dashboard');

        // Same for register
        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/dashboard');

        // Same for forgot password
        $response = $this->actingAs($user)->get('/forgot-password');
        $response->assertRedirect('/dashboard');
    }

    public function test_login_redirects_to_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('ValidPass123!'),
        ]);

        $response = $this->withSession(['_token' => 'test-token'])
            ->post('/login', [
                'email' => 'test@example.com',
                'password' => 'ValidPass123!',
                '_token' => 'test-token',
            ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_registration_redirects_to_dashboard()
    {
        $response = $this->withSession(['_token' => 'test-token'])
            ->post('/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'ValidPass123!',
                'password_confirmation' => 'ValidPass123!',
                '_token' => 'test-token',
            ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_logout_redirects_to_home()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['_token' => 'test-token'])
            ->post('/logout', ['_token' => 'test-token']);
        
        // Fortify typically redirects to root path after logout
        $response->assertRedirect('/');
    }

    public function test_route_service_provider_home_constant_is_configured()
    {
        $this->assertEquals('/dashboard', \App\Providers\RouteServiceProvider::HOME);
    }

    public function test_public_routes_are_accessible_without_authentication()
    {
        // Welcome page should be accessible
        $response = $this->get('/');
        $response->assertOk();

        // Auth routes should be accessible
        $response = $this->get('/login');
        $response->assertOk();

        $response = $this->get('/register');
        $response->assertOk();

        $response = $this->get('/forgot-password');
        $response->assertOk();
    }
} 