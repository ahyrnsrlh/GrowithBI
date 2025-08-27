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
            // Add new fields for enhanced logbook system
            $table->integer('duration_hours')->nullable()->after('notes');
            $table->integer('duration_minutes')->nullable()->after('duration_hours');
            $table->string('attachment_path')->nullable()->after('duration_minutes');
            $table->text('mentor_feedback')->nullable()->after('supervisor_notes');
            $table->timestamp('reviewed_at')->nullable()->after('approved_at');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_by');
            $table->decimal('completion_percentage', 5, 2)->default(0)->after('reviewed_by');
            
            // Add indexes for performance
            $table->index('reviewed_at');
            $table->index('completion_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn([
                'duration_hours',
                'duration_minutes', 
                'attachment_path',
                'mentor_feedback',
                'reviewed_at',
                'reviewed_by',
                'completion_percentage'
            ]);
        });
    }
};
