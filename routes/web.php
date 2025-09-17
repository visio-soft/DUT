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


