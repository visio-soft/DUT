<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
    // Ana kategoriler
    $tema      = Category::create(['name' => 'Tema']);
    $belediye  = Category::create(['name' => 'Belediye']);
    $iscilik   = Category::create(['name' => 'İşçilik']);
    $park      = Category::create(['name' => 'Park']);
    $metro     = Category::create(['name' => 'Metro']);

    // Alt kategoriler
    Category::create(['name' => 'Ağaçlandırma', 'parent_id' => $tema->id]);
    Category::create(['name' => 'Çevre Düzenleme', 'parent_id' => $tema->id]);
    Category::create(['name' => 'İmar', 'parent_id' => $belediye->id]);
    Category::create(['name' => 'Temizlik', 'parent_id' => $belediye->id]);
    Category::create(['name' => 'Usta', 'parent_id' => $iscilik->id]);
    Category::create(['name' => 'İşçi', 'parent_id' => $iscilik->id]);
    Category::create(['name' => 'Çocuk Parkı', 'parent_id' => $park->id]);
    Category::create(['name' => 'Otopark', 'parent_id' => $park->id]);
    Category::create(['name' => 'Hat 1', 'parent_id' => $metro->id]);
    Category::create(['name' => 'Hat 2', 'parent_id' => $metro->id]);
    }
}
