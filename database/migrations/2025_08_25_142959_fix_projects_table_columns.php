<?php

use App\Models\User;
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
        // Add created_by_id only if it doesn't exist yet
        if (!Schema::hasColumn('projects', 'created_by_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by_id')->nullable()->after('category_id');
            });

            // Update existing records with a sensible default
            DB::table('projects')->update([
                'created_by_id' => 1 // Default user id for legacy rows
            ]);

            // Make the column non-nullable and add foreign key if possible
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
                $table->foreign('created_by_id')->references('id')->on('users');
            });
        } else {
            // If column exists, ensure foreign key exists (guarded)
            Schema::table('projects', function (Blueprint $table) {
                try {
                    $table->foreign('created_by_id')->references('id')->on('users');
                } catch (\Throwable $e) {
                    // foreign key may already exist or DB doesn't allow adding it now; ignore
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
        });
    }
};
