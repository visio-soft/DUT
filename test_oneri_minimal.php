<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Oneri;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test creating a minimal oneri with just title and category_id
try {
    // Find first category and user
    $category = Category::first();
    $user = User::first();

    if (!$category) {
        echo "No category found. Please create a category first.\n";
        exit(1);
    }

    if (!$user) {
        echo "No user found. Please create a user first.\n";
        exit(1);
    }

    // Create minimal oneri
    $oneri = Oneri::create([
        'title' => 'Test Minimal Öneri - ' . date('Y-m-d H:i:s'),
        'category_id' => $category->id,
        'created_by_id' => $user->id,
    ]);

    echo "✅ Minimal öneri created successfully!\n";
    echo "ID: {$oneri->id}\n";
    echo "Title: {$oneri->title}\n";
    echo "Category: {$oneri->category->name}\n";
    echo "Description: " . ($oneri->description ?: 'NULL') . "\n";
    echo "Budget: " . ($oneri->budget ?: 'NULL') . "\n";
    echo "Duration: " . ($oneri->estimated_duration ?: 'NULL') . "\n";
    echo "District: " . ($oneri->district ?: 'NULL') . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
