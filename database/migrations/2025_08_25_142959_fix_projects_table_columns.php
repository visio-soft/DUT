<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Sadece created_by_id sütununu ekle (diğerleri zaten var)
            $table->unsignedBigInteger('created_by_id')->nullable()->after('category_id');
        });
        
        // Mevcut kayıtları güncelle - varsayılan user ID ata
        DB::table('projects')->update([
            'created_by_id' => 1 // Varsayılan user ID
        ]);
        
        // Şimdi created_by_id'yi not null yap ve foreign key ekle
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->foreign('created_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
        });
    }
};
