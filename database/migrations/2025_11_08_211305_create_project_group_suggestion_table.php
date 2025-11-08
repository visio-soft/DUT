<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_group_suggestion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_group_id')->constrained('project_groups')->onDelete('cascade');
            $table->foreignId('suggestion_id')->constrained('suggestions')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['project_group_id', 'suggestion_id']);
        });

        // Migrate existing data from project_group_id column
        $suggestions = DB::table('suggestions')
            ->whereNotNull('project_group_id')
            ->get();

        foreach ($suggestions as $suggestion) {
            DB::table('project_group_suggestion')->insert([
                'project_group_id' => $suggestion->project_group_id,
                'suggestion_id' => $suggestion->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Remove the old project_group_id column
        Schema::table('suggestions', function (Blueprint $table) {
            try {
                $table->dropForeign(['project_group_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            $table->dropColumn('project_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add the old column back only if it doesn't exist
        if (! Schema::hasColumn('suggestions', 'project_group_id')) {
            Schema::table('suggestions', function (Blueprint $table) {
                $table->foreignId('project_group_id')->nullable()->constrained('project_groups')->onDelete('set null');
            });
        }

        // Restore data from pivot table
        $pivotData = DB::table('project_group_suggestion')->get();
        foreach ($pivotData as $data) {
            DB::table('suggestions')
                ->where('id', $data->suggestion_id)
                ->update(['project_group_id' => $data->project_group_id]);
        }

        Schema::dropIfExists('project_group_suggestion');
    }
};
