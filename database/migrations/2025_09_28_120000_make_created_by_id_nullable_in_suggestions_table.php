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
        Schema::table('suggestions', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['created_by_id']);

            // Make the column nullable
            $table->foreignId('created_by_id')->nullable()->change();

            // Add foreign key back with nullable constraint
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suggestions', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['created_by_id']);

            // Make the column not nullable again
            $table->foreignId('created_by_id')->nullable(false)->change();

            // Add foreign key back
            $table->foreign('created_by_id')->references('id')->on('users');
        });
    }
};
