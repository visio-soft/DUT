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

        // Increase PHP limits for file uploads
        if (function_exists('ini_set')) {
            // Allow larger file uploads (50MB)
            ini_set('upload_max_filesize', '50M');
            // Allow larger POST data (60MB - should be larger than upload_max_filesize)
            ini_set('post_max_size', '60M');
            // Increase memory limit if needed (256MB)
            if (ini_get('memory_limit') !== '-1' && $this->convertToBytes(ini_get('memory_limit')) < $this->convertToBytes('256M')) {
                ini_set('memory_limit', '256M');
            }
            // Increase max execution time for file processing (300 seconds = 5 minutes)
            ini_set('max_execution_time', '300');
            // Increase max input time for file uploads (300 seconds)
            ini_set('max_input_time', '300');
        }
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
