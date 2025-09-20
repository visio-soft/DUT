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
            // Make description optional
            $table->text('description')->nullable()->change();

            // Make budget optional
            $table->decimal('budget', 15, 2)->nullable()->change();

            // Make estimated_duration optional (if exists)
            if (Schema::hasColumn('oneriler', 'estimated_duration')) {
                $table->integer('estimated_duration')->nullable()->change();
            }

            // Make start_date and end_date optional (if they still exist)
            if (Schema::hasColumn('oneriler', 'start_date')) {
                $table->date('start_date')->nullable()->change();
            }

            if (Schema::hasColumn('oneriler', 'end_date')) {
                $table->date('end_date')->nullable()->change();
            }

            // Make district optional
            $table->string('district', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            // Reverse the changes - make fields required again
            $table->text('description')->nullable(false)->change();
            $table->decimal('budget', 15, 2)->nullable(false)->change();

            if (Schema::hasColumn('oneriler', 'estimated_duration')) {
                $table->integer('estimated_duration')->nullable(false)->change();
            }

            if (Schema::hasColumn('oneriler', 'start_date')) {
                $table->date('start_date')->nullable(false)->change();
            }

            if (Schema::hasColumn('oneriler', 'end_date')) {
                $table->date('end_date')->nullable(false)->change();
            }

            $table->string('district', 100)->nullable(false)->change();
        });
    }
};
