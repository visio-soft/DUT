<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'user', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    }

    /**
     * Test that regular users cannot access admin panel.
     */
    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('user');

        // Create mock admin panel
        $adminPanel = new class extends Panel
        {
            public function getId(): string
            {
                return 'admin';
            }
        };

        $this->assertFalse($user->canAccessPanel($adminPanel));
    }

    /**
     * Test that admin users can access admin panel.
     */
    public function test_admin_user_can_access_admin_panel(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('admin');

        // Create mock admin panel
        $adminPanel = new class extends Panel
        {
            public function getId(): string
            {
                return 'admin';
            }
        };

        $this->assertTrue($user->canAccessPanel($adminPanel));
    }

    /**
     * Test that super admin users can access admin panel.
     */
    public function test_super_admin_user_can_access_admin_panel(): void
    {
        $user = User::create([
            'name' => 'Super Admin User',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super_admin');

        // Create mock admin panel
        $adminPanel = new class extends Panel
        {
            public function getId(): string
            {
                return 'admin';
            }
        };

        $this->assertTrue($user->canAccessPanel($adminPanel));
    }

    /**
     * Test that registration assigns user role correctly.
     */
    public function test_user_registration_assigns_user_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('user'));

        // Verify they cannot access admin panel
        $adminPanel = new class extends Panel
        {
            public function getId(): string
            {
                return 'admin';
            }
        };

        $this->assertFalse($user->canAccessPanel($adminPanel));
    }
}
