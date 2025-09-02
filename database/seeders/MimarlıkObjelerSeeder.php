<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class MimarlıkObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // Mimarlık kategorisindeki objeler
        $mimarlikObjeler = [
            'Modern Otobüs Durağı 2' => '1 abribus-design2.png',
            'Gölgelik Çatı' => '2 auvent-design.png',
            'Gölge Yelkenleri' => '3 shade-sails.png',
            'Ev Yolculuğu 18' => '4 voyage-of-houses-18.png',
            'Ev Yolculuğu 3' => '5 voyage-of-houses-3.png',
            'Ev Yolculuğu 8' => '6 voyage-of-houses-8.png',
            'Bina' => '7 bygning.png',
            'Mimari Yapı' => '8 91324_1_xl.png',
            'Ofis Binası 2' => '9 office_building2.png',
            'Otopark Garajı 2' => '10 parking-garage2.png',
            '2 Katlı Bina' => '11 edificio_2pisos-buildings.png',
            'Park Pavyonu' => '12 pabellón_parque-buildings.png',
            'Evler' => '13 casas-buildings.png',
            'Akustik Sahne' => '14 concha_acustica-buildings.png',
            'Yeşil Kutu' => '15 green box.png',
        ];

        foreach ($mimarlikObjeler as $name => $filename) {
            $obje = Obje::create([
                'name' => $name,
                'category' => 'mimari'
            ]);

            $imagePath = public_path('media/mimarlik-objeler/' . $filename);
            
            if (file_exists($imagePath)) {
                $obje->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
