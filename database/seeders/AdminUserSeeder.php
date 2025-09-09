<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin rolü oluştur
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Tüm izinleri al ve super_admin rolüne ata
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);
        
        // Admin kullanıcısı oluştur
        $admin = User::where('email', 'admin@admin.com')->first();
        
        if (!$admin) {
            $admin = new User();
            $admin->name = 'Super Admin';
            $admin->email = 'admin@admin.com';
            $admin->password = Hash::make('password');
            $admin->email_verified_at = now();
            $admin->save();
        }
        
        // Super Admin rolünü kullanıcıya ata
        $admin->assignRole('super_admin');
    }
}
