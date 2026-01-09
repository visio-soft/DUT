<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type'); // Model class (e.g., 'App\Models\Project')
            $table->unsignedBigInteger('translatable_id'); // Model ID
            $table->string('field'); // Field name (e.g., 'title', 'description')
            $table->string('locale', 5); // Language code (en, fr, de, sv)
            $table->text('translated_text'); // Translated content
            $table->timestamps();

            // Composite unique index for fast lookups
            $table->unique(['translatable_type', 'translatable_id', 'field', 'locale'], 'translations_unique');
            $table->index(['translatable_type', 'translatable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
