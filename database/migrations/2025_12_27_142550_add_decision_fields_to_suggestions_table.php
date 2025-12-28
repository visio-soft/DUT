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
        Schema::table('suggestions', function (Blueprint $table) {
            $table->string('decision_type')->nullable(); // most_voted, admin_choice, hybrid
            $table->foreignId('selected_suggestion_id')->nullable()->constrained('suggestions')->onDelete('set null');
            $table->text('decision_rationale')->nullable();
            $table->timestamp('voting_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->dropForeign(['selected_suggestion_id']);
            $table->dropColumn(['decision_type', 'selected_suggestion_id', 'decision_rationale', 'voting_ends_at']);
        });
    }
};
