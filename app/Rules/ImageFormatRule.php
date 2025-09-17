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
        // Default to only jpeg/jpg/png per project requirement
        $this->allowedFormats = empty($allowedFormats) ? [
            'image/jpeg', 'image/jpg', 'image/png'
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

    // Per requirement: no file size or dimension checks here — only MIME type enforcement.
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
            }
        }
        return array_unique($extensions);
    }
}
