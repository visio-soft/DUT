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
        Schema::create('oneri_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('oneri_comment_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('oneri_comment_id')->references('id')->on('oneri_comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint - bir kullanıcı bir yorumu sadece bir kez beğenebilir
            $table->unique(['oneri_comment_id', 'user_id']);

            // Indexes for performance
            $table->index(['oneri_comment_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oneri_comment_likes');
    }
};
