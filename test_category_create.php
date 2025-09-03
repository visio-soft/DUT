<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
// Some service providers expect the 'files' binding. Bind a Filesystem
// instance into the container before booting to avoid a missing binding
// when running this script standalone.
$app->instance('files', new \Illuminate\Filesystem\Filesystem());
// Set Facade application root for providers that use facades during boot.
\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

// Bootstrap the framework using the Console Kernel so config, DB and
// other core services are properly initialized.
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

// Şu anki zamanı İstanbul saat dilimine göre al
$now = Carbon::now('Europe/Istanbul');

echo "Şu anki zaman: " . $now->format('Y-m-d H:i:s') . "\n";

// Test kategorisi oluştur - 2 dakika sonra bitiş
$testCategory = Category::create([
    'name' => 'Test Projesi - Zaman Testi',
    'start_datetime' => $now->format('Y-m-d H:i:s'),
    'end_datetime' => $now->copy()->addMinutes(1)->format('Y-m-d H:i:s')
]);

echo "Test kategorisi oluşturuldu!\n";
echo "ID: " . $testCategory->id . "\n";
echo "Başlangıç: " . $testCategory->start_datetime . "\n";
echo "Bitiş: " . $testCategory->end_datetime . "\n";

// İlk kullanıcıyı bul
$user = User::first();
if (!$user) {
    echo "Kullanıcı bulunamadı!\n";
    exit;
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

echo "\nTest projesi oluşturuldu!\n";
echo "ID: " . $testProject->id . "\n";
echo "Başlık: " . $testProject->title . "\n";
echo "Kategori: " . $testProject->category->name . "\n";

// Test projesi için tasarım oluştur
$testDesign = \App\Models\ProjectDesign::create([
    'project_id' => $testProject->id,
    'created_by_id' => $user->id,
    'design_description' => 'Test tasarımı - Zaman sayacını test etmek için oluşturulmuştur. Bu tasarım sadece test amaçlıdır ve 2 dakika sonra oylama süresi dolacak.',
    // design_data is required (JSON). Provide a minimal payload.
    'design_data' => [
        'title' => 'Test Tasarımı',
        'notes' => 'Otomatik oluşturulmuş test tasarımı.'
    ],
]);

echo "\nTest tasarımı oluşturuldu!\n";
echo "Tasarım ID: " . $testDesign->id . "\n";
echo "Açıklama: " . $testDesign->design_description . "\n";

echo "\nTest tamamlandı! Şimdi web sayfasına gidip geri sayımı kontrol edebilirsiniz.\n";
echo "URL: /admin/project-designs-gallery\n";
echo "Zaman sayacı 2 dakika içinde 'Oylama süresi doldu' olarak değişecek.\n";
