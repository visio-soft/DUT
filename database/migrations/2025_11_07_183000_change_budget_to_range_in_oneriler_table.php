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
            // Add min_budget and max_budget columns
            $table->decimal('min_budget', 15, 2)->nullable()->after('estimated_duration');
            $table->decimal('max_budget', 15, 2)->nullable()->after('min_budget');
        });

        // Migrate data from budget to min_budget and max_budget if needed
        DB::statement('UPDATE oneriler SET min_budget = budget, max_budget = budget WHERE budget IS NOT NULL');

        // Drop the old budget column
        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropColumn('budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // Add back the budget column
            $table->decimal('budget', 15, 2)->nullable()->after('estimated_duration');
        });

        // Migrate data back from min_budget to budget
        DB::statement('UPDATE oneriler SET budget = min_budget WHERE min_budget IS NOT NULL');

        // Drop the range columns
        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropColumn(['min_budget', 'max_budget']);
        });
    }
};
