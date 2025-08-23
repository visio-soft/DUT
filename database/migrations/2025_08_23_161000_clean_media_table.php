<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sorunlu media kayıtlarını temizle
        DB::table('media')
            ->whereNull('file_name')
            ->orWhereNull('disk')
            ->orWhereNull('collection_name')
            ->orWhere('file_name', '')
            ->orWhere('disk', '')
            ->orWhere('collection_name', '')
            ->delete();
            
        // Media tablosunda eksik indeksleri ekle
        if (!Schema::hasIndex('media', 'media_model_type_model_id_index')) {
            Schema::table('media', function (Blueprint $table) {
                $table->index(['model_type', 'model_id'], 'media_model_type_model_id_index');
            });
        }
        
        if (!Schema::hasIndex('media', 'media_collection_name_index')) {
            Schema::table('media', function (Blueprint $table) {
                $table->index('collection_name', 'media_collection_name_index');
            });
        }
    }

    public function down(): void
    {
        // İndeksleri kaldır
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex('media_model_type_model_id_index');
            $table->dropIndex('media_collection_name_index');
        });
    }
};
