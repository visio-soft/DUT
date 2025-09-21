<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\UserAuthController;

// User Panel Routes
Route::get('/', [UserController::class, 'index'])->name('user.index');
Route::get('/projects', [UserController::class, 'projects'])->name('user.projects');
Route::get('/suggestions/{id}', [UserController::class, 'suggestionDetail'])->name('user.suggestion.detail');

// User Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
});

// AJAX Routes
Route::post('/suggestions/{id}/toggle-like', [UserController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('user.suggestion.toggle-like');
