<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Spatie models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$email = 'admin@admin.com';
$password = 'password'; // provided password

try {
    // Check existing user
    $user = User::where('email', $email)->first();

    if ($user) {
        echo "User already exists: {$email}\n";
    } else {
        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        echo "Created user: {$email}\n";
    }

    // Create admin role if not exists
    if (class_exists(Role::class)) {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user->assignRole($role);
        echo "Assigned role 'admin' to user.\n";
    } else {
        echo "Spatie Role model not available; skipped role assignment.\n";
    }

    // Optionally make user email verified (if you want)
    if (method_exists($user, 'markEmailAsVerified')) {
        $user->markEmailAsVerified();
        echo "Marked email as verified.\n";
    }

    echo "Done. Login: {$email} Password: {$password}\n";
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
