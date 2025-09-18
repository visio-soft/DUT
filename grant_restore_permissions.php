<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$adminRole = Role::where('name', 'admin')->first();
$permissions = [
    'restore_oneri',
    'restore_any_oneri',
    'force_delete_oneri',
    'force_delete_any_oneri',
    'restore_category',
    'restore_any_category',
    'force_delete_category',
    'force_delete_any_category'
];

foreach($permissions as $permission) {
    $perm = Permission::where('name', $permission)->first();
    if($perm) {
        $adminRole->givePermissionTo($perm);
        echo "Granted: {$permission}\n";
    } else {
        echo "Not found: {$permission}\n";
    }
}

echo "Admin role permissions updated successfully\n";
