<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class YaşamObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // Yaşam kategorisindeki objeler
        $yasamObjeler = [
            'Oturma Sandalyeleri' => '1 assises-chaises.png',
            'Yaşam 49' => '2 quito-life-49.png',
            'Teras' => '3 terrasse.png',
            'Yemek Masası' => '4 dinning table.png',
            'Seyyar Satıcı' => '5 peddler.png',
            'Garson' => '6 tjener.png',
            'Yemek Tabelası' => '7 eat sign.png',
            'Çalışan' => '8 working.png',
            'Yaşam 37' => '9 quito-life-37.png',
            'Yaşam Hareket 1' => '10 quito-mobility-1.png',
            'Ahşap Bank Kompleksi' => '11 Banc-bois-complexe.png',
            'Organik Ahşap Mobilya' => '12 organicwoodfuniture.png',
            'Yemek Kamyonu' => '13 foodtruck.png',
            'Food Truck' => '14 food-truck.png',
            'Bibliyobus' => '15 bibliobus.png',
            'D16-1' => '16 D16-1..png',
            'Satranç' => '17 echecs.png',
            'Çocuk Sal Oyunu' => '18 jeu-enfant-radeau.png',
            'Yaşam 13' => '19 quito-life-13.png',
            'Aile' => '20 family.png',
            'Yaşam 15' => '21 quito-life-15.png',
            'Oyun Kütükleri' => '22 playground-stumps.png',
            'Mikado' => '23 mikado.png',
            'Çocuk ve Oyun Alanı' => '24 child-and-playground.png',
            'Yaşam 32' => '25 quito-life-32.png',
            'Oyun Alanı 2' => '26 playground2.png',
            'Yaşam 16' => '27 quito-life-16.png',
            'Oyuncu Çocuk' => '28 enfant-joueur.png',
            'Çocuk Grubu 1' => '29 groupe d\'enfants 1.png',
            'Oyun Alanı' => '30 playground.png',
            'Yaşam 48' => '31 quito-life-48.png',
            'Seksek' => '32 marelle.png',
            'Genç ve Salıncak' => '33 youngster and swing.png',
            'Hafif Mobilya Dinlenme' => '34 light funiture relax.png',
            'Yaşam 22' => '35 quito-life-22.png',
            'BMX' => '36 BMX.png',
            'Ping Pong' => '37 pingpong.png',
            'Breakdans Yapan Kişi' => '38 personne-breakdance.png.png',
            'Yoga' => '39 yoga.png',
            'Kas Geliştirme Aleti 4' => '40 Structure musculation 4.png',
            'Yaşam 8' => '41 quito-life-8.png',
            'Yaşam 42' => '42 quito-life-42.png',
            'Yaşam 44' => '43 quito-life-44.png',
            'Yaşam 30' => '44 quito-life-30.png',
            'Kaykay Çocuk' => '45 enfant-skate.png',
            'Yaşam 41' => '46 quito-life-41.png',
            'Kaykay Sürme' => '47 skateing.png',
            'Kaykay Parkı' => '48 skatepark.png',
            'Kaykay' => '49 skate.png',
            'Tırmanma Duvarı' => '50 climing wall.png',
            'Kaya Tırmanışı' => '51 rock-climbing.png',
        ];

        foreach ($yasamObjeler as $name => $filename) {
            $obje = Obje::create([
                'name' => $name,
                'category' => 'yasam'
            ]);

            $imagePath = public_path('media/yasam-objeler/' . $filename);
            
            if (file_exists($imagePath)) {
                $obje->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
