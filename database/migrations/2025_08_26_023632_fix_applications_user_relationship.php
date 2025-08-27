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
        // Update applications yang tidak memiliki user_id yang valid
        // Hapus aplikasi yang tidak memiliki user_id atau user yang tidak ada
        DB::statement('
            DELETE FROM applications 
            WHERE user_id IS NULL 
            OR user_id NOT IN (SELECT id FROM users)
        ');
        
        // Pastikan foreign key constraint ada
        Schema::table('applications', function (Blueprint $table) {
            // Drop existing foreign key jika ada
            try {
                $table->dropForeign(['user_id']);
            } catch (Exception $e) {
                // Ignore if constraint doesn't exist
            }
            
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
