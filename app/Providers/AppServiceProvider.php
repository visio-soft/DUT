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
     * Set PHP limits using multiple approaches for better compatibility
     */
    private function setPhpLimitsMultipleWays(): void
    {
        $settings = [
            'upload_max_filesize' => '50M',
            'post_max_size' => '60M',
            'memory_limit' => '256M',
            'max_execution_time' => '300',
            'max_input_time' => '300',
            'max_file_uploads' => '20',
            'max_input_vars' => '3000',
        ];

        // Method 1: Standard ini_set
        if (function_exists('ini_set')) {
            foreach ($settings as $key => $value) {
                if ($key === 'memory_limit' && ini_get('memory_limit') === '-1') {
                    continue; // Skip unlimited memory
                }

                $current = ini_get($key);
                $result = @ini_set($key, $value);

                // Log for debugging
                if ($result !== false) {
                    logger("PHP setting updated: {$key} from {$current} to {$value}");
                } else {
                    logger("Failed to set PHP setting: {$key} = {$value} (current: {$current})");
                }
            }
        }

        // Method 2: Try alternative approaches
        if (function_exists('putenv')) {
            foreach ($settings as $key => $value) {
                @putenv("PHP_{$key}={$value}");
            }
        }

        // Method 3: Runtime settings for current process
        @ini_alter('upload_max_filesize', '50M');
        @ini_alter('post_max_size', '60M');

        // Method 4: Set execution limits that usually work
        @set_time_limit(300);
        @ini_set('max_input_time', 300);

        // Method 5: Memory limit (usually works)
        if (ini_get('memory_limit') !== '-1' && $this->convertToBytes(ini_get('memory_limit')) < $this->convertToBytes('256M')) {
            @ini_set('memory_limit', '256M');
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
