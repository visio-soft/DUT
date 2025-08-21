<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ana kategoriler
        $belediyeHizmetleri = Category::create(['name' => 'Belediye Hizmetleri', 'icon' => 'heroicon-o-building-office']);
        $altyapi = Category::create(['name' => 'Altyapı', 'icon' => 'heroicon-o-wrench-screwdriver']);
        $sosyalProjeler = Category::create(['name' => 'Sosyal Projeler', 'icon' => 'heroicon-o-user-group']);
        
        // Alt kategoriler
        // Belediye Hizmetleri alt kategorileri
        Category::create(['name' => 'Temizlik İşleri', 'icon' => 'heroicon-o-sparkles', 'parent_id' => $belediyeHizmetleri->id]);
        Category::create(['name' => 'Zabıta', 'icon' => 'heroicon-o-shield-check', 'parent_id' => $belediyeHizmetleri->id]);
        Category::create(['name' => 'İmar ve Şehircilik', 'icon' => 'heroicon-o-building-office', 'parent_id' => $belediyeHizmetleri->id]);
        
        // Altyapı alt kategorileri
        Category::create(['name' => 'Yol Çalışmaları', 'icon' => 'heroicon-o-map', 'parent_id' => $altyapi->id]);
        Category::create(['name' => 'Su ve Kanalizasyon', 'icon' => 'heroicon-o-cog-6-tooth', 'parent_id' => $altyapi->id]);
        Category::create(['name' => 'Elektrik', 'icon' => 'heroicon-o-bolt', 'parent_id' => $altyapi->id]);
        
        // Sosyal Projeler alt kategorileri
        Category::create(['name' => 'Kültür ve Sanat', 'icon' => 'heroicon-o-academic-cap', 'parent_id' => $sosyalProjeler->id]);
        Category::create(['name' => 'Eğitim', 'icon' => 'heroicon-o-book-open', 'parent_id' => $sosyalProjeler->id]);
        Category::create(['name' => 'Sağlık', 'icon' => 'heroicon-o-heart', 'parent_id' => $sosyalProjeler->id]);

        // Kullanıcı ekle
        $this->call([
            UserSeeder::class,
        ]);
    }
}
