<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TranslationService
{
    protected bool $cacheEnabled;
    protected string $sourceLocale;
    protected array $supportedLocales;

    public function __construct()
    {
        $this->cacheEnabled = config('translation.cache_enabled', true);
        $this->sourceLocale = config('translation.source_locale', 'tr');
        $this->supportedLocales = config('translation.supported_locales', ['en', 'fr', 'de', 'sv', 'tr']);
    }

    /**
     * Translate text using MyMemory Free Translation API
     *
     * @param string $text Text to translate
     * @param string $targetLang Target language code (e.g., 'en', 'fr')
     * @param string $sourceLang Source language code (default: 'tr')
     * @return string Translated text or original text if translation fails
     */
    public function translate(string $text, string $targetLang, string $sourceLang = 'tr'): string
    {
        // If target language is the same as source, return original
        if ($targetLang === $sourceLang) {
            return $text;
        }

        // If target language is not supported, return original
        if (!in_array($targetLang, $this->supportedLocales)) {
            return $text;
        }

        // If no text to translate, return empty string
        if (empty(trim($text))) {
            return $text;
        }

        try {
            // Use MyMemory Free Translation API
            $response = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q' => $text,
                'langpair' => $sourceLang . '|' . $targetLang,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['responseData']['translatedText'])) {
                    return $data['responseData']['translatedText'];
                }
            }

            Log::warning('Translation API returned no result', [
                'text' => substr($text, 0, 100),
                'target' => $targetLang,
                'source' => $sourceLang,
            ]);

            return $text;
        } catch (\Exception $e) {
            Log::error('Translation failed: ' . $e->getMessage(), [
                'text' => substr($text, 0, 100),
                'target' => $targetLang,
                'source' => $sourceLang,
            ]);

            // Return original text on error (graceful degradation)
            return $text;
        }
    }

    /**
     * Translate a specific model field with caching
     *
     * @param Model $model The model instance
     * @param string $field Field name to translate
     * @param string $targetLang Target language code
     * @return string Translated text
     */
    public function translateModel(Model $model, string $field, string $targetLang): string
    {
        $originalText = $model->$field ?? '';

        // If empty, return as is
        if (empty(trim($originalText))) {
            return $originalText;
        }

        // If target is source language, return original
        if ($targetLang === $this->sourceLocale) {
            return $originalText;
        }

        // Check cache first if enabled
        if ($this->cacheEnabled) {
            $cached = $this->getCachedTranslation($model, $field, $targetLang);
            if ($cached !== null) {
                return $cached;
            }
        }

        // Translate using API
        $translated = $this->translate($originalText, $targetLang, $this->sourceLocale);

        // Store in cache if enabled and translation was successful
        if ($this->cacheEnabled && $translated !== $originalText) {
            $this->cacheTranslation($model, $field, $targetLang, $translated);
        }

        return $translated;
    }

    /**
     * Get cached translation from database
     *
     * @param Model $model
     * @param string $field
     * @param string $locale
     * @return string|null
     */
    protected function getCachedTranslation(Model $model, string $field, string $locale): ?string
    {
        $translation = DB::table('translations')
            ->where('translatable_type', get_class($model))
            ->where('translatable_id', $model->id)
            ->where('field', $field)
            ->where('locale', $locale)
            ->first();

        return $translation?->translated_text;
    }

    /**
     * Cache translation in database
     *
     * @param Model $model
     * @param string $field
     * @param string $locale
     * @param string $translatedText
     * @return void
     */
    protected function cacheTranslation(Model $model, string $field, string $locale, string $translatedText): void
    {
        try {
            DB::table('translations')->updateOrInsert(
                [
                    'translatable_type' => get_class($model),
                    'translatable_id' => $model->id,
                    'field' => $field,
                    'locale' => $locale,
                ],
                [
                    'translated_text' => $translatedText,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to cache translation: ' . $e->getMessage());
        }
    }

    /**
     * Clear cached translations for a model
     *
     * @param Model $model
     * @return void
     */
    public function clearModelCache(Model $model): void
    {
        DB::table('translations')
            ->where('translatable_type', get_class($model))
            ->where('translatable_id', $model->id)
            ->delete();
    }
}
