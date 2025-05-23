<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;

Route::get('/', function () {
    return view('welcome');
});

// HTMX and Alpine.js Demo Routes
Route::get('/demo', [DemoController::class, 'index'])->name('demo');
Route::get('/demo/verse', [DemoController::class, 'getRandomVerse'])->name('demo.verse');
Route::post('/demo/log', [DemoController::class, 'logReading'])->name('demo.log');
