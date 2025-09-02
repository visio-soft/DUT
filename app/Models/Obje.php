<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\Conversions\Conversion;

class Obje extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $table = 'objeler';
    
    protected $fillable = [
        'category',
        'name',
        // Legacy Turkish column names for backward compatibility
        'kategori',
        'isim',
    ];

    // Backward compatibility accessors for Turkish column names
    public function getKategoriAttribute()
    {
        return $this->attributes['category'] ?? null;
    }

    public function setKategoriAttribute($value)
    {
        $this->attributes['category'] = $value;
    }

    public function getIsimAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function setIsimAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    // Sabit kategori listesi (formlarda ve kontrollerde kullanmak için)
    public const CATEGORIES = [
    'doga' => 'Doğa',
    'yasam' => 'Yaşam',
    'ulasim' => 'Ulaşım',
    'mimari' => 'Mimari',
    'sanat' => 'Sanat',
    'doku' => 'Doku',

    ];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes([
                'image/jpeg', 
                'image/jpg', 
                'image/png', 
                'image/gif', 
                'image/webp', 
                'image/bmp', 
                'image/svg+xml'
            ])
            ->singleFile()
            ->useDisk('public');
    }
    
    public function registerMediaConversions(?Media $media = null): void
    {
        // Conversion'ları devre dışı bırak - hata kaynağı
        // Eğer thumbnail gerekirse frontend'de CSS ile küçültelim
        
        /*
        // Sadece raster (bitmap) resimler için conversion yapılır
        $this->addMediaConversion('preview')
            ->width(400)
            ->height(400)
            ->nonQueued()
            ->performOnCollections('images')
            ->optimize()
            ->nonOptimized() // Şeffaflığı korumak için
            ->skipOnFailure() // Hata durumunda conversion'u atla
            ->performOnlyOnMimeTypes([
                'image/jpeg',
                'image/jpg', 
                'image/png',
                'image/gif',
                'image/webp',
                'image/bmp'
            ]);
            
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->nonQueued()
            ->performOnCollections('images')
            ->optimize()
            ->nonOptimized() // Şeffaflığı korumak için
            ->skipOnFailure() // Hata durumunda conversion'u atla
            ->performOnlyOnMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png', 
                'image/gif',
                'image/webp',
                'image/bmp'
            ]);
        */
    }
}
