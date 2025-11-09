<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProjectGroup;
use Illuminate\Database\Seeder;

class ProjectGroupSeeder extends Seeder
{
    public function run(): void
    {
        // Get some categories to assign to project groups
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        // Create project groups for different categories
        $projectGroups = [
            ['name' => 'Kent Yeşillendirme Projesi', 'category_id' => $categories->where('name', 'Ağaçlandırma')->first()?->id ?? $categories->first()->id],
            ['name' => 'Park Ağaçlandırma', 'category_id' => $categories->where('name', 'Ağaçlandırma')->first()?->id ?? $categories->first()->id],
            ['name' => 'Cadde Ağaçlandırma', 'category_id' => $categories->where('name', 'Ağaçlandırma')->first()?->id ?? $categories->first()->id],

            ['name' => 'Mahalle Düzenleme', 'category_id' => $categories->where('name', 'Çevre Düzenleme')->first()?->id ?? $categories->first()->id],
            ['name' => 'Sahil Düzenleme', 'category_id' => $categories->where('name', 'Çevre Düzenleme')->first()?->id ?? $categories->first()->id],

            ['name' => 'Yeni Çocuk Parkı', 'category_id' => $categories->where('name', 'Çocuk Parkı')->first()?->id ?? $categories->first()->id],
            ['name' => 'Park Yenileme', 'category_id' => $categories->where('name', 'Çocuk Parkı')->first()?->id ?? $categories->first()->id],

            ['name' => 'Otopark Yapımı', 'category_id' => $categories->where('name', 'Otopark')->first()?->id ?? $categories->first()->id],
            ['name' => 'Otopark Genişletme', 'category_id' => $categories->where('name', 'Otopark')->first()?->id ?? $categories->first()->id],
        ];

        foreach ($projectGroups as $group) {
            if ($group['category_id']) {
                ProjectGroup::create($group);
            }
        }

        $this->command->info('Project groups created successfully!');
    }
}
