<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    /**
     * Güvenli resim yükleme - dosyayı direkt alarak
     */
    public static function addImageSafely(HasMedia $model, UploadedFile $file, string $collection = 'images', string $disk = 'public'): ?Media
    {
        try {
            // Dosya doğrulaması
            if (! $file->isValid()) {
                throw new Exception('Geçersiz dosya');
            }

            // MIME type kontrolü
            $allowedMimes = [
                'image/jpeg', 'image/jpg', 'image/png',
                'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml',
            ];

            if (! in_array($file->getMimeType(), $allowedMimes)) {
                throw new Exception('Desteklenmeyen dosya formatı: '.$file->getMimeType());
            }

            // Dosya boyutu kontrolü (5MB)
            if ($file->getSize() > 5242880) {
                throw new Exception('Dosya boyutu çok büyük: '.number_format($file->getSize() / 1024 / 1024, 2).'MB');
            }

            // SVG olmayan dosyalar için boyut kontrolü
            if ($file->getMimeType() !== 'image/svg+xml') {
                $imageInfo = @getimagesize($file->getPathname());
                if ($imageInfo !== false) {
                    [$width, $height] = $imageInfo;

                    if ($width < 200 || $height < 200) {
                        throw new Exception("Resim en az 200x200 piksel olmalıdır. Mevcut: {$width}x{$height}");
                    }

                    if ($width > 6000 || $height > 6000) {
                        throw new Exception("Resim en fazla 6000x6000 piksel olabilir. Mevcut: {$width}x{$height}");
                    }
                }
            }

            // Mevcut dosyaları temizle (singleFile collection için)
            $model->clearMediaCollection($collection);

            // Güvenli dosya yükleme
            $mediaAdder = $model->addMedia($file)
                ->toMediaCollection($collection, $disk);

            return $mediaAdder;

        } catch (Exception $e) {
            Log::error('Media upload error: '.$e->getMessage(), [
                'model' => get_class($model),
                'model_id' => $model->id ?? 'new',
                'collection' => $collection,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            throw $e;
        }
    }

    /**
     * Güvenli media URL alma
     *
     * @param  HasMedia&InteractsWithMedia  $model
     */
    public static function getImageUrl(HasMedia $model, string $collection = 'images', ?string $conversion = null): ?string
    {
        try {
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media */
            $media = $model->getFirstMedia($collection);

            if (! $media) {
                return null;
            }

            // Dosya varlığını kontrol et
            if (! $media->disk()->exists($media->getPath())) {
                Log::warning('Media file not found', [
                    'media_id' => $media->id,
                    'path' => $media->getPath(),
                    'model' => get_class($model),
                    'model_id' => $model->id,
                ]);

                return null;
            }

            return $conversion ? $media->getUrl($conversion) : $media->getUrl();

        } catch (Exception $e) {
            Log::error('Media URL error: '.$e->getMessage(), [
                'model' => get_class($model),
                'model_id' => $model->id,
                'collection' => $collection,
                'conversion' => $conversion,
            ]);

            return null;
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

        } catch (Exception $e) {
            Log::error('Media validation error: '.$e->getMessage());

            return false;
        }
    }
}
