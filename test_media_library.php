<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Oneri;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test media library functionality
    $oneri = Oneri::first();

    if (!$oneri) {
        echo "No oneri found. Creating one...\n";

        $category = Category::first();
        $user = User::first();

        if (!$category || !$user) {
            echo "Need category and user first\n";
            exit(1);
        }

        $oneri = Oneri::create([
            'title' => 'Test Media Öneri - ' . date('Y-m-d H:i:s'),
            'category_id' => $category->id,
            'created_by_id' => $user->id,
        ]);
    }

    echo "Testing media collections for Oneri ID: {$oneri->id}\n";

    // Check existing media
    $media = $oneri->getMedia('images');
    echo "Existing media in 'images' collection: " . count($media) . "\n";

    foreach ($media as $mediaItem) {
        echo "- Media ID: {$mediaItem->id}\n";
        echo "  Name: {$mediaItem->name}\n";
        echo "  File name: {$mediaItem->file_name}\n";
        echo "  Size: {$mediaItem->size} bytes\n";
        echo "  URL: {$mediaItem->getUrl()}\n";
        echo "  Full URL: {$mediaItem->getFullUrl()}\n\n";
    }

    // Test storage disk
    echo "Testing storage disk configuration...\n";
    $disk = Storage::disk('public');
    echo "Storage disk path: " . $disk->path('') . "\n";
    echo "Storage disk exists: " . ($disk->exists('') ? 'Yes' : 'No') . "\n";

    // Check if media is actually accessible
    if (count($media) > 0) {
        $firstMedia = $media->first();
        $path = $firstMedia->getPath();
        echo "First media file path: {$path}\n";
        echo "File exists: " . (file_exists($path) ? 'Yes' : 'No') . "\n";
    }

    echo "\n✅ Media library test completed!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
