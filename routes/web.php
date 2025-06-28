<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ReadingLogController;

Route::get('/', function () {
    return view('welcome');
});

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
        // Return partial view for HTMX requests (seamless content loading)
        if ($request->header('HX-Request')) {
            return view('partials.dashboard-content');
        }
        
        // Return full page for direct access (graceful degradation)
        return view('dashboard');
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
});
