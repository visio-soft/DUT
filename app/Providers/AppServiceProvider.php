<?php

namespace App\Providers;

use App\View\Composers\UserBackgroundComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Register view composer for user panel background images
        View::composer([
            'user.*'
        ], UserBackgroundComposer::class);
    }
}
