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
            $table->text('challenges')->nullable();
            $table->text('attachments')->nullable();
            $table->string('attachment_path')->nullable();
            $table->text('mentor_feedback')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('reviewed_at');
            
            // Add indexes for performance
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn([
                'challenges',
                'attachments',
                'attachment_path',
                'mentor_feedback',
                'reviewed_at',
                'reviewed_by'
            ]);
        });
    }
};
