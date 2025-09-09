<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

try {
    // Test kullanıcıları
    $testUsers = [
        [
            'name' => 'Test Admin',
            'email' => 'test.admin@example.com',
            'password' => 'password',
            'role' => 'admin'
        ],
        [
            'name' => 'Test User 1',
            'email' => 'test.user1@example.com',
            'password' => 'password',
            'role' => null
        ],
        [
            'name' => 'Test User 2',
            'email' => 'test.user2@example.com',
            'password' => 'password',
            'role' => null
        ]
    ];
    
    foreach ($testUsers as $userData) {
        // E-posta zaten varsa atla
        if (User::where('email', $userData['email'])->exists()) {
            echo "Kullanıcı zaten var: {$userData['email']}\n";
            continue;
        }
        
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'email_verified_at' => now(), // Otomatik doğrulanmış
        ]);
        
        if ($userData['role']) {
            $role = Role::findByName($userData['role']);
            if ($role) {
                $user->assignRole($role);
            }
        }
        
        echo "Kullanıcı oluşturuldu: {$userData['name']} ({$userData['email']})\n";
    }
    
    echo "\nToplam kullanıcı sayısı: " . User::count() . "\n";
    echo "İşlem tamamlandı.\n";
    
} catch (Throwable $e) {
    echo 'Hata: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
