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
            $table->string('district')->nullable()->after('description');
            $table->string('neighborhood')->nullable()->after('district');
            $table->text('detailed_address')->nullable()->after('neighborhood');
            $table->string('country')->nullable()->after('detailed_address')->default('Türkiye');
            $table->string('province')->nullable()->after('country')->default('İstanbul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['district', 'neighborhood', 'detailed_address', 'country', 'province']);
        });
    }
};
