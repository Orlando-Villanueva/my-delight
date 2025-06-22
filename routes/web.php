<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;

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

// HTMX and Alpine.js Demo Routes
Route::get('/demo', [DemoController::class, 'index'])->name('demo');
Route::get('/demo/verse', [DemoController::class, 'getRandomVerse'])->name('demo.verse');
Route::post('/demo/log', [DemoController::class, 'logReading'])->name('demo.log');

// Temporary routes for layout preview (these will be replaced with real controllers later)
Route::get('/dashboard', function () {
    return view('preview-layout');
})->name('dashboard');

Route::get('/history', function () {
    return view('preview-layout');
})->name('history');

Route::get('/profile', function () {
    return view('preview-layout');
})->name('profile');

Route::get('/logs/create', function () {
    return view('preview-layout');
})->name('logs.create');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

// Layout Preview Route (temporary for testing)
Route::get('/preview-layout', function () {
    return view('preview-layout');
})->name('layout.preview');
