<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class DoğaObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // Doğa kategorisindeki objeler
        $dogaObjeler = [
            'Tall Grass' => '1 Tall-Grass-8.png',
            'Bahçe' => '2 images (4).png',
            'Çim' => '3 grass.png',
            'Bitkisel Bariyer' => '3 barrière-végétale.png',
            'Doğa Manzarası' => '5 quito-nature-18.png',
            'Doğa Görünümü' => '6 quito-nature-19.png',
            'Yağmur Bahçesi 2' => '7 jardin_de_lluvia_2-sbn.png',
            'Nehir' => '8 river.png',
            'Alıkoyma Kanalı' => '9 zanja_de_retención-sbn.png',
            'Su Bahçesi' => '10 jardin_inundable-sbn.png',
            'Su Parkı' => '11 parque inundable-sbn.png',
            'İnfiltrasyon Kanalı' => '12 zanja_de_infiltracion-sbn.png',
            'Dış Mekan' => '13 ext.png',
            'Küçük Ağaç Desteği' => '14 arbre-petit-support.png',
            'Kentsel Ağaçlandırma 1' => '15 arbolado_urbano_1-sbn.png',
            'Lambda Ağaç' => '16 arbre-lambda.png',
            'Yağmur Bahçesi 1' => '17 jardin_de_lluvia_1-sbn.png',
            'Doğa 13' => '18 quito-nature-13.png',
            'Ağaç 2' => '19 tree2.png',
            'Gürgen' => '20 charme.png',
            'Kayın' => '21 hetre.png',
            'Küçük Meşe' => '22 chene-petit.png',
            'Ihlamur' => '23 tilleul.png',
            'Büyük Meşe' => '24 chêne-large.png',
            'Ağaç 1' => '25 tree1.png',
            'Kentsel Ağaçlandırma 2' => '26 arbolado_urbano_2-sbn.png',
            'Doğa 1' => '27 quito-nature-1.png',
            'Mikro Orman' => '28 microbosque-sbn.png',
            'Yağmur Bahçesi 3' => '29 jardin_de_lluvia_3-sbn.png',
            'Ekmek Fırını 1' => '30 Brotoften_1.png',
            'Domates Bitkileri' => '31 pieds-tomates.png.png',
            'Hidroponik Tarım' => '32 cultivo_hidroponico-sbn.png',
            'L3-1' => '33 L3-1.png',
            'Çiçekler' => '34 flowers.png',
            'Kompost' => '35 compostera-sbn.png',
            'Modüler Fidelik' => '36 vivero_modular-sbn.png',
            'Saksı' => '37 planter.png',
            'Dikey Bahçe' => '38 vertical garden.png',
            'Bitkiler' => '39 plants.png',
            'Çiçek Duvarı' => '40 wall of flowers.png',
            'Yeşil Duvar' => '41 green wall.png.png',
            'Bitki Cephesi 1' => '42 fachada_vegetal_1-sbn.png',
            'Böcek Habitatı' => '43 habitat_insectos-sbn.png',
            'Kuş Habitatı' => '44 habitat_aves-sbn.png',
            'Doğa 24' => '45 quito-nature-24.png',
            'Kentsel Çiftlik' => '46 ferme-urbaine.png',
            'Kentsel Çiftlik 2' => '47 ferme-urbaine.png',
            'Doğa 8' => '48 quito-nature-8.png',
            'Doğa 26' => '49 quito-nature-26.png',
            'Keçi' => '50 goat.png',
            'Doğa 10' => '51 quito-nature-10.png',
            'Martı' => '52 seagulls.png',
            'Güneş Paneli' => '53 panneau-solaire.png',
            'Güneş Panelleri' => '54 solar-panels.png',
            'Rüzgar Türbini' => '55 eolienne.png',
            'Rüzgar Türbini 2' => '56 wind-turbine.png',
            'Doğa 30' => '57 quito-nature-30.png',
            'Geri Dönüşüm Kutusu' => '58 recylingbin.png',
        ];

        foreach ($dogaObjeler as $name => $filename) {
            $obje = Obje::create([
                'name' => $name,
                'category' => 'doga'
            ]);

            $imagePath = public_path('media/doga-objeler/' . $filename);
            
            if (file_exists($imagePath)) {
                $obje->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
