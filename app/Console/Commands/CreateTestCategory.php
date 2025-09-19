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
            'address' => 'Test Adresi'
        ]);

        $this->info("\nTest projesi oluşturuldu!");
        $this->info("ID: " . $testProject->id);
        $this->info("Başlık: " . $testProject->title);
        $this->info("Kategori: " . $testProject->category->name);

        // Design functionality removed - no longer creating test designs
        $this->info("\nTest tamamlandı! Proje başarıyla oluşturuldu.");
    }
}
