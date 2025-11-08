<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Category;
use App\Models\Suggestion;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Current Categories and their Suggestions:\n";
    $categories = Category::with('oneriler')->get();
    foreach ($categories as $category) {
        echo "- {$category->name} (ID: {$category->id}) - {$category->oneriler->count()} suggestions\n";
        if ($category->oneriler->count() > 0) {
            foreach ($category->oneriler->take(2) as $oneri) {
                echo "  * {$oneri->title}\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
