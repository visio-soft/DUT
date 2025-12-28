<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert old status values to new open/closed values
     */
    public function up(): void
    {
        // Map old statuses to new statuses
        // pending, under_review, active -> open (voting is still active)
        // approved, rejected, implemented -> closed (voting is finished)

        DB::table('suggestions')
            ->whereIn('status', ['pending', 'under_review', 'active'])
            ->update(['status' => 'open']);

        DB::table('suggestions')
            ->whereIn('status', ['approved', 'rejected', 'implemented'])
            ->update(['status' => 'closed']);
    }

    /**
     * Reverse the migrations.
     * Convert new status values back to old values (default to pending/approved)
     */
    public function down(): void
    {
        DB::table('suggestions')
            ->where('status', 'open')
            ->update(['status' => 'pending']);

        DB::table('suggestions')
            ->where('status', 'closed')
            ->update(['status' => 'approved']);
    }
};
