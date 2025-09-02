<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class SanatObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // Sanat kategorisindeki objeler
        $sanatObjeler = [
            'Muwai' => '1 Muwai.png',
            'Muwai 2' => '2 Muwai 2.png',
            'Muwai 3' => '3 Muwai 2.png',
            'Yere Yapılan Resim' => '4 painting on ground.png',
            'Hayat 12' => '5 quito-life-12.png',
            'Hayat 40' => '6 quito-life-40.png',
            'Mavi Ahtapot' => '7 poulpe bleu.png',
            'Bay Ahtapot' => '8 Monsieur poulpe.png',
            'Hayat 11' => '9 quito-life-11.png',
            'Yapısal Sanat' => '10 structure.png',
            'D7-1' => '11 D7-1.png',
            'Banklar 2' => '12 Bancs 2.png',
            'Banklar &' => '13 Bancs &.png',
            'Direk' => '14 poteau_1080.png',
            'Totem' => '15 totem_1080.png',
            'A Harfi' => '16 a.png',
            'Randers Gökyürüyüşü' => '17 Randers Skywalk.png',
            'Merdiven Seyir Noktası' => '18 stair viewpoint.png',
            'Işıklı Bank' => '19 lighting bench.png',
        ];

        foreach ($sanatObjeler as $name => $filename) {
            $obje = Obje::create([
                'name' => $name,
                'category' => 'sanat'
            ]);

            $imagePath = public_path('media/sanat-objeler/' . $filename);
            
            if (file_exists($imagePath)) {
                $obje->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
