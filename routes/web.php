<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFilamentController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\AuthController;

// Site ana sayfası artık demo public Filament-styled sayfa
Route::get('/', [\App\Http\Controllers\VoteController::class, 'index'])->name('home');

// Public project index page - Admin olmayan kullanıcılar için proje kategorileri
Route::get('/projeler', [PublicProjectController::class, 'index'])->name('public.projects.index');

// Public project detail pages
Route::get('/projeler/{project}', [PublicProjectController::class, 'show'])->name('public.projects.show');
Route::get('/oneriler/{suggestion}', [PublicProjectController::class, 'showSuggestion'])->name('public.projects.suggestion');

// Comment functionality for suggestions
Route::post('/oneriler/{suggestion}/comment', [PublicProjectController::class, 'storeComment'])->name('public.projects.comment.store');

// Authentication routes for public users
Route::prefix('auth')->name('auth.')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register routes  
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Logout route (POST for security)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Alternatif route (aynı içeriğe işaret eder)
Route::get('/filament-public', [PublicFilamentController::class, 'index'])->name('filament.public');

// Voting page
use App\Http\Controllers\VoteController;
Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');

// Suggestion detail
Route::get('/suggestion/{suggestion}', [\App\Http\Controllers\VoteController::class, 'show'])->name('vote.show');
