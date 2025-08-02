<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ReadingLogController;
use App\Http\Controllers\SitemapController;
use App\Services\BookProgressService;
use App\Services\ReadingFormService;

Route::get('/', function () {
    return view('landing');
})->name('landing');

// XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Legal Pages
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');



// Authentication Routes (GET routes for views - POST routes handled by Fortify)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['request' => request()->merge(['token' => $token])]);
    })->name('password.reset');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Main Dashboard
    Route::get('/dashboard', function (Request $request) {
        $readingFormService = app(ReadingFormService::class);
        $hasReadToday = $readingFormService->hasReadToday(auth()->user());
        
        // Return partial for HTMX navigation, full page for direct access
        if ($request->header('HX-Request')) {
            return view('partials.dashboard-page', compact('hasReadToday'));
        }

        // Return full page for direct access (browser URL)
        return view('dashboard', compact('hasReadToday'));
    })->name('dashboard');

    // Coming Soon Routes (MVP placeholders)
    Route::get('/profile', function () {
        return response()->view('dashboard', [
            'message' => 'Profile management coming soon in post-MVP!'
        ]);
    })->name('profile');

    // Reading Log Routes
    Route::get('/logs', [ReadingLogController::class, 'index'])->name('logs.index');
    Route::get('/logs/create', [ReadingLogController::class, 'create'])->name('logs.create');
    Route::post('/logs', [ReadingLogController::class, 'store'])->name('logs.store');

    // User Preferences
    Route::post('/preferences/testament', function (Request $request) {
        $testament = $request->input('testament');

        // Validate the testament value
        if (!in_array($testament, ['Old', 'New'])) {
            return response('Invalid testament', 400);
        }

        // Store in session
        session(['testament_preference' => $testament]);

        // Return 200 OK for HTMX (no content needed since hx-swap="none")
        return response('', 200);
    })->name('preferences.testament');
});
