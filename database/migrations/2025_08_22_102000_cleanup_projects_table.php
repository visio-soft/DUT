<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Eksik form alanları için sütunlar ekle
            if (!Schema::hasColumn('projects', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('projects', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('projects', 'neighborhood')) {
                $table->string('neighborhood', 100)->nullable();
            }
            if (!Schema::hasColumn('projects', 'street_cadde')) {
                $table->string('street_cadde', 150)->nullable();
            }
            if (!Schema::hasColumn('projects', 'street_sokak')) {
                $table->string('street_sokak', 150)->nullable();
            }
            if (!Schema::hasColumn('projects', 'address_details')) {
                $table->text('address_details')->nullable();
            }
            if (!Schema::hasColumn('projects', 'image_path')) {
                $table->string('image_path', 300)->nullable();
            }
        });


        Schema::table('projects', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $columns = ['start_date', 'end_date', 'neighborhood', 'street_cadde', 'street_sokak', 'address_details', 'image_path'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }

            // name'i tekrar not null yap
            $table->string('name')->nullable(false)->change();
        });
    }
};
