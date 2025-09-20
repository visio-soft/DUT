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
            // Add datetime fields for projects
            $table->dateTime('start_date')->nullable()->after('estimated_duration')->comment('Proje başlangıç tarihi ve saati');
            $table->dateTime('end_date')->nullable()->after('start_date')->comment('Proje bitiş tarihi ve saati');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
