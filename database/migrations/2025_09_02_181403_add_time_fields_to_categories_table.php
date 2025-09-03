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
            // Eğer date alanları varsa onları kaldır
            if (Schema::hasColumn('categories', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('categories', 'end_date')) {
                $table->dropColumn('end_date');
            }

            // DateTime alanlarını ekle
            $table->dateTime('start_datetime')->nullable()->after('icon');
            $table->dateTime('end_datetime')->nullable()->after('start_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['start_datetime', 'end_datetime']);
        });
    }
};
