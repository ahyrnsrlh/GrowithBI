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
        // First, remove any duplicate active applications by keeping only the most recent one
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                DELETE a1 FROM applications a1
                INNER JOIN applications a2 
                WHERE a1.email = a2.email 
                AND a1.status IN ('menunggu', 'dalam_review', 'wawancara', 'diterima')
                AND a2.status IN ('menunggu', 'dalam_review', 'wawancara', 'diterima')
                AND a1.created_at < a2.created_at
            ");
        } else {
            DB::statement("
                DELETE FROM applications 
                WHERE id NOT IN (
                    SELECT MAX(id) 
                    FROM applications 
                    WHERE status IN ('menunggu', 'dalam_review', 'wawancara', 'diterima') 
                    GROUP BY email
                ) AND status IN ('menunggu', 'dalam_review', 'wawancara', 'diterima')
            ");
        }

        // Add a unique index to prevent multiple active applications per email
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['email', 'status'], 'idx_email_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_email_status');
        });
    }
};
