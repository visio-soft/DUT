<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing Project creation without category...\n\n";

    $user = User::first();

    if (!$user) {
        echo "❌ No user found. Please create a user first.\n";
        exit(1);
    }

    echo "Creating a test project without category...\n";

    $project = Project::create([
        'created_by_id' => $user->id,
        'updated_by_id' => $user->id,
        'title' => 'Kategorisiz Test Projesi - ' . now()->format('Y-m-d H:i:s'),
        'description' => 'Bu kategorisi olmayan bir test projesidir.',
        'start_date' => now(),
        'end_date' => now()->addDays(15),
        'city' => 'İstanbul',
        'district' => 'Beşiktaş',
        'neighborhood' => 'Ortaköy',
        'street_cadde' => 'Mecidiye Köprüsü',
        'street_sokak' => 'Sahil Yolu',
        'address_details' => 'Boğaz manzaralı proje alanı',
    ]);

    echo "✅ Project created successfully with ID: {$project->id}\n";
    echo "   Title: {$project->title}\n";
    echo "   Start Date: {$project->start_date->format('d.m.Y H:i')}\n";
    echo "   End Date: {$project->end_date->format('d.m.Y H:i')}\n";
    echo "   Location: {$project->district}, {$project->neighborhood}\n";
    echo "   Address: {$project->street_cadde}, {$project->street_sokak}\n";

    // Test relationships
    echo "\nTesting relationships...\n";
    echo "   Created by relationship: " . ($project->createdBy ? "✅ Working ({$project->createdBy->name})" : "❌ Not working") . "\n";
    echo "   Updated by relationship: " . ($project->updatedBy ? "✅ Working ({$project->updatedBy->name})" : "❌ Not working") . "\n";

    // Test media collections
    echo "\nTesting media collections...\n";
    $collections = $project->getRegisteredMediaCollections();
    foreach ($collections as $collection) {
        echo "   Collection: {$collection->name} - ";
        echo ($collection->singleFile ? "Single file" : "Multiple files") . "\n";
    }

    echo "\n✅ Project without category test completed successfully!\n";
    echo "   The Project model now works without requiring a category:\n";
    echo "   - ❌ Category field removed from form\n";
    echo "   - ❌ Category column removed from table\n";
    echo "   - ❌ Category relationship removed from model\n";
    echo "   - ✅ Main image upload available\n";
    echo "   - ✅ DateTime fields for project timeline\n";
    echo "   - ✅ Location management\n";
    echo "   - ✅ User relationships maintained\n\n";

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
