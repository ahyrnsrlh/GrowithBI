<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration updates the status enum to include the new 'letter_ready' status
     * and adds tracking columns for notification idempotency.
     */
    public function up(): void
    {
        // Step 1: Update existing 'accepted' applications that have acceptance letters to temporary value
        // We'll update them to 'letter_ready' after modifying the enum
        
        // Step 2: Modify the status enum to include 'letter_ready'
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'menunggu',              -- Initial state: Application submitted
            'in_review',             -- Documents being reviewed / Selection in progress
            'interview_scheduled',   -- Interview scheduled
            'accepted',              -- Application accepted (awaiting letter)
            'rejected',              -- Application rejected
            'letter_ready',          -- Acceptance letter uploaded and ready
            'expired'                -- Application expired
        ) DEFAULT 'menunggu'");

        // Step 3: Update applications that already have acceptance letters to 'letter_ready'
        DB::table('applications')
            ->where('status', 'accepted')
            ->whereNotNull('acceptance_letter_path')
            ->update(['status' => 'letter_ready']);

        // Step 4: Add notification tracking columns for idempotency
        Schema::table('applications', function (Blueprint $table) {
            // Track last notification sent for this application
            $table->string('last_notification_event')->nullable()->after('status');
            $table->timestamp('last_notification_at')->nullable()->after('last_notification_event');
            
            // Add rejection reason field if not exists
            if (!Schema::hasColumn('applications', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('admin_notes');
            }
        });

        // Step 5: Add index for faster status queries
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_applications_status_created');
            $table->index(['user_id', 'status'], 'idx_applications_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_applications_status_created');
            $table->dropIndex('idx_applications_user_status');
        });

        // Remove notification tracking columns
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['last_notification_event', 'last_notification_at']);
            
            if (Schema::hasColumn('applications', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });

        // Revert 'letter_ready' back to 'accepted'
        DB::table('applications')
            ->where('status', 'letter_ready')
            ->update(['status' => 'accepted']);

        // Restore original enum without 'letter_ready'
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'menunggu',
            'in_review',
            'interview_scheduled',
            'accepted',
            'rejected',
            'expired'
        ) DEFAULT 'menunggu'");
    }
};
