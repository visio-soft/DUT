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

        // Create categories table (without hierarchy - simplified)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->string('district')->nullable();
            $table->string('neighborhood')->nullable();
            $table->text('detailed_address')->nullable();
            $table->string('country')->nullable()->default('Türkiye');
            $table->string('province')->nullable()->default('İstanbul');
            $table->timestamps();
            $table->softDeletes();
        });

        // Create oneriler table (main proposals/projects table)
        Schema::create('oneriler', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('updated_by_id')->nullable()->constrained('users');

            // Basic Information
            $table->string('title')->index();
            $table->text('description')->nullable();

            // Project Duration and Timing
            $table->integer('estimated_duration')->nullable()->comment('Tahmini işlem süresi (gün)');
            $table->dateTime('start_date')->nullable()->comment('Proje başlangıç tarihi ve saati');
            $table->dateTime('end_date')->nullable()->comment('Proje bitiş tarihi ve saati');
            $table->decimal('budget', 15, 2)->nullable();

            // Location Information
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('address')->nullable();
            $table->text('address_details')->nullable();
            $table->string('city', 100)->default('İstanbul');
            $table->string('district', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('street_cadde', 150)->nullable();
            $table->string('street_sokak', 150)->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });

        // Create media table (Spatie Media Library)
        if (!Schema::hasTable('media')) {
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
                $table->integer('order_column')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });

            // Ensure index exists
            DB::statement('CREATE INDEX IF NOT EXISTS media_model_type_model_id_index ON media (model_type, model_id)');
        }

        // Create oneri_likes table
        Schema::create('oneri_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('oneri_id')->constrained('oneriler')->onDelete('cascade');
            $table->timestamps();

            // Bir kullanıcı bir öneriye sadece bir kez beğeni verebilir
            $table->unique(['user_id', 'oneri_id']);
        });

        // Create oneri_comments table
        Schema::create('oneri_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oneri_id')->constrained('oneriler')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['oneri_id', 'is_approved']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oneri_comments');
        Schema::dropIfExists('oneri_likes');
        Schema::dropIfExists('media');
        Schema::dropIfExists('oneriler');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
};