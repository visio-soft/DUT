<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Flat categories (no hierarchy)
        Category::create(['name' => 'Ağaçlandırma']);
        Category::create(['name' => 'Çevre Düzenleme']);
        Category::create(['name' => 'İmar']);
        Category::create(['name' => 'Temizlik']);
        Category::create(['name' => 'Usta']);
        Category::create(['name' => 'İşçi']);
        Category::create(['name' => 'Çocuk Parkı']);
        Category::create(['name' => 'Otopark']);
        Category::create(['name' => 'Hat 1']);
        Category::create(['name' => 'Hat 2']);
    }
}
