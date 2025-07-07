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
        Schema::table('divisions', function (Blueprint $table) {
            // Add missing columns for better division management
            $table->text('requirements')->nullable()->after('description');
            $table->integer('max_interns')->default(10)->after('requirements');
            
            // Make start_date and end_date nullable for flexibility
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            
            // Rename quota to max_interns for consistency
            $table->dropColumn('quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn(['requirements', 'max_interns']);
            $table->integer('quota')->default(10)->after('description');
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
        });
    }
};
