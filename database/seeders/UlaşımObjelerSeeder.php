<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class UlaşımObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // Ulaşım kategorisindeki objeler
        $ulasimObjeler = [
            'Dalga Çizgi' => '1 Sol vague.png',
            'Kırmızı Şerit' => '2 red lane.png',
            'Kırmızı Bisiklet Yolu' => '3 red bicycle lane.png',
            'Yaya Yolu' => '4 pathway1.png',
            'Yaya Geçidi' => '5 pedestrian crossing.png',
            'Ulaşım 16' => '6 quito-mobility-17.png',
            'Ulaşım 15' => '7 quito-mobility-16.png',
            'Çocuklar' => '8 enfants-de-dos_1080.png',
            'Klasik Otobüs Durağı' => '9 abribus-classique.png',
            'Modern Otobüs Durağı' => '10 abribus-design1.png',
            'Harita' => '11 map.png',
            'Otobüs Kullanıcısı' => '12 nondriver_bus.png',
            'Ulaşım 3' => '13 quito-mobility-3.png',
            'Yaşlı Kişi' => '14 personne-agee.png',
            'Yaşlı İnsan' => '15 old person.png',
            'Klasik Bank' => '16 Banc-classique.png',
            'Engelli Kişi' => '17 personne-handicapée.png',
            'Koşucu Adam' => '18 man-runner.png',
            'Sporcu Kişi' => '19 personne-sportive.png',
            'Tek Tekerlekli Araç' => '20 solowheel.png',
            'Kaykayçı' => '21 skater.png',
            'Ulaşım 9' => '22 quito-mobility-9.png',
            'Yürüyüş' => '23 hikking.png',
            'Ulaşım 7' => '24 quito-mobility-7.png',
            'İş Bisikletçisi' => '25 personne-velo-affaires.png',
            'Bisikletçi' => '26 personne-velo.png',
            'Üç Tekerlekli Bisiklet' => '27 tricycle3.png',
            'Ulaşım 11' => '28 quito-mobility-11.png',
            'Ulaşım 37' => '29 quito-mobility-37.png',
            'Ulaşım 26' => '30 quito-mobility-26.png',
            'Bisiklet Parkı' => '31 range-velos-design.png',
            'Bisiklet Taksi' => '32 velo-taxi.png',
            'Tek Bisiklet' => '33 velo-seul.png',
            'Paylaşımlı Bisiklet' => '34 velib.png',
            'U Bisiklet' => '35 u bike.png',
            'Araba 1' => '36 voiture-1.png',
            'Arabalar' => '37 cars.png',
            'Araba 2' => '38 voiture-2.png.png',
        ];

        foreach ($ulasimObjeler as $name => $filename) {
            $obje = Obje::create([
                'name' => $name,
                'category' => 'ulasim'
            ]);

            $imagePath = public_path('media/ulasim-objeler/' . $filename);
            
            if (file_exists($imagePath)) {
                $obje->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
