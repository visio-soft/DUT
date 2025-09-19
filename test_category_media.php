<?php
require_once 'vendor/autoload.php';

// Test if Category model can be instantiated with HasMedia interface
$category = new App\Models\Category([
    'name' => 'Test Category',
    'description' => 'Test Description',
    'start_datetime' => now(),
    'end_datetime' => now()->addDays(30)
]);

echo "Category model with HasMedia interface loaded successfully!\n";
echo "Implements HasMedia: " . (($category instanceof Spatie\MediaLibrary\HasMedia) ? 'Yes' : 'No') . "\n";
echo "Has InteractsWithMedia trait: " . (method_exists($category, 'addMedia') ? 'Yes' : 'No') . "\n";
echo "Media collections registered: " . (method_exists($category, 'registerMediaCollections') ? 'Yes' : 'No') . "\n";
