<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@admin.com';
$password = 'password';

try {
    $user = User::where('email', $email)->first();

    if (! $user) {
        echo "User not found: {$email}\n";
        exit(0);
    }

    echo "Found user: id={$user->id} name={$user->name} email={$user->email}\n";

    if (Hash::check($password, $user->password)) {
        echo "Password matches the provided password.\n";
    } else {
        echo "Password does NOT match the provided password.\n";
    }

    // Roles (Spatie)
    if (method_exists($user, 'getRoleNames')) {
        $roles = $user->getRoleNames()->toArray();
        echo 'Roles: '.implode(', ', $roles)."\n";
    }

    if (! empty($user->email_verified_at)) {
        echo "Email verified at: {$user->email_verified_at}\n";
    } else {
        echo "Email not verified.\n";
    }

    echo "Done.\n";
} catch (Throwable $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
    exit(1);
}
