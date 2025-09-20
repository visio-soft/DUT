<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->instance('files', new \Illuminate\Filesystem\Filesystem());
\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "Testing Category model with location fields...\n";

try {
    // Create a test category with location
    $category = Category::create([
        'name' => 'Test Proje - Konum Testi',
        'description' => 'Bu proje konum özelliklerini test etmek için oluşturulmuştur.',
        'start_datetime' => now(),
        'end_datetime' => now()->addDays(30),
        'district' => 'Kadıköy',
        'neighborhood' => 'Fenerbahçe'
    ]);

    echo "✅ Test kategori oluşturuldu!\n";
    echo "   ID: {$category->id}\n";
    echo "   Adı: {$category->name}\n";
    echo "   İlçe: {$category->district}\n";
    echo "   Mahalle: {$category->neighborhood}\n";
    echo "   Başlangıç: {$category->start_datetime}\n";
    echo "   Bitiş: {$category->end_datetime}\n";

    // Clean up
    $category->delete();
    echo "\n✅ Test kategori temizlendi.\n";

} catch (\Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
    echo "Dosya: " . $e->getFile() . "\n";
    echo "Satır: " . $e->getLine() . "\n";
}
