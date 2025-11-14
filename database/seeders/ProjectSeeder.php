<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user and project groups
        $adminUser = User::role(['admin', 'super_admin'])->first();
        
        if (!$adminUser) {
            $this->command->warn('No admin user found. Please run AdminUserSeeder first.');
            return;
        }

        $projectGroups = ProjectGroup::all();

        if ($projectGroups->isEmpty()) {
            $this->command->warn('No project groups found. Please run ProjectGroupSeeder first.');
            return;
        }

        // Sample projects
        $projects = [
            [
                'title' => 'Ataşehir Park Ağaçlandırma Projesi',
                'description' => 'Ataşehir bölgesindeki parkların ağaçlandırılması ve yeşil alan artırılması projesi.',
                'status' => 'active',
                'start_date' => '2025-03-01',
                'end_date' => '2025-09-30',
                'min_budget' => 150000.00,
                'max_budget' => 250000.00,
                'city' => 'İstanbul',
                'district' => 'Ataşehir',
                'latitude' => 40.9826,
                'longitude' => 29.1196,
                'address' => 'Ataşehir Parkı',
                'created_by_id' => $adminUser->id,
            ],
            [
                'title' => 'Kadıköy Sahil Düzenleme Projesi',
                'description' => 'Kadıköy sahil şeridinin düzenlenmesi, yeşil alan ve oturma alanlarının artırılması.',
                'status' => 'active',
                'start_date' => '2025-04-01',
                'end_date' => '2025-10-31',
                'min_budget' => 500000.00,
                'max_budget' => 750000.00,
                'city' => 'İstanbul',
                'district' => 'Kadıköy',
                'latitude' => 40.9910,
                'longitude' => 29.0242,
                'address' => 'Kadıköy Sahili',
                'created_by_id' => $adminUser->id,
            ],
            [
                'title' => 'Üsküdar Çocuk Parkı Projesi',
                'description' => 'Üsküdar bölgesinde yeni çocuk parkı yapımı ve oyun alanları düzenlenmesi.',
                'status' => 'draft',
                'start_date' => '2025-05-01',
                'end_date' => '2025-12-31',
                'min_budget' => 200000.00,
                'max_budget' => 350000.00,
                'city' => 'İstanbul',
                'district' => 'Üsküdar',
                'latitude' => 41.0216,
                'longitude' => 29.0256,
                'address' => 'Üsküdar Merkez',
                'created_by_id' => $adminUser->id,
            ],
            [
                'title' => 'Beşiktaş Otopark Genişletme Projesi',
                'description' => 'Beşiktaş bölgesinde mevcut otoparkların genişletilmesi ve yeni otopark alanları oluşturulması.',
                'status' => 'active',
                'start_date' => '2025-02-15',
                'end_date' => '2025-08-15',
                'min_budget' => 300000.00,
                'max_budget' => 450000.00,
                'city' => 'İstanbul',
                'district' => 'Beşiktaş',
                'latitude' => 41.0422,
                'longitude' => 29.0081,
                'address' => 'Beşiktaş Merkez',
                'created_by_id' => $adminUser->id,
            ],
            [
                'title' => 'Maltepe Cadde Ağaçlandırma',
                'description' => 'Maltepe ana caddelerin ağaçlandırılması ve gölgelik alanların artırılması.',
                'status' => 'completed',
                'start_date' => '2024-09-01',
                'end_date' => '2024-12-31',
                'min_budget' => 100000.00,
                'max_budget' => 180000.00,
                'city' => 'İstanbul',
                'district' => 'Maltepe',
                'latitude' => 40.9310,
                'longitude' => 29.1307,
                'address' => 'Maltepe Caddesi',
                'created_by_id' => $adminUser->id,
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Randomly attach 1-2 project groups
            $randomGroups = $projectGroups->random(rand(1, min(2, $projectGroups->count())));
            $project->projectGroups()->attach($randomGroups->pluck('id'));
            
            $this->command->info("Project created: {$project->title}");
        }

        $this->command->info('Projects created successfully!');
    }
}
