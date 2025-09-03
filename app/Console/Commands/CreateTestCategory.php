<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectDesign;
use App\Models\User;
use Carbon\Carbon;

class CreateTestCategory extends Command
{
    protected $signature = 'test:create-category';
    protected $description = 'Test için zaman sayacı olan kategori ve proje oluştur';

    public function handle()
    {
        // Şu anki zamanı İstanbul saat dilimine göre al
        $now = Carbon::now('Europe/Istanbul');

        $this->info("Şu anki zaman: " . $now->format('Y-m-d H:i:s'));

        // Test kategorisi oluştur - 2 dakika sonra bitiş
        $testCategory = Category::create([
            'name' => 'Test Projesi - Zaman Testi',
            'start_datetime' => $now->format('Y-m-d H:i:s'),
            'end_datetime' => $now->copy()->addMinutes(2)->format('Y-m-d H:i:s')
        ]);

        $this->info("Test kategorisi oluşturuldu!");
        $this->info("ID: " . $testCategory->id);
        $this->info("Başlangıç: " . $testCategory->start_datetime);
        $this->info("Bitiş: " . $testCategory->end_datetime);

        // İlk kullanıcıyı bul
        $user = User::first();
        if (!$user) {
            $this->error("Kullanıcı bulunamadı!");
            return;
        }

        // Test projesi oluştur
        $testProject = Project::create([
            'category_id' => $testCategory->id,
            'created_by_id' => $user->id,
            'title' => 'Test Projesi - Zaman Sayacı',
            'description' => 'Bu proje zaman sayacını test etmek için oluşturulmuştur. 2 dakika sonra süresi dolacak.',
            'start_date' => $now->format('Y-m-d'),
            'end_date' => $now->copy()->addMinutes(2)->format('Y-m-d'),
            'budget' => 50000,
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'neighborhood' => 'Moda',
            'address' => 'Test Adresi',
            'design_completed' => false
        ]);

        $this->info("\nTest projesi oluşturuldu!");
        $this->info("ID: " . $testProject->id);
        $this->info("Başlık: " . $testProject->title);
        $this->info("Kategori: " . $testProject->category->name);

        // Test projesi için tasarım oluştur
        $testDesign = \App\Models\ProjectDesign::create([
            'project_id' => $testProject->id,
            'design_data' => [
                'project_id' => $testProject->id,
                'elements' => [
                    [
                        'obje_id' => 1,
                        'x' => 100,
                        'y' => 100,
                        'width' => 50,
                        'height' => 50,
                        'scale' => 1.0
                    ],
                    [
                        'obje_id' => 2,
                        'x' => 200,
                        'y' => 150,
                        'width' => 60,
                        'height' => 60,
                        'scale' => 1.2
                    ]
                ],
                'timestamp' => $now->toIso8601String(),
                'total_elements' => 2
            ]
        ]);

        $this->info("\nTest tasarımı oluşturuldu!");
        $this->info("Tasarım ID: " . $testDesign->id);
        $this->info("Element sayısı: " . count($testDesign->design_data['elements']));
        $this->info("Tasarım timestamp: " . $testDesign->design_data['timestamp']);

        $this->info("\nTest tamamlandı! Şimdi web sayfasına gidip geri sayımı kontrol edebilirsiniz.");
        $this->info("URL: /admin/project-designs-gallery");
        $this->info("Zaman sayacı 2 dakika içinde 'Oylama süresi doldu' olarak değişecek.");
    }
}
