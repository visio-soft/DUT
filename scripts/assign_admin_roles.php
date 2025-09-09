<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

try {
    $user = User::where('email', 'admin@admin.com')->first();
    
    if ($user) {
        // Super admin rolü ata
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $user->assignRole($superAdminRole);
            echo "Super admin rolü atandı: {$user->email}\n";
        }
        
        // Admin rolü de ata
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->assignRole($adminRole);
            echo "Admin rolü atandı: {$user->email}\n";
        }
        
        echo "Kullanıcının rolleri: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    } else {
        echo "Admin kullanıcısı bulunamadı.\n";
    }
    
    echo "İşlem tamamlandı.\n";
} catch (Throwable $e) {
    echo 'Hata: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
