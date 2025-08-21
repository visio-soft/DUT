<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
