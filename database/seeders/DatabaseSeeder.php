<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (env('DUT_SEED_WITH_LIVE', false)) {
            $this->call([
                PostgresDataSeeder::class, // Yeni: Daha güvenilir live data seeder
            ]);

            return;
        }

        // Create roles first
        $this->call([
            RoleSeeder::class,
        ]);

        // Admin kullanıcıları ekle
        $this->call([
            AdminUserSeeder::class,
            NormalAdminUserSeeder::class,
        ]);

        // Kategoriler, proje grupları, projeler ve öneriler
        $this->call([
            CategorySeeder::class,
            ProjectGroupSeeder::class,
            ProjectSeeder::class,
            SuggestionSeeder::class,
        ]);
    }
}
