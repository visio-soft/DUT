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
        'image/webp'
    ],



    /*
     * PHP ini settings for file uploads - handled in AppServiceProvider
     */
    'php_settings' => [
        'upload_max_filesize' => '100M',
        'post_max_size' => '120M',
        'memory_limit' => '512M',
        'max_execution_time' => 600,
        'max_input_time' => 600,
    ],

    /*
     * Image processing settings
     */
    'image_processing' => [
        'max_width' => 6000,  // Daha yüksek çözünürlük için artırıldı
        'max_height' => 6000, // Daha yüksek çözünürlük için artırıldı
        'quality' => 90,      // Daha yüksek kalite
        'auto_orient' => true,
        'preserve_metadata' => false, // Dosya boyutunu düşürmek için
    ],
];
