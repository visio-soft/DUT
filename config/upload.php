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
    'max_file_size_mb' => 50,

    /*
     * Maximum file size in bytes (calculated from MB)
     */
    'max_file_size_bytes' => 50 * 1024 * 1024, // 50MB

    /*
     * Allowed image types
     */
    'allowed_image_types' => [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/bmp',
        'image/svg+xml'
    ],

    /*
     * Allowed document types
     */
    'allowed_document_types' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain'
    ],

    /*
     * PHP ini settings for file uploads - handled in AppServiceProvider
     */
    'php_settings' => [
        'upload_max_filesize' => '50M',
        'post_max_size' => '60M',
        'memory_limit' => '256M',
        'max_execution_time' => 300,
        'max_input_time' => 300,
    ],

    /*
     * Image processing settings
     */
    'image_processing' => [
        'max_width' => 4000,
        'max_height' => 4000,
        'quality' => 85,
        'auto_orient' => true,
    ],
];
