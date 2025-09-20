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
        // Remove design-related fields from oneriler table
        Schema::table('oneriler', function (Blueprint $table) {
            if (Schema::hasColumn('oneriler', 'design_completed')) {
                $table->dropColumn('design_completed');
            }
            if (Schema::hasColumn('oneriler', 'design_landscape')) {
                $table->dropColumn('design_landscape');
            }
        });

        // Drop design-related tables
        Schema::dropIfExists('project_design_likes');
        Schema::dropIfExists('project_designs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate project_designs table
        Schema::create('project_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('oneriler')->onDelete('cascade');
            $table->json('design_data');
            $table->timestamps();
            $table->softDeletes();
        });

        // Recreate project_design_likes table
        Schema::create('project_design_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_design_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'project_design_id']);
        });

        // Add design fields back to oneriler table
        Schema::table('oneriler', function (Blueprint $table) {
            $table->boolean('design_completed')->default(false);
            $table->json('design_landscape')->nullable();
        });
    }
};