<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'a@a.com',
            'password' => bcrypt('236330'), // Use bcrypt for password hashing
        ]);
    }
}
