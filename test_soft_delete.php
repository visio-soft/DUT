<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Category;
use App\Models\Oneri;
use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== SOFT DELETE TEST ===\n";

    $user = User::first();
    if (!$user) {
        echo "❌ Kullanıcı bulunamadı.\n";
        exit(1);
    }

    // Test kategorisi oluştur
    $category = Category::create([
        'name' => 'Test Kategori - Silme Testi',
        'description' => 'Bu kategori silinecek'
    ]);

    // Bu kategoride öneri oluştur
    $oneri = Oneri::create([
        'category_id' => $category->id,
        'created_by_id' => $user->id,
        'updated_by_id' => $user->id,
        'title' => 'Test Önerisi - Silme Testi',
        'description' => 'Bu öneri kategorisiyle birlikte silinecek'
    ]);

    echo "✅ Test kategorisi ve önerisi oluşturuldu\n";
    echo "Kategori ID: {$category->id}\n";
    echo "Öneri ID: {$oneri->id}\n\n";

    // Kategoriye ait önerileri göster
    echo "Kategori önerileri (silinmeden önce):\n";
    foreach ($category->oneriler as $item) {
        echo "  - {$item->title}\n";
    }
    echo "\n";

    // Kategoriyi soft delete et
    echo "Kategori soft delete ediliyor...\n";
    $category->delete();

    // Sonuçları kontrol et
    echo "✅ Kategori soft delete edildi\n\n";

    echo "Soft delete sonrası durum:\n";
    echo "Kategori (withTrashed): " . (Category::withTrashed()->find($category->id) ? "Bulundu (silinmiş)" : "Bulunamadı") . "\n";
    echo "Öneri (withTrashed): " . (Oneri::withTrashed()->find($oneri->id) ? "Bulundu (silinmiş)" : "Bulunamadı") . "\n";
    echo "Kategori (normal): " . (Category::find($category->id) ? "Bulundu" : "Bulunamadı") . "\n";
    echo "Öneri (normal): " . (Oneri::find($oneri->id) ? "Bulundu" : "Bulunamadı") . "\n\n";

    // Geri getirme testi
    echo "Kategori geri getiriliyor...\n";
    Category::withTrashed()->find($category->id)->restore();

    echo "✅ Kategori geri getirildi\n\n";

    echo "Restore sonrası durum:\n";
    echo "Kategori: " . (Category::find($category->id) ? "Bulundu" : "Bulunamadı") . "\n";
    echo "Öneri: " . (Oneri::find($oneri->id) ? "Bulundu" : "Bulunamadı") . "\n\n";

    // Force delete testi
    echo "Force delete testi...\n";
    $category->forceDelete();

    echo "✅ Kategori force delete edildi\n\n";

    echo "Force delete sonrası durum:\n";
    echo "Kategori (withTrashed): " . (Category::withTrashed()->find($category->id) ? "Bulundu" : "Bulunamadı (kalıcı silindi)") . "\n";
    echo "Öneri (withTrashed): " . (Oneri::withTrashed()->find($oneri->id) ? "Bulundu" : "Bulunamadı (kalıcı silindi)") . "\n";

    echo "\n✅ Tüm testler başarıyla tamamlandı!\n";

} catch (\Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
    echo "Dosya: " . $e->getFile() . "\n";
    echo "Satır: " . $e->getLine() . "\n";
}
