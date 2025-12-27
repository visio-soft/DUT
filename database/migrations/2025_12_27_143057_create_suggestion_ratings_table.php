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
        Schema::create('suggestion_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suggestion_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Nullable for potential anonymous ratings
            $table->integer('score'); // 0-10
            $table->string('visual_score')->nullable(); // heart, star
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Limit: One rating per user per suggestion? 
            // Ideally yes, but depends on logic. Let's add unique constraint for logged in users.
            $table->unique(['user_id', 'suggestion_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestion_ratings');
    }
};
