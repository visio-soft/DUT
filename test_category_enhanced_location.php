<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->instance('files', new \Illuminate\Filesystem\Filesystem());
\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "Testing Category model with new location fields...\n";

try {
    // Create a test category with all location fields
    $category = Category::create([
        'name' => 'Test Proje - Gelişmiş Konum Testi',
        'description' => 'Bu proje gelişmiş konum özelliklerini test etmek için oluşturulmuştur.',
        'start_datetime' => now(),
        'end_datetime' => now()->addDays(30),
        'country' => 'Türkiye',
        'province' => 'İstanbul',
        'district' => 'Kadıköy',
        'neighborhood' => 'Fenerbahçe',
        'detailed_address' => 'Bağdat Caddesi No: 123, Kat: 4, Daire: 8'
    ]);

    echo "✅ Test kategori oluşturuldu!\n";
    echo "   ID: {$category->id}\n";
    echo "   Adı: {$category->name}\n";
    echo "   Ülke: {$category->country}\n";
    echo "   İl: {$category->province}\n";
    echo "   İlçe: {$category->district}\n";
    echo "   Mahalle: {$category->neighborhood}\n";
    echo "   Detaylı Adres: {$category->detailed_address}\n";
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
