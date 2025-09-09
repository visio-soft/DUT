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
        
        // Normal admin kullanıcısı oluştur (OMEGA BRANCH VERSION)
        $omegaAdmin = User::where('email', 'omega@admin.com')->first();
        
        if (!$omegaAdmin) {
            $omegaAdmin = new User();
            $omegaAdmin->name = 'Omega Admin';
            $omegaAdmin->email = 'omega@admin.com';
            $omegaAdmin->password = Hash::make('omega456');
            $omegaAdmin->email_verified_at = now();
            $omegaAdmin->save();
        }
        
        // Admin rolünü kullanıcıya ata
        $omegaAdmin->assignRole('admin');
        
        // Normal admin kullanıcısı oluştur (MAIN BRANCH VERSION)
        $normalAdmin = User::where('email', 'normaladmin@dut.com')->first();
        
        if (!$normalAdmin) {
            $normalAdmin = new User();
            $normalAdmin->name = 'Normal Admin Main';
            $normalAdmin->email = 'normaladmin@dut.com';
            $normalAdmin->password = Hash::make('main123');
            $normalAdmin->email_verified_at = now();
            $normalAdmin->save();
        }
        
        // Admin rolünü kullanıcıya ata
        $normalAdmin->assignRole('admin');
        echo "Normal admin kullanıcıları oluşturuldu:\n";
        echo "1. Omega Admin - Email: omega@admin.com, Password: omega456\n";
        echo "2. Normal Admin Main - Email: normaladmin@dut.com, Password: main123\n";
        echo "Bu kullanıcılar User yönetimini ve Öneri yönetimini görebilir, Role yönetimini göremez.\n";
    }
}
