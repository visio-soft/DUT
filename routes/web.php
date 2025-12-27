<?php

use App\Filament\Pages\UserPanel\ProjectSuggestions;
use App\Filament\Pages\UserPanel\SuggestionDetail;
use App\Filament\Pages\UserPanel\UserDashboard;
use App\Filament\Pages\UserPanel\UserProjects;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\HandleFileUploadLimits;
use Illuminate\Support\Facades\Route;

// Language switching route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['tr', 'en'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('language.switch');

// Debug route for upload issues
Route::get('/debug-upload', function () {
    return response()->json([
        'php_limits' => HandleFileUploadLimits::getCurrentLimits(),
        'media_library_max' => config('media-library.max_file_size'),
        'media_library_max_mb' => round(config('media-library.max_file_size') / 1024 / 1024, 2).' MB',
        'upload_config' => config('upload'),
        'server_info' => [
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'php_version' => phpversion(),
            'php_sapi' => php_sapi_name(),
        ],
        'permissions' => [
            'storage_writable' => is_writable(storage_path()),
            'public_writable' => is_writable(public_path()),
            'temp_dir' => sys_get_temp_dir(),
            'temp_writable' => is_writable(sys_get_temp_dir()),
        ],
        'last_upload_errors' => session('upload_errors', []),
    ]);
})->name('debug.upload');

// User Panel Routes - Handled by Filament Pages in app/Filament/Pages/UserPanel/
Route::middleware('auth')->group(function () {
    Route::get('/', [UserDashboard::class, 'index'])->name('user.index');
    Route::get('/projects', [UserProjects::class, 'index'])->name('user.projects');
    Route::get('/projects/{id}/suggestions', [ProjectSuggestions::class, 'show'])->name('user.project.suggestions');
    Route::get('/suggestions/{id}', [SuggestionDetail::class, 'show'])->name('user.suggestion.detail');
    Route::get('/projects/{id}/surveys', [\App\Filament\Pages\UserPanel\ProjectSurveys::class, 'index'])->name('user.project.surveys');
    Route::post('/projects/{id}/surveys/{surveyId}/submit', [\App\Filament\Pages\UserPanel\ProjectSurveys::class, 'store'])->name('user.project.surveys.store');
});

// User Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
    Route::post('/notifications/mark-read', [UserController::class, 'markAsRead'])->name('user.notifications.mark-read');
});

// AJAX Routes
Route::post('/suggestions/{id}/toggle-like', [UserController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('user.suggestion.toggle-like');

Route::post('/likes/{id}/update-feedback', [UserController::class, 'updateLikeFeedback'])
    ->middleware('auth')
    ->name('user.like.update-feedback');

Route::post('/suggestions/{id}/comments', [UserController::class, 'storeComment'])
    ->middleware('auth')
    ->name('user.suggestion.store-comment');

Route::post('/comments/{id}/reply', [UserController::class, 'storeReply'])
    ->middleware('auth')
    ->name('user.comment.store-reply');

Route::post('/comments/{id}/toggle-like', [UserController::class, 'toggleCommentLike'])
    ->middleware('auth')
    ->name('user.comment.toggle-like');

// Chunked Upload Routes
Route::post('/api/chunked-upload', [App\Http\Controllers\ChunkedUploadController::class, 'upload'])
    ->middleware(['auth', HandleFileUploadLimits::class])
    ->name('api.chunked-upload');

Route::post('/api/chunked-upload/cleanup', [App\Http\Controllers\ChunkedUploadController::class, 'cleanupOldSessions'])
    ->middleware('auth')
    ->name('api.chunked-upload.cleanup');

// Location API
Route::get('/api/locations', [App\Http\Controllers\LocationController::class, 'getChildren'])->name('api.locations');
