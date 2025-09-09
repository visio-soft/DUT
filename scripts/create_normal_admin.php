<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

try {
    $email = 'admin2@admin.com';
    $password = 'password';
    
    // E-posta adresinin kullanılıp kullanılmadığını kontrol et
    if (User::where('email', $email)->exists()) {
        echo "Hata: {$email} adresi zaten kullanılıyor.\n";
        echo "Farklı bir e-posta adresi deneyin.\n";
        exit(1);
    }
    
    // Normal admin kullanıcısı oluştur
    $user = User::create([
        'name' => 'User',
        'email' => $email,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ]);
    
    // Sadece admin rolü ata (super_admin değil)
    $adminRole = Role::findByName('admin');
    $user->assignRole($adminRole);
    
    echo "Normal admin kullanıcısı oluşturuldu:\n";
    echo "Email: {$email}\n";
    echo "Password: {$password}\n";
    echo "Roller: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    echo "Bu kullanıcı User yönetimini ve Öneri yönetimini görebilir, Role yönetimini göremez.\n";
    
} catch (Throwable $e) {
    echo 'Hata: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
