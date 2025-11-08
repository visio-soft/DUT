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
        Schema::table('categories', function (Blueprint $table) {
            // Proje ile ilgili tarih alanlarını kaldır
            $table->dropColumn(['start_datetime', 'end_datetime']);
            
            // Konum alanlarını kaldır
            $table->dropColumn(['district', 'neighborhood', 'detailed_address', 'country', 'province']);
            
            // Proje spesifik alanları kaldır
            $table->dropColumn(['icon', 'hide_budget']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Tarih alanlarını geri ekle
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            
            // Konum alanlarını geri ekle
            $table->string('district')->nullable();
            $table->string('neighborhood')->nullable();
            $table->text('detailed_address')->nullable();
            $table->string('country')->nullable()->default('Türkiye');
            $table->string('province')->nullable()->default('İstanbul');
            
            // Proje spesifik alanları geri ekle
            $table->string('icon')->nullable();
            $table->boolean('hide_budget')->default(false);
        });
    }
};
