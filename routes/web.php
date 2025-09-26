<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\UserAuthController;

// User Panel Routes
Route::get('/', [UserController::class, 'index'])->name('user.index');
Route::get('/projects', [UserController::class, 'projects'])->name('user.projects');
Route::get('/projects/{id}/suggestions', [UserController::class, 'projectSuggestions'])->name('user.project.suggestions');
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

Route::post('/suggestions/{id}/comments', [UserController::class, 'storeComment'])
    ->middleware('auth')
    ->name('user.suggestion.store-comment');

Route::post('/comments/{id}/reply', [UserController::class, 'storeReply'])
    ->middleware('auth')
    ->name('user.comment.store-reply');

Route::post('/comments/{id}/toggle-like', [UserController::class, 'toggleCommentLike'])
    ->middleware('auth')
    ->name('user.comment.toggle-like');
