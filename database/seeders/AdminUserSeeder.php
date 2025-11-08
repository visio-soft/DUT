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
        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Get all permissions and assign to super_admin role
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);
        
        // If no permissions exist, create basic permissions
        if ($permissions->isEmpty()) {
            $basicPermissions = [
                'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
                'view_any_suggestion', 'view_suggestion', 'create_suggestion', 'update_suggestion', 'delete_suggestion',
                'view_any_category', 'view_category', 'create_category', 'update_category', 'delete_category',
                'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
                'view_any_project::design', 'view_project::design', 'create_project::design', 'update_project::design', 'delete_project::design',
            ];
            
            foreach ($basicPermissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
            
            // Get all permissions again and assign
            $permissions = Permission::all();
            $superAdminRole->syncPermissions($permissions);
        }
        
        // Create admin user
        $admin = User::where('email', 'admin@admin.com')->first();
        
        if (!$admin) {
            $admin = new User();
            $admin->name = 'Super Admin';
            $admin->email = 'admin@admin.com';
            $admin->password = Hash::make('password');
            $admin->email_verified_at = now();
            $admin->save();
        }
        
        // Assign Super Admin role to user
        $admin->assignRole('super_admin');
        
        // Extra security: Give user direct permissions too
        $admin->syncPermissions($permissions);
        
        echo "Super Admin created:\n";
        echo "Email: admin@admin.com\n";
        echo "Password: password\n";
        echo "Role: super_admin\n";
        echo "Total Permissions: " . $permissions->count() . "\n";
    }
}
