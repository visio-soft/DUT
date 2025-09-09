<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

try {
    $user = User::where('email', 'admin@admin.com')->first();
    
    if ($user) {
        // User resource için tüm izinleri al
        $userPermissions = [
            'view_any_user',
            'view_user',
            'create_user', 
            'update_user',
            'delete_user',
            'delete_any_user',
            'force_delete_user',
            'force_delete_any_user',
            'restore_user',
            'restore_any_user',
            'replicate_user',
            'reorder_user'
        ];
        
        foreach ($userPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $user->givePermissionTo($permission);
                echo "İzin verildi: {$permissionName}\n";
            } else {
                echo "İzin bulunamadı: {$permissionName}\n";
            }
        }
        
        // Admin role'üne de bu izinleri ata
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            foreach ($userPermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $adminRole->givePermissionTo($permission);
                }
            }
            echo "Admin rolüne User izinleri atandı.\n";
        }
        
        // Super admin role'üne de bu izinleri ata
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            foreach ($userPermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $superAdminRole->givePermissionTo($permission);
                }
            }
            echo "Super admin rolüne User izinleri atandı.\n";
        }
        
        echo "İşlem tamamlandı.\n";
    } else {
        echo "Admin kullanıcısı bulunamadı.\n";
    }
    
} catch (Throwable $e) {
    echo 'Hata: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
