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
        if (! Schema::hasTable('oneriler')) {
            return;
        }

        Schema::table('oneriler', function (Blueprint $table) {
            if (! Schema::hasColumn('oneriler', 'project_id')) {
                $table->foreignId('project_id')->nullable()->constrained('oneriler')->onDelete('cascade')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('oneriler')) {
            return;
        }

        Schema::table('oneriler', function (Blueprint $table) {
            if (Schema::hasColumn('oneriler', 'project_id')) {
                $table->dropConstrainedForeignId('project_id');
            }
        });
    }
};
