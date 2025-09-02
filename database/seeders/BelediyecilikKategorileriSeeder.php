<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class BelediyecilikKategorileriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Altyapı Hizmetleri',
                'icon' => 'heroicon-o-wrench-screwdriver',
            ],
            [
                'name' => 'Çevre ve Temizlik',
                'icon' => 'heroicon-o-trash',
            ],
            [
                'name' => 'Ulaşım ve Trafik',
                'icon' => 'heroicon-o-truck',
            ],
            [
                'name' => 'Park ve Bahçe',
                'icon' => 'heroicon-o-squares-2x2',
            ],
            [
                'name' => 'Sosyal Hizmetler',
                'icon' => 'heroicon-o-users',
            ],
            [
                'name' => 'Kültür ve Sanat',
                'icon' => 'heroicon-o-musical-note',
            ],
            [
                'name' => 'Spor Tesisleri',
                'icon' => 'heroicon-o-trophy',
            ],
            [
                'name' => 'İmar ve Planlama',
                'icon' => 'heroicon-o-building-office-2',
            ],
            [
                'name' => 'Su ve Kanalizasyon',
                'icon' => 'heroicon-o-beaker',
            ],
            [
                'name' => 'Elektrik ve Aydınlatma',
                'icon' => 'heroicon-o-light-bulb',
            ],
            [
                'name' => 'Doğalgaz',
                'icon' => 'heroicon-o-fire',
            ],
            [
                'name' => 'Temizlik Hizmetleri',
                'icon' => 'heroicon-o-sparkles',
            ],
            [
                'name' => 'Sosyal Tesis',
                'icon' => 'heroicon-o-home-modern',
            ],
            [
                'name' => 'Mezarlık Hizmetleri',
                'icon' => 'heroicon-o-building-library',
            ],
            [
                'name' => 'Zabıta Hizmetleri',
                'icon' => 'heroicon-o-shield-check',
            ],
            [
                'name' => 'İtfaiye Hizmetleri',
                'icon' => 'heroicon-o-fire',
            ],
            [
                'name' => 'Sağlık Hizmetleri',
                'icon' => 'heroicon-o-heart',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
