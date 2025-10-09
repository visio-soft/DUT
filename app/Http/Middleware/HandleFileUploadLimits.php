<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleFileUploadLimits
{
    /**
     * Handle an incoming request and adjust PHP settings for file uploads.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a file upload request
        if ($request->hasFile('files') || $request->hasFile('images') ||
            $request->header('Content-Type') === 'multipart/form-data' ||
            str_contains($request->header('Content-Type', ''), 'multipart/form-data')) {

            // Try to increase limits for file uploads
            $this->increasePHPLimits();

            // Set custom timeout for file processing
            set_time_limit(300); // 5 minutes
        }

        $response = $next($request);

        return $response;
    }

    /**
     * Increase PHP limits for file uploads
     */
    private function increasePHPLimits(): void
    {
        $limits = [
            'upload_max_filesize' => '50M',
            'post_max_size' => '60M',
            'memory_limit' => '256M',
            'max_execution_time' => '300',
            'max_input_time' => '300',
            'max_file_uploads' => '20',
            'max_input_vars' => '3000',
        ];

        foreach ($limits as $setting => $value) {
            // Only set if current value is smaller or if setting is allowed
            if (function_exists('ini_set')) {
                $current = ini_get($setting);

                if ($setting === 'memory_limit' && $current === '-1') {
                    continue; // Skip unlimited memory
                }

                // Try to set the value
                $result = @ini_set($setting, $value);

                if ($result === false) {
                    // Log that setting couldn't be changed
                    logger("Could not set PHP setting: {$setting} = {$value}");
                }
            }
        }
    }

    /**
     * Get current PHP upload limits for debugging
     */
    public static function getCurrentLimits(): array
    {
        return [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'max_input_time' => ini_get('max_input_time'),
            'max_file_uploads' => ini_get('max_file_uploads'),
        ];
    }
}
