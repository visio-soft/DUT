<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
    // Üst (test) kategori - "Ana Kategori"
    $ana = Category::create(['name' => 'Ana Kategori']);

    // Ana kategoriler (Ana Kategori'nin altı olacak)
    $tema      = Category::create(['name' => 'Tema', 'parent_id' => $ana->id]);
    $belediye  = Category::create(['name' => 'Belediye', 'parent_id' => $ana->id]);
    $iscilik   = Category::create(['name' => 'İşçilik', 'parent_id' => $ana->id]);
    $park      = Category::create(['name' => 'Park', 'parent_id' => $ana->id]);
    $metro     = Category::create(['name' => 'Metro', 'parent_id' => $ana->id]);

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
