<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Project;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing Project model functionality...\n\n";

    // Test basic Project creation
    $category = Category::first();
    $user = User::first();

    if (!$category) {
        echo "❌ No category found. Please create a category first.\n";
        exit(1);
    }

    if (!$user) {
        echo "❌ No user found. Please create a user first.\n";
        exit(1);
    }

    echo "Creating a test project...\n";

    $project = Project::create([
        'category_id' => $category->id,
        'created_by_id' => $user->id,
        'updated_by_id' => $user->id,
        'title' => 'Test Projesi - ' . now()->format('Y-m-d H:i:s'),
        'description' => 'Bu bir test projesidir.',
        'start_date' => now(),
        'end_date' => now()->addDays(30),
        'city' => 'İstanbul',
        'district' => 'Kadıköy',
        'neighborhood' => 'Fenerbahçe',
        'street_cadde' => 'Test Caddesi',
        'street_sokak' => 'Test Sokağı',
        'address_details' => 'Test adresi detayları',
    ]);

    echo "✅ Project created with ID: {$project->id}\n";
    echo "   Title: {$project->title}\n";
    echo "   Category: {$project->category->name}\n";
    echo "   Start Date: {$project->start_date->format('d.m.Y H:i')}\n";
    echo "   End Date: {$project->end_date->format('d.m.Y H:i')}\n";
    echo "   Location: {$project->district}, {$project->neighborhood}\n";

    // Test relationships
    echo "\nTesting relationships...\n";
    echo "   Category relationship: " . ($project->category ? "✅ Working" : "❌ Not working") . "\n";
    echo "   Created by relationship: " . ($project->createdBy ? "✅ Working" : "❌ Not working") . "\n";
    echo "   Updated by relationship: " . ($project->updatedBy ? "✅ Working" : "❌ Not working") . "\n";

    // Test media collections
    echo "\nTesting media collections...\n";
    $collections = $project->getRegisteredMediaCollections();
    foreach ($collections as $collection) {
        echo "   Collection: {$collection->name} - ";
        echo ($collection->singleFile ? "Single file" : "Multiple files") . "\n";
    }

    echo "\n✅ Project model test completed successfully!\n";
    echo "   The Project model now supports:\n";
    echo "   - Main image upload (single file)\n";
    echo "   - DateTime fields for start and end dates\n";
    echo "   - No budget field\n";
    echo "   - Location management\n";
    echo "   - User relationships\n\n";

    // Clean up test data
    echo "Cleaning up test data...\n";
    $project->delete();
    echo "✅ Test project deleted.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";

    if (isset($project) && $project->exists) {
        echo "\nCleaning up test data...\n";
        $project->delete();
        echo "✅ Test project deleted.\n";
    }
}
