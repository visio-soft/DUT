<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NormalAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign user and suggestion permissions to admin role
        $userPermissions = Permission::where('name', 'like', '%user%')->get();
        $suggestionPermissions = Permission::where('name', 'like', '%suggestion%')->get();

        $allPermissions = $userPermissions->merge($suggestionPermissions);
        $adminRole->syncPermissions($allPermissions);

        // Normal admin kullanıcısı oluştur (OMEGA BRANCH VERSION)
        $omegaAdmin = User::where('email', 'omega@admin.com')->first();

        if (! $omegaAdmin) {
            $omegaAdmin = new User;
            $omegaAdmin->name = 'Omega Admin';
            $omegaAdmin->email = 'omega@admin.com';
            $omegaAdmin->password = Hash::make('omega456');
            $omegaAdmin->email_verified_at = now();
            $omegaAdmin->save();
        }

        // Assign admin role to user
        $omegaAdmin->assignRole('admin');

        // Create normal admin user (MAIN BRANCH VERSION)
        $normalAdmin = User::where('email', 'normaladmin@dut.com')->first();

        if (! $normalAdmin) {
            $normalAdmin = new User;
            $normalAdmin->name = 'Normal Admin Main';
            $normalAdmin->email = 'normaladmin@dut.com';
            $normalAdmin->password = Hash::make('main123');
            $normalAdmin->email_verified_at = now();
            $normalAdmin->save();
        }

        // Assign admin role to user
        $normalAdmin->assignRole('admin');
        echo "Normal admin users created:\n";
        echo "1. Omega Admin - Email: omega@admin.com, Password: omega456\n";
        echo "2. Normal Admin Main - Email: normaladmin@dut.com, Password: main123\n";
        echo "These users can view User management and Suggestion management, but not Role management.\n";
    }
}
