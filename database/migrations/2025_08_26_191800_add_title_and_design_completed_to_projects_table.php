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
            if (!Schema::hasColumn('projects', 'title')) {
                $table->string('title')->nullable()->after('category_id')->index();
            }

            if (!Schema::hasColumn('projects', 'design_completed')) {
                $table->boolean('design_completed')->default(false)->after('created_by_id');
            }
        });

        // Backfill title from name when available
        if (Schema::hasColumn('projects', 'name') && Schema::hasColumn('projects', 'title')) {
            DB::table('projects')->whereNull('title')->update(['title' => DB::raw('name')]);
        }

        // Make title non-nullable if all rows have a value
        $hasNull = DB::table('projects')->whereNull('title')->exists();
        if (!$hasNull) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('title')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('projects', 'design_completed')) {
                $table->dropColumn('design_completed');
            }
        });
    }
};
