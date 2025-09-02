<?php

use Illuminate\Support\Facades\Route;

// Ana sayfa Filament admin paneline yönlendir
Route::get('/', function () {
    return redirect('/admin');
});

// Project Designs Table Routes - Moved to admin panel
// Route::get('/project-designs', [ProjectDesignTableController::class, 'index'])->name('project-designs.index');
// Route::post('/project-designs/{projectDesign}/like', [ProjectDesignTableController::class, 'like'])->name('project-designs.like');
