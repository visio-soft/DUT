<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Projeler (tarih + saat aralıkları ile)
        Category::create([
            'name' => 'Ağaçlandırma',
            'start_datetime' => '2025-01-01 08:00:00',
            'end_datetime' => '2025-12-31 17:00:00',
        ]);

        Category::create([
            'name' => 'Çevre Düzenleme',
            'start_datetime' => '2025-03-01 09:00:00',
            'end_datetime' => '2025-11-30 18:00:00',
        ]);

        Category::create([
            'name' => 'İmar',
            'start_datetime' => '2025-01-15 08:30:00',
            'end_datetime' => '2025-12-15 17:30:00',
        ]);

        Category::create([
            'name' => 'Temizlik',
            'start_datetime' => '2025-01-01 06:00:00',
            'end_datetime' => '2025-12-31 14:00:00',
        ]);

        Category::create([
            'name' => 'Usta',
            'start_datetime' => '2025-02-01 08:00:00',
            'end_datetime' => '2025-11-30 17:00:00',
        ]);

        Category::create([
            'name' => 'İşçi',
            'start_datetime' => '2025-02-01 07:00:00',
            'end_datetime' => '2025-11-30 16:00:00',
        ]);

        Category::create([
            'name' => 'Çocuk Parkı',
            'start_datetime' => '2025-04-01 10:00:00',
            'end_datetime' => '2025-10-31 19:00:00',
        ]);

        Category::create([
            'name' => 'Otopark',
            'start_datetime' => '2025-01-01 00:00:00',
            'end_datetime' => '2025-12-31 23:59:00',
        ]);

        Category::create([
            'name' => 'Hat 1',
            'start_datetime' => '2025-01-01 06:00:00',
            'end_datetime' => '2025-12-31 23:00:00',
        ]);

        Category::create([
            'name' => 'Hat 2',
            'start_datetime' => '2025-01-01 06:30:00',
            'end_datetime' => '2025-12-31 22:30:00',
        ]);
    }
}
