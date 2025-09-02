<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Remove hierarchical categories - use flat categories from seeders only
        
        // Kullanıcı ekle
        $this->call([
            UserSeeder::class,
            BelediyecilikKategorileriSeeder::class,
        ]);
    }
}
