<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

try {
    $superAdminRole = Role::findByName('super_admin');
    $adminRole = Role::findByName('admin');

    // Super admin'e role yönetimi izinlerini ver
    $rolePermissions = [
        'view_role',
        'view_any_role',
        'create_role',
        'update_role',
        'delete_role',
        'delete_any_role',
    ];

    foreach ($rolePermissions as $permissionName) {
        $permission = Permission::findByName($permissionName);
        if ($permission) {
            $superAdminRole->givePermissionTo($permission);
            echo "Super admin'e '{$permissionName}' izni verildi\n";
        }
    }

    // Admin rolüne user yönetimi izinlerini ver (role değil)
    $userPermissions = [
        'view_user',
        'view_any_user',
        'create_user',
        'update_user',
        'delete_user',
        'delete_any_user',
    ];

    foreach ($userPermissions as $permissionName) {
        $permission = Permission::findByName($permissionName);
        if ($permission) {
            $adminRole->givePermissionTo($permission);
            $superAdminRole->givePermissionTo($permission); // Super admin da user yönetebilsin
            echo "Admin ve Super admin'e '{$permissionName}' izni verildi\n";
        }
    }

    // Admin rolü varolan role izinlerini kaldır (eğer varsa)
    foreach ($rolePermissions as $permissionName) {
        $permission = Permission::findByName($permissionName);
        if ($permission && $adminRole->hasPermissionTo($permission)) {
            $adminRole->revokePermissionTo($permission);
            echo "Admin'den '{$permissionName}' izni kaldırıldı\n";
        }
    }

    echo "\nSuper Admin İzinleri:\n";
    foreach ($superAdminRole->permissions as $perm) {
        echo '- '.$perm->name."\n";
    }

    echo "\nAdmin İzinleri:\n";
    foreach ($adminRole->permissions as $perm) {
        echo '- '.$perm->name."\n";
    }

    echo "\nİşlem tamamlandı.\n";
} catch (Throwable $e) {
    echo 'Hata: '.$e->getMessage().PHP_EOL;
    exit(1);
}
