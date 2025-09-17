<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Exception;

class MediaService
{
    /**
     * Güvenli resim yükleme - dosyayı direkt alarak
     */
    public static function addImageSafely(HasMedia $model, UploadedFile $file, string $collection = 'images', string $disk = 'public'): ?Media
    {
        try {
            // Dosya doğrulaması
            if (!$file->isValid()) {
                throw new Exception('Geçersiz dosya');
            }

            // Dosya boyutu kontrolü
            $fileSize = $file->getSize();
            if ($fileSize === null || $fileSize === false) {
                throw new Exception('Dosya boyutu alınamadı');
            }

            // Dosya varlığını kontrol et
            $filePath = $file->getRealPath();
            if (!$filePath || !file_exists($filePath)) {
                throw new Exception('Dosya okunabilir değil');
            }

            // Dosya boyutu için dosya sisteminden de kontrol
            if ($fileSize <= 0) {
                $fileSize = filesize($filePath);
                if ($fileSize === false || $fileSize <= 0) {
                    throw new Exception('Geçersiz dosya boyutu');
                }
            }

            $maxFileSize = config('media-library.max_file_size');
            if ($maxFileSize && $fileSize > $maxFileSize) {
                $maxSizeMB = round($maxFileSize / (1024 * 1024), 2);
                $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                throw new Exception("Dosya boyutu çok büyük ({$fileSizeMB}MB). Maksimum {$maxSizeMB}MB olmalıdır.");
            }

            // MIME type kontrolü - only allow jpeg, jpg and png (single condition requested)
            $allowedMimes = [
                'image/jpeg', 'image/jpg', 'image/png',
            ];

            if (!in_array($file->getMimeType(), $allowedMimes)) {
                throw new Exception('Desteklenmeyen dosya formatı: ' . $file->getMimeType());
            }

            // Mevcut dosyaları temizle (singleFile collection için)
            $model->clearMediaCollection($collection);

            // Güvenli dosya yükleme
            $mediaAdder = $model->addMedia($file)
                ->toMediaCollection($collection, $disk);

            return $mediaAdder;

        } catch (Exception $e) {
            Log::error('Media upload error: ' . $e->getMessage(), [
                'model' => get_class($model),
                'model_id' => $model->id ?? 'new',
                'collection' => $collection,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            throw $e;
        }
    }

    /**
     * Güvenli media URL alma
     * @param HasMedia&InteractsWithMedia $model
     */
    public static function getImageUrl(HasMedia $model, string $collection = 'images', ?string $conversion = null): ?string
    {
        try {
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media */
            $media = $model->getFirstMedia($collection);

            if (!$media) {
                return null;
            }

            // Dosya varlığını kontrol et
            if (!$media->disk()->exists($media->getPath())) {
                Log::warning('Media file not found', [
                    'media_id' => $media->id,
                    'path' => $media->getPath(),
                    'model' => get_class($model),
                    'model_id' => $model->id
                ]);
                return null;
            }

            return $conversion ? $media->getUrl($conversion) : $media->getUrl();

        } catch (Exception $e) {
            Log::error('Media URL error: ' . $e->getMessage(), [
                'model' => get_class($model),
                'model_id' => $model->id,
                'collection' => $collection,
                'conversion' => $conversion
            ]);

            return null;
        }
    }

    /**
     * Medya dosyası var mı kontrolü
     * @param HasMedia&InteractsWithMedia $model
     */
    public static function hasValidMedia(HasMedia $model, string $collection = 'images'): bool
    {
        try {
            /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media */
            $media = $model->getFirstMedia($collection);

            if (!$media) {
                return false;
            }

            return $media->disk()->exists($media->getPath());

        } catch (Exception $e) {
            Log::error('Media validation error: ' . $e->getMessage());
            return false;
        }
    }
}
