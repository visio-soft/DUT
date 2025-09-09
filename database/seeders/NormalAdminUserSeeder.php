<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NormalAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin rolü oluştur
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Admin rolüne kullanıcı ve öneri izinlerini ata
        $userPermissions = Permission::where('name', 'like', '%user%')->get();
        $oneriPermissions = Permission::where('name', 'like', '%oneri%')->get();
        
        $allPermissions = $userPermissions->merge($oneriPermissions);
        $adminRole->syncPermissions($allPermissions);
        
        // Normal admin kullanıcısı oluştur
        $normalAdmin = User::where('email', 'user@admin.com')->first();
        
        if (!$normalAdmin) {
            $normalAdmin = new User();
            $normalAdmin->name = 'User';
            $normalAdmin->email = 'user@admin.com';
            $normalAdmin->password = Hash::make('password');
            $normalAdmin->email_verified_at = now();
            $normalAdmin->save();
        }
        
        // Admin rolünü kullanıcıya ata
        $normalAdmin->assignRole('admin');
        
        echo "Normal admin kullanıcısı oluşturuldu:\n";
        echo "Email: user@admin.com\n";
        echo "Password: password\n";
        echo "Bu kullanıcı User yönetimini ve Öneri yönetimini görebilir, Role yönetimini göremez.\n";
    }
}
