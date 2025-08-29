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
            // Türkçe 'isim' sütununu İngilizce 'name' olarak yeniden adlandır
            $table->renameColumn('isim', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objeler', function (Blueprint $table) {
            // Geri alma: 'name' sütununu 'isim' olarak geri adlandır
            $table->renameColumn('name', 'isim');
        });
    }
};