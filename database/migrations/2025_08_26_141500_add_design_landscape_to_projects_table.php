<?php

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
            $table->json('design_landscape')->nullable()->after('design_completed');
        });

        // Copy existing design data from project_designs (if any)
        try {
            $designs = DB::table('project_designs')->get();
            foreach ($designs as $d) {
                if (isset($d->project_id)) {
                    DB::table('projects')
                        ->where('id', $d->project_id)
                        ->update(['design_landscape' => $d->design_data]);
                }
            }
        } catch (\Exception $e) {
            // Ignore if table does not exist yet in some environments
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'design_landscape')) {
                $table->dropColumn('design_landscape');
            }
        });
    }
};
