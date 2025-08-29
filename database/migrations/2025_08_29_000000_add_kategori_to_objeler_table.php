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
            if (!Schema::hasColumn('objeler', 'kategori')) {
                // place new column after existing 'isim' column
                $table->string('kategori')->default('doga')->after('isim');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objeler', function (Blueprint $table) {
            if (Schema::hasColumn('objeler', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};
