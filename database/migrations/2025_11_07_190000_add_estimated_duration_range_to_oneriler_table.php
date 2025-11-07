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
        Schema::table('oneriler', function (Blueprint $table) {
            $table->integer('min_estimated_duration')->nullable()->after('estimated_duration');
            $table->integer('max_estimated_duration')->nullable()->after('min_estimated_duration');
        });

        // Migrate data from estimated_duration to min_estimated_duration and max_estimated_duration if needed
        DB::statement('UPDATE oneriler SET min_estimated_duration = estimated_duration, max_estimated_duration = estimated_duration WHERE estimated_duration IS NOT NULL');

        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropColumn('estimated_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            $table->integer('estimated_duration')->nullable()->after('max_estimated_duration');
        });

        // Migrate data back from min_estimated_duration to estimated_duration
        DB::statement('UPDATE oneriler SET estimated_duration = min_estimated_duration WHERE min_estimated_duration IS NOT NULL');

        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropColumn(['min_estimated_duration', 'max_estimated_duration']);
        });
    }
};
