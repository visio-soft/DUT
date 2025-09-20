<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, migrate subcategories to become main categories
        // and update any projects using main categories to use default subcategory
        
        // Get all main categories (parent_id is null)
        $mainCategories = Category::whereNull('parent_id')->get();
        
        foreach ($mainCategories as $mainCategory) {
            // Get subcategories of this main category
            $subCategories = Category::where('parent_id', $mainCategory->id)->get();
            
            if ($subCategories->isNotEmpty()) {
                // Use the first subcategory as default for any projects linked to main category
                $defaultSubCategory = $subCategories->first();
                
                // Update any projects/oneriler that reference this main category
                DB::table('oneriler')
                    ->where('category_id', $mainCategory->id)
                    ->update(['category_id' => $defaultSubCategory->id]);
                
                // Remove parent_id from all subcategories to make them main categories
                Category::where('parent_id', $mainCategory->id)
                    ->update(['parent_id' => null]);
            }
            
            // Delete the main category
            $mainCategory->delete();
        }
        
        // Remove parent_id column from categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });
        
        // Remove is_main column if it exists
        if (Schema::hasColumn('categories', 'is_main')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('is_main');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back parent_id column
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
            $table->index('parent_id');
            $table->boolean('is_main')->default(false)->after('icon');
        });
    }
};
