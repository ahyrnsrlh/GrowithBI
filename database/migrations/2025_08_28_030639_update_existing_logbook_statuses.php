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
        // Update any invalid status values to draft
        DB::table('logbooks')
            ->whereNotIn('status', ['draft', 'submitted', 'approved', 'revision', 'rejected'])
            ->update(['status' => 'draft']);
            
        // Update any null status to draft
        DB::table('logbooks')
            ->whereNull('status')
            ->update(['status' => 'draft']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse data changes
    }
};
