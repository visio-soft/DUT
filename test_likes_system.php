<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Oneri;
use App\Models\OneriLike;
use App\Models\User;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // İlk öneri ve kullanıcıları al
    $oneri = Oneri::first();
    $user = User::first();

    if (!$oneri) {
        echo "No oneri found. Please create a oneri first.\n";
        exit(1);
    }

    if (!$user) {
        echo "No user found. Please create a user first.\n";
        exit(1);
    }

    echo "Testing likes system...\n";
    echo "Oneri ID: {$oneri->id}\n";
    echo "User ID: {$user->id}\n\n";

    // Beğeni öncesi sayısı
    echo "Likes count before: {$oneri->likes_count}\n";

    // Beğeni ekle
    $like = OneriLike::create([
        'user_id' => $user->id,
        'oneri_id' => $oneri->id,
    ]);

    echo "Like created with ID: {$like->id}\n";

    // Oneri'yi yeniden yükle ve beğeni sayısını kontrol et
    $oneri->refresh();
    echo "Likes count after: {$oneri->likes_count}\n";

    // Beğeni ilişkisini test et
    echo "Likes relationship test:\n";
    foreach ($oneri->likes as $like) {
        echo "- Like ID: {$like->id}, User ID: {$like->user_id}\n";
    }

    echo "\n✅ Likes system test completed successfully!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
