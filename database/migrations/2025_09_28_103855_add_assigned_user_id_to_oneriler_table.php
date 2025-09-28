<?php<?php



use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;use Illuminate\Support\Facades\Schema;



return new class extends Migrationreturn new class extends Migration

{{

    /**    /**

     * Run the migrations.     * Run the migrations.

     */     */

    public function up(): void    public function up(): void

    {    {

        Schema::table('oneriler', function (Blueprint $table) {        Schema::table('oneriler', function (Blueprint $table) {

            $table->foreignId('assigned_user_id')            //

                  ->nullable()        });

                  ->constrained('users')    }

                  ->nullOnDelete()

                  ->comment('Öneriyi oluşturan kişiyi temsil eder, null ise kimse oluşturmamış gibi görünür');    /**

            $table->index('assigned_user_id');     * Reverse the migrations.

        });     */

    }    public function down(): void

    {

    /**        Schema::table('oneriler', function (Blueprint $table) {

     * Reverse the migrations.            //

     */        });

    public function down(): void    }

    {};

        Schema::table('oneriler', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropIndex(['assigned_user_id']);
            $table->dropColumn('assigned_user_id');
        });
    }
};
