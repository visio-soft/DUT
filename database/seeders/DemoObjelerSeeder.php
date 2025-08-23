<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obje;

class DemoObjelerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ağaç
        $agac = Obje::create(['isim' => 'Ağaç']);
        $agac->addMedia(public_path('favicon.ico'))->preservingOriginal()->toMediaCollection('images');

        // 2. Çiçek
        $cicek = Obje::create(['isim' => 'Çiçek']);
        $cicek->addMedia(public_path('favicon.ico'))->preservingOriginal()->toMediaCollection('images');

        // 3. Havuz
        $havuz = Obje::create(['isim' => 'Havuz']);
        $havuz->addMedia(public_path('favicon.ico'))->preservingOriginal()->toMediaCollection('images');
    }
}
