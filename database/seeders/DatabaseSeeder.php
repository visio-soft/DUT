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
                // Kullanıcı ekle
        $this->call([
            UserSeeder::class,
            DoğaObjelerSeeder::class,
            UlaşımObjelerSeeder::class,
            MimarlıkObjelerSeeder::class,
            SanatObjelerSeeder::class,
            DokuObjelerSeeder::class,
            YaşamObjelerSeeder::class,
        ]);
    }
}
