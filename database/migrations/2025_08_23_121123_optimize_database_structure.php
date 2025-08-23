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
        // Projects tablosunu optimize et
        Schema::table('projects', function (Blueprint $table) {
            // Gereksiz sütunları kaldır
            if (Schema::hasColumn('projects', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('projects', 'created_by_id')) {
                $table->dropColumn('created_by_id');
            }
            if (Schema::hasColumn('projects', 'updated_by_id')) {
                $table->dropColumn('updated_by_id');
            }
            if (Schema::hasColumn('projects', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('projects', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('projects', 'image_path')) {
                $table->dropColumn('image_path'); // Spatie Media Library kullanıyoruz
            }
            if (Schema::hasColumn('projects', 'deleted_at')) {
                $table->dropColumn('deleted_at'); // Soft delete kaldır
            }
        });

        // Projects tablosunda gerekli sütunları optimize et
        Schema::table('projects', function (Blueprint $table) {
            // name sütununu title olarak yeniden adlandır
            $table->renameColumn('name', 'title');
        });

        Schema::table('projects', function (Blueprint $table) {
            // title alanını optimize et
            $table->string('title')->nullable(false)->change();
            
            // description gerekli yap
            $table->text('description')->nullable(false)->change();
            
            // budget gerekli yap
            $table->decimal('budget', 12, 2)->nullable(false)->change();
            
            // start_date ve end_date gerekli yap
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
            
            // city varsayılan olarak İstanbul yap
            $table->string('city', 50)->default('İstanbul')->change();
            
            // district gerekli yap
            $table->string('district', 100)->nullable(false)->change();
            
            // İndeksler ekle (performans için)
            $table->index(['category_id', 'district']);
            $table->index(['city', 'district']);
            $table->index('budget');
            $table->index(['start_date', 'end_date']);
        });

        // Categories tablosunu optimize et
        Schema::table('categories', function (Blueprint $table) {
            // name alanına indeks ekle
            $table->index('name');
            
            // parent_id alanına indeks ekle
            $table->index('parent_id');
        });

        // Objeler tablosunu optimize et
        Schema::table('objeler', function (Blueprint $table) {
            // isim alanına indeks ekle
            $table->index('isim');
            
            // isim alanını gerekli yap
            $table->string('isim')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Projects tablosuna geri ekleme
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->text('location')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('image_path', 300)->nullable();
            $table->softDeletes();
            
            // name sütununu geri ekle
            $table->renameColumn('title', 'name');
            
            // İndeksleri kaldır
            $table->dropIndex(['category_id', 'district']);
            $table->dropIndex(['city', 'district']);
            $table->dropIndex(['budget']);
            $table->dropIndex(['start_date', 'end_date']);
        });

        // Categories indekslerini kaldır
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['parent_id']);
        });

        // Objeler indekslerini kaldır
        Schema::table('objeler', function (Blueprint $table) {
            $table->dropIndex(['isim']);
        });
    }
};
