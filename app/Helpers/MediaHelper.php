<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaHelper
{
    /**
     * Güvenli resim URL alma
     *
     * @param  HasMedia&InteractsWithMedia  $model
     */
    public static function getImageUrl(HasMedia $model, string $collection = 'images', ?string $conversion = null): string
    {
        try {
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media */
            $media = $model->getFirstMedia($collection);

            if (! $media) {
                return url('/images/no-image.png');
            }

            // Dosya varlığını kontrol et
            if (! $media->disk()->exists($media->getPath())) {
                Log::warning('Media file not found', [
                    'media_id' => $media->id,
                    'path' => $media->getPath(),
                    'model' => get_class($model),
                    'model_id' => $model->id,
                ]);

                return url('/images/no-image.png');
            }

            // Conversion varsa ve mevcut ise
            if ($conversion) {
                try {
                    return $media->getUrl($conversion);
                } catch (\Exception $e) {
                    Log::info('Conversion not available, using original', [
                        'conversion' => $conversion,
                        'media_id' => $media->id,
                    ]);

                    return $media->getUrl();
                }
            }

            return $media->getUrl();

        } catch (\Exception $e) {
            Log::error('Media URL error: '.$e->getMessage(), [
                'model' => get_class($model),
                'model_id' => $model->id ?? 'unknown',
                'collection' => $collection,
                'conversion' => $conversion,
            ]);

            return url('/images/no-image.png');
        }
    }

    /**
     * Medya dosyası var mı kontrolü
     *
     * @param  HasMedia&InteractsWithMedia  $model
     */
    public static function hasValidMedia(HasMedia $model, string $collection = 'images'): bool
    {
        try {
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media */
            $media = $model->getFirstMedia($collection);

            if (! $media) {
                return false;
            }

            return $media->disk()->exists($media->getPath());

        } catch (\Exception $e) {
            Log::error('Media validation error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Model için güvenli media attribute
     *
     * @param  HasMedia&InteractsWithMedia  $model
     */
    public static function getMediaAttribute(HasMedia $model, string $collection = 'images'): ?Media
    {
        try {
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media */
            return $model->getFirstMedia($collection);
        } catch (\Exception $e) {
            Log::error('Media attribute error: '.$e->getMessage());

            return null;
        }
    }
}
