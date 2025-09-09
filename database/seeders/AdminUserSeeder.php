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
        
        // Eğer hiç permission yoksa, temel permissions oluştur
        if ($permissions->isEmpty()) {
            $basicPermissions = [
                'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
                'view_any_oneri', 'view_oneri', 'create_oneri', 'update_oneri', 'delete_oneri',
                'view_any_category', 'view_category', 'create_category', 'update_category', 'delete_category',
                'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
                'view_any_project::design', 'view_project::design', 'create_project::design', 'update_project::design', 'delete_project::design',
            ];
            
            foreach ($basicPermissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
            
            // Tekrar tüm izinleri al ve ata
            $permissions = Permission::all();
            $superAdminRole->syncPermissions($permissions);
        }
        
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
        
        // Ekstra güvenlik: Kullanıcıya direkt tüm izinleri de ver
        $admin->syncPermissions($permissions);
        
        echo "Super Admin oluşturuldu:\n";
        echo "Email: admin@admin.com\n";
        echo "Password: password\n";
        echo "Rol: super_admin\n";
        echo "Toplam İzin: " . $permissions->count() . "\n";
    }
}
