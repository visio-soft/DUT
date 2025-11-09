<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration refactors the database to implement the correct hierarchy:
     * Category > ProjectGroup > Project > Suggestion
     */
    public function up(): void
    {
        // Step 1: Add project_group_id back to suggestions table
        // This is for rows that are Projects (where project_id is NULL)
        if (!Schema::hasColumn('suggestions', 'project_group_id')) {
            Schema::table('suggestions', function (Blueprint $table) {
                $table->foreignId('project_group_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('project_groups')
                    ->onDelete('cascade');
            });
        }

        // Step 2: Migrate data from pivot table to project_group_id column
        // Only for Projects (rows where project_id is NULL)
        if (Schema::hasTable('project_group_suggestion')) {
            $pivotData = DB::table('project_group_suggestion')
                ->join('suggestions', 'project_group_suggestion.suggestion_id', '=', 'suggestions.id')
                ->whereNull('suggestions.project_id')
                ->select('project_group_suggestion.suggestion_id', 'project_group_suggestion.project_group_id')
                ->get();

            foreach ($pivotData as $data) {
                DB::table('suggestions')
                    ->where('id', $data->suggestion_id)
                    ->update(['project_group_id' => $data->project_group_id]);
            }
        }

        // Step 3: Make category_id nullable for suggestions
        // Suggestions will get category through Project > ProjectGroup > Category
        Schema::table('suggestions', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->change();
        });

        // Step 4: Drop the pivot table - no longer needed
        Schema::dropIfExists('project_group_suggestion');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the pivot table
        Schema::create('project_group_suggestion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_group_id')->constrained('project_groups')->onDelete('cascade');
            $table->foreignId('suggestion_id')->constrained('suggestions')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['project_group_id', 'suggestion_id']);
        });

        // Migrate data back to pivot table
        $projects = DB::table('suggestions')
            ->whereNotNull('project_group_id')
            ->whereNull('project_id')
            ->get();

        foreach ($projects as $project) {
            DB::table('project_group_suggestion')->insert([
                'project_group_id' => $project->project_group_id,
                'suggestion_id' => $project->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Make category_id NOT nullable again
        Schema::table('suggestions', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
        });

        // Remove project_group_id column
        Schema::table('suggestions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_group_id');
        });
    }
};
