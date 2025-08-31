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
        // Create permissions and roles tables (Spatie/Laravel-Permission)
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
            $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        // Media table (moved from standalone migration so no extra migration is needed)
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index(['model_type', 'model_id']);
        });

        // Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name')->index();
            $table->string('icon')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraint for parent_id
            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();

            // Performance indexes
            $table->index('parent_id');
        });

        // Create oneriler table (renamed from projects)
        Schema::create('oneriler', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('updated_by_id')->nullable()->constrained('users');

            // Basic Information
            $table->string('title')->index();
            $table->text('description');

            // Project Status
            $table->boolean('design_completed')->default(false);

            // Dates and Budget
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2);

            // Location Information
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('address')->nullable();
            $table->text('address_details')->nullable();
            $table->string('city', 100)->default('İstanbul');
            $table->string('district', 100);
            $table->string('neighborhood', 100)->nullable();
            $table->string('street_cadde', 150)->nullable();
            $table->string('street_sokak', 150)->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });

        // Create project_designs table
        Schema::create('project_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('oneriler')->onDelete('cascade');
            $table->json('design_data'); // Tasarım verilerini JSON olarak saklayacağız
            $table->timestamps();
        });

        // Create project_design_likes table
        Schema::create('project_design_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_design_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Ensure one like per user per project design
            $table->unique(['user_id', 'project_design_id']);
        });

        // Create objeler table
        Schema::create('objeler', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('original_filename')->nullable();
            $table->string('category')->default('doga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('project_design_likes');
    Schema::dropIfExists('project_designs');
    Schema::dropIfExists('oneriler');
    Schema::dropIfExists('categories');
    Schema::dropIfExists('objeler');
    // Media table
    Schema::dropIfExists('media');
    Schema::dropIfExists('role_has_permissions');
    Schema::dropIfExists('model_has_roles');
    Schema::dropIfExists('model_has_permissions');
    Schema::dropIfExists('roles');
    Schema::dropIfExists('permissions');
    }
};
