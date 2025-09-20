<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->instance('files', new \Illuminate\Filesystem\Filesystem());
\Illuminate\Support\Facades\Facade::setFacadeApplication($app);

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "Testing Category form with new organized layout...\n";

try {
    // Create a test category with the new form structure
    $category = Category::create([
        'name' => 'Test - Düzenlenmiş Form',
        'description' => 'Yeni form düzeni test edildi.',
        'start_datetime' => now(),
        'end_datetime' => now()->addDays(30),
        'country' => 'Türkiye',
        'province' => 'İstanbul',
        'district' => 'Beşiktaş',
        'neighborhood' => 'Ortaköy',
        'detailed_address' => 'Test Caddesi No: 10'
    ]);

    echo "✅ Test kategori başarıyla oluşturuldu!\n";
    echo "   ID: {$category->id}\n";
    echo "   Adı: {$category->name}\n";
    echo "   Açıklama: {$category->description}\n";
    echo "   Başlangıç: {$category->start_datetime}\n";
    echo "   Bitiş: {$category->end_datetime}\n";
    echo "   Konum: {$category->district}, {$category->neighborhood}\n";
    echo "   Adres: {$category->detailed_address}\n";

    // Clean up
    $category->delete();
    echo "\n✅ Test temizlendi.\n";

} catch (\Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
    echo "Dosya: " . $e->getFile() . "\n";
    echo "Satır: " . $e->getLine() . "\n";
}
