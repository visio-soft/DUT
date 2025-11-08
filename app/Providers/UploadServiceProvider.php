<?php

namespace App\Providers;

use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class UploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register custom upload configurations
        $this->app->singleton('upload.config', function () {
            return [
                'max_size_bytes' => config('upload.max_file_size_bytes', 52428800), // 50MB
                'max_size_mb' => config('upload.max_file_size_mb', 50),
                'allowed_types' => array_merge(
                    config('upload.allowed_image_types', []),
                    config('upload.allowed_document_types', [])
                ),
            ];
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Custom validation for file uploads
        $this->registerCustomValidation();

        // Register error handlers for upload issues
        $this->registerUploadErrorHandlers();
    }

    /**
     * Register custom validation rules for file uploads
     */
    private function registerCustomValidation(): void
    {
        // Add custom validation rule for large files
        \Illuminate\Support\Facades\Validator::extend('large_file', function ($attribute, $value, $parameters, $validator) {
            if (! $value instanceof UploadedFile) {
                return true; // Let other validators handle non-files
            }

            $maxSizeBytes = $parameters[0] ?? 52428800; // 50MB default

            // Check file size
            if ($value->getSize() > $maxSizeBytes) {
                $maxSizeMB = round($maxSizeBytes / 1024 / 1024, 2);
                $fileSizeMB = round($value->getSize() / 1024 / 1024, 2);

                throw ValidationException::withMessages([
                    $attribute => "Dosya boyutu çok büyük! Maksimum: {$maxSizeMB}MB, Yüklenen: {$fileSizeMB}MB. Lütfen daha küçük bir dosya seçin veya resmi sıkıştırın.",
                ]);
            }

            return true;
        });
    }

    /**
     * Register upload error handlers
     */
    private function registerUploadErrorHandlers(): void
    {
        // Handle PHP upload errors
        if (isset($_FILES)) {
            foreach ($_FILES as $fileInput) {
                if (is_array($fileInput['error'])) {
                    foreach ($fileInput['error'] as $error) {
                        $this->handleUploadError($error);
                    }
                } else {
                    $this->handleUploadError($fileInput['error']);
                }
            }
        }
    }

    /**
     * Handle specific PHP upload errors
     */
    private function handleUploadError(int $error): void
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'Dosya boyutu PHP upload_max_filesize limitini aşıyor ('.ini_get('upload_max_filesize').')',
            UPLOAD_ERR_FORM_SIZE => 'Dosya boyutu form MAX_FILE_SIZE limitini aşıyor',
            UPLOAD_ERR_PARTIAL => 'Dosya kısmen yüklendi, lütfen tekrar deneyin',
            UPLOAD_ERR_NO_FILE => 'Hiç dosya yüklenmedi',
            UPLOAD_ERR_NO_TMP_DIR => 'Geçici klasör bulunamadı',
            UPLOAD_ERR_CANT_WRITE => 'Dosya diske yazılamadı',
            UPLOAD_ERR_EXTENSION => 'PHP uzantısı dosya yüklemeyi durdurdu',
        ];

        if ($error !== UPLOAD_ERR_OK && isset($errorMessages[$error])) {
            logger('Upload Error: '.$errorMessages[$error]);

            // If in web context, show user-friendly notification
            if (app()->runningInConsole() === false) {
                try {
                    Notification::make()
                        ->title('Dosya Yükleme Hatası')
                        ->body($errorMessages[$error])
                        ->danger()
                        ->persistent()
                        ->send();
                } catch (\Exception $e) {
                    // Fallback if Filament notification fails
                    session()->flash('upload_error', $errorMessages[$error]);
                }
            }
        }
    }
}
