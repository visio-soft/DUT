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
        Schema::table('oneriler', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Öneriyi atanan kullanıcıya bağlar; null ise atanmış değildir');

            $table->index('assigned_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropIndex(['assigned_user_id']);
            $table->dropColumn('assigned_user_id');
        });
    }
};
