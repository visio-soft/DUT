<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure the file upload settings for the application.
    | These settings are used by various parts of the application including
    | Filament file upload components.
    |
    */

    /*
     * Maximum file size in megabytes
     */
    'max_file_size_mb' => 20,

    /*
     * Maximum file size in bytes (calculated from MB)
     */
    'max_file_size_bytes' => 20 * 1024 * 1024, // 20MB

    /*
     * Allowed image types - Sadece temel resim formatları
     */
    'allowed_image_types' => [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ],

    /*
     * PHP ini settings for file uploads - handled in AppServiceProvider
     */
    'php_settings' => [
        'upload_max_filesize' => '25M',
        'post_max_size' => '30M',
        'memory_limit' => '256M',
        'max_execution_time' => 300,
        'max_input_time' => 300,
    ],

    /*
     * Image processing settings
     */
    'image_processing' => [
        'max_width' => 2000,
        'max_height' => 2000,
        'quality' => 85,
        'auto_orient' => true,
    ],
];
