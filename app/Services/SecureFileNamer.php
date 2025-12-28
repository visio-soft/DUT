<?php

namespace App\Services;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;

class SecureFileNamer extends FileNamer
{
    public function originalFileName(string $fileName): string
    {
        // Dosya ismini güvenli hale getir
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        // Türkçe karakterleri değiştir
        $name = $this->sanitizeFileName($name);

        // UUID-like unique identifier ekle
        $uniqueId = substr(md5(uniqid().microtime()), 0, 8);

        return $name.'_'.$uniqueId.'.'.strtolower($extension);
    }

    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        return $name.'_'.$conversion->getName().'.'.$extension;
    }

    public function responsiveFileName(string $fileName): string
    {
        // Responsive images için dosya ismi
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        return $name.'_responsive.'.$extension;
    }

    private function sanitizeFileName(string $fileName): string
    {
        // Türkçe karakterleri değiştir
        $turkish = ['ş', 'Ş', 'ı', 'İ', 'ç', 'Ç', 'ü', 'Ü', 'ö', 'Ö', 'ğ', 'Ğ'];
        $english = ['s', 'S', 'i', 'I', 'c', 'C', 'u', 'U', 'o', 'O', 'g', 'G'];
        $fileName = str_replace($turkish, $english, $fileName);

        // Sadece alfanumerik karakterler ve tire-alt çizgi bırak
        $fileName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $fileName);

        // Birden fazla alt çizgiyi tek alt çizgiye çevir
        $fileName = preg_replace('/_+/', '_', $fileName);

        // Başındaki ve sonundaki alt çizgileri kaldır
        $fileName = trim($fileName, '_');

        // Minimum 1 karakter garantisi
        if (empty($fileName)) {
            $fileName = 'file';
        }

        // Maksimum 50 karakter
        if (strlen($fileName) > 50) {
            $fileName = substr($fileName, 0, 50);
        }

        return $fileName;
    }
}
