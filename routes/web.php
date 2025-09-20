<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// User Panel Routes
Route::get('/', [UserController::class, 'index'])->name('user.index');
Route::get('/projects', [UserController::class, 'projects'])->name('user.projects');
Route::get('/suggestions/{id}', [UserController::class, 'suggestionDetail'])->name('user.suggestion.detail');

// AJAX Routes
Route::post('/suggestions/{id}/toggle-like', [UserController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('user.suggestion.toggle-like');
