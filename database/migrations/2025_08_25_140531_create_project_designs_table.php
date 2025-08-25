<?php

use App\Models\Project;
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
        Schema::create('project_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->onDelete('cascade');
            $table->json('design_data'); // Tasarım verilerini JSON olarak saklayacağız
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_designs');
    }
};
