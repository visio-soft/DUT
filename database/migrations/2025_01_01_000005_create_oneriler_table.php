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
        Schema::create('oneriler', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('updated_by_id')->nullable()->constrained('users');

            // Basic Information
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->integer('estimated_duration')->nullable()->comment('Tahmini işlem süresi (gün)');

            // Project Dates
            $table->dateTime('start_date')->nullable()->comment('Proje başlangıç tarihi ve saati');
            $table->dateTime('end_date')->nullable()->comment('Proje bitiş tarihi ve saati');

            // Budget
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

            // Additional indexes
            $table->index(['category_id', 'created_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oneriler');
    }
};