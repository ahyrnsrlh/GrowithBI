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
        Schema::table('applications', function (Blueprint $table) {
            // Add single column indexes for frequently queried fields
            if (!Schema::hasColumn('applications', 'user_id') || 
                !collect(Schema::getIndexes('applications'))->pluck('name')->contains('applications_user_id_index')) {
                $table->index('user_id', 'applications_user_id_index');
            }
            
            if (!Schema::hasColumn('applications', 'division_id') || 
                !collect(Schema::getIndexes('applications'))->pluck('name')->contains('applications_division_id_index')) {
                $table->index('division_id', 'applications_division_id_index');
            }
            
            if (!Schema::hasColumn('applications', 'status') || 
                !collect(Schema::getIndexes('applications'))->pluck('name')->contains('applications_status_index')) {
                $table->index('status', 'applications_status_index');
            }
            
            // Add composite index for common query patterns (user_id + division_id + status)
            if (!collect(Schema::getIndexes('applications'))->pluck('name')->contains('applications_user_division_status_index')) {
                $table->index(['user_id', 'division_id', 'status'], 'applications_user_division_status_index');
            }
            
            // Add index for email lookups
            if (!Schema::hasColumn('applications', 'email') || 
                !collect(Schema::getIndexes('applications'))->pluck('name')->contains('applications_email_index')) {
                $table->index('email', 'applications_email_index');
            }
        });

        Schema::table('divisions', function (Blueprint $table) {
            // Add index for is_active column
            if (!Schema::hasColumn('divisions', 'is_active') || 
                !collect(Schema::getIndexes('divisions'))->pluck('name')->contains('divisions_is_active_index')) {
                $table->index('is_active', 'divisions_is_active_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex('applications_user_division_status_index');
            $table->dropIndex('applications_email_index');
            $table->dropIndex('applications_status_index');
            $table->dropIndex('applications_division_id_index');
            $table->dropIndex('applications_user_id_index');
        });

        Schema::table('divisions', function (Blueprint $table) {
            $table->dropIndex('divisions_is_active_index');
        });
    }
};
