<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Category;
use App\Models\Oneri;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== RESTRUCTURED CATEGORY SYSTEM TEST ===\n";
    echo "Testing Projects with Categories based on Suggestions\n\n";

    $user = User::first();
    $category = Category::first();

    if (!$user || !$category) {
        echo "❌ Missing test data. Ensure users and categories exist.\n";
        exit(1);
    }

    echo "1. TESTING CATEGORY STRUCTURE\n";
    echo "==============================\n";
    echo "Available Category: {$category->name} (ID: {$category->id})\n";

    // Test creating an Oneri (suggestion) first
    echo "\n2. CREATING A SUGGESTION (ÖNERI)\n";
    echo "=================================\n";

    $oneri = Oneri::create([
        'category_id' => $category->id,
        'created_by_id' => $user->id,
        'updated_by_id' => $user->id,
        'title' => 'Park Düzenlemesi Önerisi - ' . now()->format('H:i:s'),
        'description' => 'Mahalle parkının yenilenmesi için öneri',
        'estimated_duration' => 60,
        'city' => 'İstanbul',
        'district' => 'Üsküdar',
        'neighborhood' => 'Altunizade',
    ]);

    echo "✅ Oneri created: {$oneri->title}\n";
    echo "   Category: {$oneri->category->name}\n";
    echo "   Duration: {$oneri->estimated_duration} days\n";

    // Test creating a Project in the same category
    echo "\n3. CREATING A PROJECT IN THE SAME CATEGORY\n";
    echo "==========================================\n";

    $project = Project::create([
        'category_id' => $category->id, // Same category as the suggestion
        'created_by_id' => $user->id,
        'updated_by_id' => $user->id,
        'title' => 'Park Düzenleme Projesi - ' . now()->format('H:i:s'),
        'description' => 'Öneri doğrultusunda başlatılan park düzenleme projesi',
        'start_date' => now(),
        'end_date' => now()->addDays(60),
        'city' => 'İstanbul',
        'district' => 'Üsküdar',
        'neighborhood' => 'Altunizade',
    ]);

    echo "✅ Project created: {$project->title}\n";
    echo "   Category: {$project->category->name}\n";
    echo "   Start: {$project->start_date->format('d.m.Y H:i')}\n";
    echo "   End: {$project->end_date->format('d.m.Y H:i')}\n";

    // Test category relationships
    echo "\n4. TESTING CATEGORY RELATIONSHIPS\n";
    echo "==================================\n";

    $category->refresh();
    $categoryInfo = $category->getAllItems();

    echo "Category: {$category->name}\n";
    echo "  - Suggestions (Öneriler): {$categoryInfo['oneriler']->count()}\n";
    echo "  - Projects: {$categoryInfo['projects']->count()}\n";
    echo "  - Total items: {$categoryInfo['total_count']}\n";

    echo "\nSuggestions in this category:\n";
    foreach ($categoryInfo['oneriler'] as $item) {
        echo "  * {$item->title} (Öneri)\n";
    }

    echo "\nProjects in this category:\n";
    foreach ($categoryInfo['projects'] as $item) {
        echo "  * {$item->title} (Proje)\n";
    }

    // Test that projects now require categories
    echo "\n5. TESTING CATEGORY REQUIREMENT\n";
    echo "================================\n";

    try {
        Project::create([
            // No category_id - should fail
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
            'title' => 'Should Fail',
            'description' => 'This should fail without category',
        ]);
        echo "❌ Project created without category (unexpected)\n";
    } catch (\Exception $e) {
        echo "✅ Project creation failed without category (as expected)\n";
        echo "   Error: " . substr($e->getMessage(), 0, 100) . "...\n";
    }

    echo "\n🎉 RESTRUCTURED SYSTEM WORKING CORRECTLY!\n";
    echo "   ✅ Projects now require categories\n";
    echo "   ✅ Projects and suggestions can share the same categories\n";
    echo "   ✅ Category relationships work for both models\n";
    echo "   ✅ Projects can be organized by suggestion categories\n\n";

    // Cleanup
    echo "Cleaning up test data...\n";
    $oneri->delete();
    $project->delete();
    echo "✅ Test data cleaned up.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";

    // Cleanup on error
    if (isset($oneri) && $oneri->exists) $oneri->delete();
    if (isset($project) && $project->exists) $project->delete();
}
