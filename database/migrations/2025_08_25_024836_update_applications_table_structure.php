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
            // Add fields for storing user data directly (for easier querying)
            if (!Schema::hasColumn('applications', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('applications', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('applications', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('applications', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('applications', 'university')) {
                $table->string('university')->nullable();
            }
            if (!Schema::hasColumn('applications', 'major')) {
                $table->string('major')->nullable();
            }
            if (!Schema::hasColumn('applications', 'semester')) {
                $table->integer('semester')->nullable();
            }
            
            // Add motivation field (separate from motivation_letter file)
            if (!Schema::hasColumn('applications', 'motivation')) {
                $table->text('motivation')->nullable();
            }
            
            // Add document paths
            if (!Schema::hasColumn('applications', 'cv_path')) {
                $table->string('cv_path')->nullable();
            }
            if (!Schema::hasColumn('applications', 'surat_lamaran_path')) {
                $table->string('surat_lamaran_path')->nullable();
            }
            if (!Schema::hasColumn('applications', 'transkrip_path')) {
                $table->string('transkrip_path')->nullable();
            }
            if (!Schema::hasColumn('applications', 'ktp_path')) {
                $table->string('ktp_path')->nullable();
            }
            
            // Add interview fields
            if (!Schema::hasColumn('applications', 'interview_date')) {
                $table->datetime('interview_date')->nullable();
            }
            if (!Schema::hasColumn('applications', 'interview_location')) {
                $table->string('interview_location')->nullable();
            }
            if (!Schema::hasColumn('applications', 'notes')) {
                $table->text('notes')->nullable();
            }
            
            // Update status enum to include more statuses
            $table->enum('status', ['menunggu', 'dalam_review', 'wawancara', 'diterima', 'ditolak'])->default('menunggu')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'email', 'phone', 'address', 'university', 'major', 'semester',
                'motivation', 'cv_path', 'surat_lamaran_path', 'transkrip_path', 'ktp_path',
                'interview_date', 'interview_location', 'notes'
            ]);
        });
    }
};
