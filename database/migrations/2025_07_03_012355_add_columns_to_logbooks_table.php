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
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('division_id')->constrained()->onDelete('cascade')->after('user_id');
            $table->date('date')->after('division_id');
            $table->time('time_in')->after('date');
            $table->time('time_out')->nullable()->after('time_in');
            $table->text('activities')->after('time_out');
            $table->text('notes')->nullable()->after('activities');
            $table->string('status')->default('draft')->after('notes'); // draft, submitted, approved, rejected
            $table->text('supervisor_notes')->nullable()->after('status');
            $table->timestamp('submitted_at')->nullable()->after('supervisor_notes');
            $table->timestamp('approved_at')->nullable()->after('submitted_at');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');

            $table->index(['user_id', 'date']);
            $table->index(['division_id', 'date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['division_id']);
            $table->dropForeign(['user_id']);
            
            $table->dropIndex(['user_id', 'date']);
            $table->dropIndex(['division_id', 'date']);
            $table->dropIndex(['status']);
            
            $table->dropColumn([
                'user_id', 'division_id', 'date', 'time_in', 'time_out',
                'activities', 'notes', 'status', 'supervisor_notes',
                'submitted_at', 'approved_at', 'approved_by'
            ]);
        });
    }
};
