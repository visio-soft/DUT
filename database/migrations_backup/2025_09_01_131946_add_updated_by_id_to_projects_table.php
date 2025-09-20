<?php

use App\Models\User;
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
        // The projects table was renamed to 'oneriler' in a different migration.
        Schema::table('oneriler', function (Blueprint $table) {
            if (!Schema::hasColumn('oneriler', 'updated_by_id')) {
                $table->foreignIdFor(User::class, 'updated_by_id')->nullable()->after('created_by_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            if (Schema::hasColumn('oneriler', 'updated_by_id')) {
                $table->dropForeign(['updated_by_id']);
                $table->dropColumn('updated_by_id');
            }
        });
    }
};
