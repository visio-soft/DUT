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
            // parent_id foreign key constraint'ini kaldır
            $table->dropForeign(['parent_id']);
            
            // parent_id kolonunu kaldır
            $table->dropColumn('parent_id');
            
            // main_category_id ekle
            $table->foreignId('main_category_id')
                ->nullable()
                ->after('id')
                ->constrained('main_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // main_category_id'yi kaldır
            $table->dropForeign(['main_category_id']);
            $table->dropColumn('main_category_id');
            
            // parent_id'yi geri ekle
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('categories')
                ->onDelete('cascade');
        });
    }
};
