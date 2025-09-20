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
            // Drop existing foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Add new foreign key constraint with CASCADE delete
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // Drop CASCADE foreign key
            $table->dropForeign(['category_id']);
            
            // Restore original RESTRICT foreign key
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories');
        });
    }
};
