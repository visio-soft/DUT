<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles first
        $this->call([
            RoleSeeder::class,
        ]);
        
        // Admin kullanıcıları ekle
        $this->call([
            AdminUserSeeder::class,
            NormalAdminUserSeeder::class,
        ]);

        // Kategoriler ekle
        $this->call([
            CategorySeeder::class,
        ]);

        // Proje ve Öneriler ekle
        $this->call([
            ProjectSeeder::class,
            OneriSeeder::class,
        ]);
    }
}
