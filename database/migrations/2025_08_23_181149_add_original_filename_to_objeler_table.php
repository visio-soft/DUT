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
            $table->string('original_filename')->nullable()->after('isim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objeler', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });
    }
};
