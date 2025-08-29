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
        Schema::table('objeler', function (Blueprint $table) {
            // Türkçe 'kategori' sütununu İngilizce 'category' olarak yeniden adlandır
            $table->renameColumn('kategori', 'category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objeler', function (Blueprint $table) {
            // Geri alma: 'category' sütununu 'kategori' olarak geri adlandır
            $table->renameColumn('category', 'kategori');
        });
    }
};