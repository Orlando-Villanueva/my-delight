<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_error_page_displays_correctly(): void
    {
        $response = $this->get('/non-existent-page');
        
        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Page Not Found');
        $response->assertSee('Delight');
    }

    public function test_404_page_shows_appropriate_actions(): void
    {
        $response = $this->get('/non-existent-page');
        
        $response->assertStatus(404);
        $response->assertSee('Go Home');
        $response->assertSee('Go Back');
    }

    public function test_404_page_actions_work_for_all_users(): void
    {
        // Test for guest users
        $response = $this->get('/non-existent-page');
        $response->assertStatus(404);
        $response->assertSee('Go Home');
        
        // Test for authenticated users - same buttons should appear
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get('/non-existent-page');
        $response->assertStatus(404);
        $response->assertSee('Go Home');
    }

    public function test_500_error_page_can_be_rendered(): void
    {
        // We can't easily trigger a real 500 error in tests, but we can test the view directly
        $view = view('errors.500');
        $content = $view->render();
        
        $this->assertStringContainsString('500', $content);
        $this->assertStringContainsString('Server Error', $content);
        $this->assertStringContainsString('Delight', $content);
        $this->assertStringContainsString('Try Again', $content);
    }

    public function test_403_error_page_can_be_rendered(): void
    {
        $view = view('errors.403');
        $content = $view->render();
        
        $this->assertStringContainsString('403', $content);
        $this->assertStringContainsString('Access Forbidden', $content);
        $this->assertStringContainsString('Delight', $content);
    }

    public function test_error_pages_include_delight_branding(): void
    {
        $errorPages = ['404', '500', '403'];
        
        foreach ($errorPages as $errorCode) {
            $view = view("errors.{$errorCode}");
            $content = $view->render();
            
            // Check for Delight branding elements
            $this->assertStringContainsString('Delight', $content);
            $this->assertStringContainsString('logo-64.png', $content);
            $this->assertStringContainsString('primary-500', $content);
        }
    }
}