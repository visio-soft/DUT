<?php

namespace App\Filament\Helpers;

use Filament\Notifications\Notification;

class NotificationHelper
{
    /**
     * Send a success notification.
     */
    public static function success(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
    }

    /**
     * Send an error notification.
     */
    public static function error(string $title, string $body, int $duration = 5000): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->duration($duration)
            ->send();
    }

    /**
     * Handle validation exception and send appropriate notification.
     */
    public static function handleValidationException(\Illuminate\Validation\ValidationException $e): void
    {
        $errors = $e->errors();

        // Check for file size errors
        if (self::hasFileSizeError($errors)) {
            self::error(
                __('common.file_size_error_title'),
                __('common.file_size_error_body'),
                10000
            );

            return;
        }

        // Generic validation error
        self::error(
            __('common.validation_error_title'),
            __('common.validation_error_body', ['message' => $e->getMessage()])
        );
    }

    /**
     * Handle general exception and send appropriate notification.
     */
    public static function handleException(\Exception $e, string $context): void
    {
        self::error(
            __('common.error_title'),
            __("common.{$context}_error", ['message' => $e->getMessage()])
        );
    }

    /**
     * Check if the validation errors contain file size error.
     */
    private static function hasFileSizeError(array $errors): bool
    {
        $errorJson = json_encode($errors);

        return (isset($errors['images']) && str_contains($errorJson, 'max')) ||
               (isset($errors['image']) && str_contains($errorJson, 'max'));
    }
}
