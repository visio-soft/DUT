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
            // Make category_id nullable for projects that don't require categories
            $table->foreignId('category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // Revert category_id back to NOT NULL
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }
};
