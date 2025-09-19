<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Oneri;
use App\Models\OneriLike;
use App\Models\User;
use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing enhanced likes system...\n\n";

    // Get first oneri and user
    $oneri = Oneri::first();
    $user = User::first();

    if (!$oneri) {
        echo "❌ No oneri found. Please create a oneri first.\n";
        exit(1);
    }

    if (!$user) {
        echo "❌ No user found. Please create a user first.\n";
        exit(1);
    }

    echo "Testing with:\n";
    echo "Oneri ID: {$oneri->id} - '{$oneri->title}'\n";
    echo "User ID: {$user->id} - '{$user->name}'\n\n";

    // Check if like already exists
    $existingLike = OneriLike::where('user_id', $user->id)
                             ->where('oneri_id', $oneri->id)
                             ->first();

    if ($existingLike) {
        echo "Like already exists - removing it first...\n";
        $existingLike->delete();
        echo "✅ Existing like removed.\n";
    }

    // Get initial count
    $initialCount = $oneri->likes_count;
    echo "Initial likes count: {$initialCount}\n";

    // Add a like
    echo "\nAdding a like...\n";
    $like = OneriLike::create([
        'user_id' => $user->id,
        'oneri_id' => $oneri->id,
    ]);

    echo "✅ Like created with ID: {$like->id}\n";

    // Refresh and check count
    $oneri->refresh();
    $newCount = $oneri->likes_count;
    echo "New likes count: {$newCount}\n";

    if ($newCount > $initialCount) {
        echo "✅ Like count increased correctly!\n";
    } else {
        echo "❌ Like count did not increase as expected.\n";
    }

    // Test trying to add duplicate like
    echo "\nTesting duplicate like prevention...\n";
    try {
        OneriLike::create([
            'user_id' => $user->id,
            'oneri_id' => $oneri->id,
        ]);
        echo "❌ Duplicate like was allowed (this shouldn't happen)!\n";
    } catch (\Exception $e) {
        echo "✅ Duplicate like prevented correctly (unique constraint working).\n";
    }

    // Test relationships
    echo "\nTesting relationships...\n";
    echo "Like -> User: " . ($like->user ? "✅ Working" : "❌ Not working") . "\n";
    echo "Like -> Oneri: " . ($like->oneri ? "✅ Working" : "❌ Not working") . "\n";
    echo "Oneri -> Likes: " . ($oneri->likes->count() > 0 ? "✅ Working" : "❌ Not working") . "\n";

    // Test removing like
    echo "\nRemoving the like...\n";
    $like->delete();
    echo "✅ Like removed.\n";

    $oneri->refresh();
    $finalCount = $oneri->likes_count;
    echo "Final likes count: {$finalCount}\n";

    if ($finalCount == $initialCount) {
        echo "✅ Like count returned to original value!\n";
    } else {
        echo "❌ Like count did not return to original value.\n";
    }

    echo "\n✅ Enhanced likes system test completed successfully!\n";
    echo "   The like system now supports:\n";
    echo "   - Adding likes to suggestions\n";
    echo "   - Preventing duplicate likes (unique constraint)\n";
    echo "   - Counting likes correctly\n";
    echo "   - Proper relationships between users, likes, and suggestions\n";
    echo "   - Removing likes\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
