<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // estimated_duration sütununu nullable olarak ekle
            $table->integer('estimated_duration')->nullable()->after('description')->comment('Tahmini işlem süresi (gün)');
        });
        
        // Mevcut kayıtlar için varsayılan değer ata (30 gün)
        DB::table('oneriler')->whereNull('estimated_duration')->update(['estimated_duration' => 30]);
        
        // Şimdi nullable'ı kaldır
        Schema::table('oneriler', function (Blueprint $table) {
            $table->integer('estimated_duration')->nullable(false)->change();
        });
        
        // start_date ve end_date sütunlarını kaldır
        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // estimated_duration sütununu kaldır
            $table->dropColumn('estimated_duration');
            
            // start_date ve end_date sütunlarını geri ekle
            $table->date('start_date')->after('description');
            $table->date('end_date')->after('start_date');
        });
    }
};
