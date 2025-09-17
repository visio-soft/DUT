<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['tr','en'])
                ->labels([
                    'tr' => '🇹🇷', 'Türkçe',
                    'en' => '🇬🇧', 'English',
                ])
                ->displayLocale('name'); // Display language names instead of codes
        });
    }
}
