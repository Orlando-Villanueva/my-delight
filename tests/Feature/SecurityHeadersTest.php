<?php

use App\Models\User;

test('security headers are applied to web requests', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/dashboard');
    
    $response->assertStatus(200);
    
    // Check essential security headers
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('security headers are applied to public pages', function () {
    $response = $this->get('/login');
    
    $response->assertStatus(200);
    
    // Check essential security headers
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('strict transport security header is not applied in non-production environments', function () {
    $response = $this->get('/login');
    
    $response->assertStatus(200);
    
    // HSTS should not be present in development/testing
    expect($response->headers->has('Strict-Transport-Security'))->toBeFalse();
});