<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class DokuObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // Doku kategorisindeki objeler
        $dokuObjeler = [
            'Çim Taşı' => '1 pavé-herbe.jpg',
            'Geçirgen Taş 2' => '2 permeable-pavers2.jpg',
            'Çim Beton' => '3 grasscrete.jpg',
            'Yosun' => '4 moss.jpg',
            'Çim 2' => '5 grass2.jpg',
            'Çim' => '6 grass.jpg',
            'Zemin Boyası' => '7 peinturesol.jpg',
            'Doku 0' => '8 quito-texture-0.jpg',
            'Doku 1' => '9 quito-texture-1.jpg',
            'Mavi Kauçuk' => '10 blue-rubber.jpg',
            'Doku 9' => '11 quito-texture-9.jpg',
            'Su' => '12 water.jpg',
            'Su 2' => '13 water-2.jpg',
            'Kum Tepeleri' => '14 sand-dunes.jpg',
            'Kum Dokusu' => '15 sand-texture.jpg',
            'Çakıl' => '16 gravel.jpg',
            'Beyaz Zemin' => '17 sol-blanc.jpg',
            'Doku 13' => '18 quito-texture-13.jpg',
            'Mavi Taş' => '19 blue-stone.jpg',
            'Döşeme' => '20 dallage.jpg',
            'Tuğla' => '21 briquette.jpg',
            'Geçirgen Taş' => '22 permeable-pavers.jpg',
            'Arnavut Kaldırımı' => '23 cobblestone.jpg',
            'Doku 8' => '24 quito-texture-8.jpg',
            'Doku 3' => '25 quito-texture-3.jpg',
            'Ahşap' => '26 wood.jpg',
            'Odun Yongası' => '27 woodchips.jpg',
        ];

        foreach ($dokuObjeler as $name => $filename) {
            $obje = Obje::create([
                'name' => $name,
                'category' => 'doku'
            ]);

            $imagePath = public_path('media/doku-objeler/' . $filename);
            
            if (file_exists($imagePath)) {
                $obje->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
