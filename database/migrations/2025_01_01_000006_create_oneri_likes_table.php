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
        Schema::create('oneri_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('oneri_id')->constrained('oneriler')->onDelete('cascade');
            $table->timestamps();

            // Bir kullanıcı bir öneriye sadece bir kez beğeni verebilir
            $table->unique(['user_id', 'oneri_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oneri_likes');
    }
};