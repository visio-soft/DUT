<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageFormatRule implements ValidationRule
{
    private array $allowedFormats;
    private string $context;

    public function __construct(array $allowedFormats = [], string $context = 'genel')
    {
        $this->allowedFormats = empty($allowedFormats) ? [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 
            'image/webp', 'image/bmp', 'image/svg+xml'
        ] : $allowedFormats;
        
        $this->context = $context;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            return;
        }

        $mimeType = $value->getMimeType();
        $extension = strtolower($value->getClientOriginalExtension());
        
        // MIME type kontrolü
        if (!in_array($mimeType, $this->allowedFormats)) {
            $allowedExtensions = $this->getAllowedExtensions();
            $fail("Yüklenen dosya formatı desteklenmiyor. {$this->context} için desteklenen formatlar: " . implode(', ', $allowedExtensions) . ". Yüklenen dosya: {$extension}");
            return;
        }

        // Dosya boyutu kontrolü (5MB)
        if ($value->getSize() > 5242880) { // 5MB in bytes
            $fail('Resim dosyası 5MB\'dan büyük olamaz. Yüklenen dosya boyutu: ' . number_format($value->getSize() / 1024 / 1024, 2) . 'MB');
            return;
        }

        // Minimum boyut kontrolü (200x200)
        if ($mimeType !== 'image/svg+xml') {
            $imageInfo = @getimagesize($value->getPathname());
            if ($imageInfo !== false) {
                [$width, $height] = $imageInfo;
                
                if ($width < 200 || $height < 200) {
                    $fail("Resim en az 200x200 piksel boyutunda olmalıdır. Yüklenen resim: {$width}x{$height} piksel");
                    return;
                }

                if ($width > 6000 || $height > 6000) {
                    $fail("Resim en fazla 6000x6000 piksel boyutunda olabilir. Yüklenen resim: {$width}x{$height} piksel");
                    return;
                }
            }
        }
    }

    private function getAllowedExtensions(): array
    {
        $extensions = [];
        foreach ($this->allowedFormats as $mimeType) {
            switch ($mimeType) {
                case 'image/jpeg':
                    $extensions[] = 'JPEG';
                    break;
                case 'image/jpg':
                    $extensions[] = 'JPG';
                    break;
                case 'image/png':
                    $extensions[] = 'PNG';
                    break;
                case 'image/gif':
                    $extensions[] = 'GIF';
                    break;
                case 'image/webp':
                    $extensions[] = 'WebP';
                    break;
                case 'image/bmp':
                    $extensions[] = 'BMP';
                    break;
                case 'image/svg+xml':
                    $extensions[] = 'SVG';
                    break;
            }
        }
        return array_unique($extensions);
    }
}
