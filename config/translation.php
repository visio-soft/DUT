<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Cloud Translation Configuration
    |--------------------------------------------------------------------------
    */
    'google' => [
        'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
        'project_id' => env('GOOGLE_TRANSLATE_PROJECT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    | List of language codes that the application supports for translation
    */
    'supported_locales' => ['en', 'fr', 'de', 'sv', 'tr'],

    /*
    |--------------------------------------------------------------------------
    | Source Locale
    |--------------------------------------------------------------------------
    | The default language of your database content
    */
    'source_locale' => 'tr',

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    | Enable database caching to avoid repeated API calls
    */
    'cache_enabled' => true,
];
