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
        Schema::table('logbooks', function (Blueprint $table) {
            if (!Schema::hasColumn('logbooks', 'title')) {
                $table->string('title')->after('division_id');
            }
            if (!Schema::hasColumn('logbooks', 'duration')) {
                $table->decimal('duration', 3, 1)->after('date'); // jam kerja
            }
            if (!Schema::hasColumn('logbooks', 'learning_points')) {
                $table->text('learning_points')->nullable()->after('activities');
            }
            if (!Schema::hasColumn('logbooks', 'reviewer_id')) {
                $table->foreignId('reviewer_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn(['title', 'duration', 'learning_points', 'challenges', 'attachments', 'reviewer_id']);
            
            // Restore dropped columns
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->text('notes')->nullable();
            $table->text('supervisor_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
