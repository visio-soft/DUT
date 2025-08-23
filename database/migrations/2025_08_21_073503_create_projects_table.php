<?php

use App\Models\Category;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Basic Information
            $table->string('title')->index();
            $table->text('description');
            
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
            
            // Indexes for performance
            $table->index(['category_id', 'district']);
            $table->index(['city', 'district']);
            $table->index('budget');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
