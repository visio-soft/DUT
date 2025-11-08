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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('min_budget', 15, 2)->nullable()->after('budget');
            $table->decimal('max_budget', 15, 2)->nullable()->after('min_budget');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('budget', 15, 2)->nullable();
            $table->dropColumn('min_budget');
            $table->dropColumn('max_budget');
        });
    }
};
