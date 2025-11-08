<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use App\Models\Category;
use App\Models\Project;
use App\Models\Oneri;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create Main Category
        $mainCategory = MainCategory::create([
            'name' => 'Altyapı Projeleri',
            'description' => 'Şehir altyapısını geliştirmeye yönelik ana kategori',
            'aktif' => true,
        ]);

        // Create Category under Main Category
        $category = Category::create([
            'name' => 'Ulaşım Projeleri',
            'description' => 'Şehir ulaşım sistemlerini iyileştirme',
            'main_category_id' => $mainCategory->id,
            'aktif' => true,
        ]);

        // Create Project under Category
        $project = Project::create([
            'name' => 'Metro Hat Genişletme',
            'description' => 'Mevcut metro hatlarının genişletilmesi projesi',
            'category_id' => $category->id,
            'aktif' => true,
        ]);

        // Create Oneri (Suggestion) under Project
        $oneri = Oneri::create([
            'title' => 'Kadıköy-Kartal Metro Hattı',
            'description' => 'Kadıköy ile Kartal arasında yeni metro hattı önerisi',
            'category_id' => $category->id,
            'project_id' => $project->id,
            'created_by_id' => $user->id,
            'min_budget' => 500000000,
            'max_budget' => 750000000,
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
        ]);

        echo "\n✅ Test hierarchy created successfully!\n";
        echo "📊 Hierarchy: {$mainCategory->name} > {$category->name} > {$project->name} > {$oneri->title}\n\n";
    }
}
