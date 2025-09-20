<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabloyu projects'den oneriler'e yeniden adlandır (varsa)
        if (Schema::hasTable('projects') && ! Schema::hasTable('oneriler')) {
            Schema::rename('projects', 'oneriler');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma: oneriler'den projects'e yeniden adlandır (varsa)
        if (Schema::hasTable('oneriler') && ! Schema::hasTable('projects')) {
            Schema::rename('oneriler', 'projects');
        }
    }
};
