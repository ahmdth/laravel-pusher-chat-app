<?php

use App\Http\Controllers\ChatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Str;
use Inertia\Inertia;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
  return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum'])->prefix('chat')->group(function () {
  Route::inertia("/", 'Chat')->name('chat');
  Route::get("rooms", [ChatController::class, 'rooms']);
  Route::get("{room}/messages", [ChatController::class, 'messages']);
  Route::post("{room}/messages", [ChatController::class, 'store']);
});

Route::get('/whooops', function () {
  throw new Exception('whooops');
});
