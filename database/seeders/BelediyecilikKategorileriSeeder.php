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
                'parent_id' => null,
            ],
            [
                'name' => 'Çevre ve Temizlik',
                'icon' => 'heroicon-o-trash',
                'parent_id' => null,
            ],
            [
                'name' => 'Ulaşım ve Trafik',
                'icon' => 'heroicon-o-truck',
                'parent_id' => null,
            ],
            [
                'name' => 'Park ve Bahçe',
                'icon' => 'heroicon-o-squares-2x2',
                'parent_id' => null,
            ],
            [
                'name' => 'Sosyal Hizmetler',
                'icon' => 'heroicon-o-users',
                'parent_id' => null,
            ],
            [
                'name' => 'Kültür ve Sanat',
                'icon' => 'heroicon-o-musical-note',
                'parent_id' => null,
            ],
            [
                'name' => 'Spor Tesisleri',
                'icon' => 'heroicon-o-trophy',
                'parent_id' => null,
            ],
            [
                'name' => 'İmar ve Planlama',
                'icon' => 'heroicon-o-building-office-2',
                'parent_id' => null,
            ],
            [
                'name' => 'Su ve Kanalizasyon',
                'icon' => 'heroicon-o-beaker',
                'parent_id' => null,
            ],
            [
                'name' => 'Elektrik ve Aydınlatma',
                'icon' => 'heroicon-o-light-bulb',
                'parent_id' => null,
            ],
            [
                'name' => 'Sosyal Tesis',
                'icon' => 'heroicon-o-home-modern',
                'parent_id' => null,
            ],
            [
                'name' => 'Mezarlık Hizmetleri',
                'icon' => 'heroicon-o-building-library',
                'parent_id' => null,
            ],
            [
                'name' => 'Zabıta Hizmetleri',
                'icon' => 'heroicon-o-shield-check',
                'parent_id' => null,
            ],
            [
                'name' => 'İtfaiye Hizmetleri',
                'icon' => 'heroicon-o-fire',
                'parent_id' => null,
            ],
            [
                'name' => 'Sağlık Hizmetleri',
                'icon' => 'heroicon-o-heart',
                'parent_id' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('Belediyecilik kategorileri başarıyla eklendi!');
    }
}
