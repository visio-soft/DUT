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

        // Increase PHP limits for file uploads - Multiple approaches
        $this->setPhpLimitsMultipleWays();
    }

    /**
     * Set basic PHP limits for 20MB file uploads
     */
    private function setPhpLimitsMultipleWays(): void
    {
        // Basic settings for 20MB uploads
        @ini_set('upload_max_filesize', '25M');
        @ini_set('post_max_size', '30M');
        @ini_set('memory_limit', '256M');
        @ini_set('max_execution_time', '300');
    }

    /**
     * Convert PHP memory notation to bytes
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower(substr($value, -1));
        $value = (int) substr($value, 0, -1);

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}
