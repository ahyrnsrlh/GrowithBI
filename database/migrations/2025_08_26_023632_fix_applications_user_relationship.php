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
            // Check if foreign key exists and drop if it does
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'applications' 
                AND COLUMN_NAME = 'user_id' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ");
            
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
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
            // Check if foreign key exists before dropping
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'applications' 
                AND COLUMN_NAME = 'user_id' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ");
            
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }
        });
    }
};
