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
            // First set a default category for any NULL values
            // This will be handled in code before the migration
        });

        // Update any NULL category_id values to a default category
        $defaultCategory = \App\Models\Category::first();
        if ($defaultCategory) {
            \Illuminate\Support\Facades\DB::table('oneriler')
                ->whereNull('category_id')
                ->update(['category_id' => $defaultCategory->id]);
        }

        Schema::table('oneriler', function (Blueprint $table) {
            // Make category_id required again
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // Make category_id nullable again
            $table->foreignId('category_id')->nullable()->change();
        });
    }
};
