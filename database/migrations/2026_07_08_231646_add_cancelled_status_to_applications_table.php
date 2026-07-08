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
        // Modify status ENUM column (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
                'menunggu',
                'in_review',
                'interview_scheduled',
                'accepted',
                'rejected',
                'letter_ready',
                'expired',
                'cancelled'
            ) DEFAULT 'menunggu'");
        }

        // Add nullable fields
        Schema::table('applications', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->string('cancelled_by')->nullable()->after('cancelled_at');
            $table->text('cancellation_reason')->nullable()->after('cancelled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['cancelled_at', 'cancelled_by', 'cancellation_reason']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
                'menunggu',
                'in_review',
                'interview_scheduled',
                'accepted',
                'rejected',
                'letter_ready',
                'expired'
            ) DEFAULT 'menunggu'");
        }
    }
};
