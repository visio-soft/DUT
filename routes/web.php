<?php

use Illuminate\Support\Facades\Route;

// Ana sayfa Filament admin paneline yönlendir
Route::get('/', function () {
    return redirect('/admin');
});

// Language switching route
Route::post('/language/switch', function () {
    $locale = request('locale');
    
    if (in_array($locale, ['en', 'tr'])) {
        session(['locale' => $locale]);
    }
    
    return redirect()->back();
})->name('language.switch');

// Project Designs Table Routes - Moved to admin panel
// Route::get('/project-designs', [ProjectDesignTableController::class, 'index'])->name('project-designs.index');
// Route::post('/project-designs/{projectDesign}/like', [ProjectDesignTableController::class, 'like'])->name('project-designs.like');
