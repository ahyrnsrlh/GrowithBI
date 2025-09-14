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
            $table->json('job_description')->nullable()->after('description');
            $table->date('application_deadline')->nullable()->after('end_date');
            $table->date('selection_announcement')->nullable()->after('application_deadline');
            
            // Change requirements to json if it's not already
            $table->json('requirements')->nullable()->change();
            
            // Add quota column if it doesn't exist (alias for max_interns)
            if (!Schema::hasColumn('divisions', 'quota')) {
                $table->integer('quota')->nullable()->after('requirements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn([
                'job_description',
                'application_deadline', 
                'selection_announcement'
            ]);
            
            // Revert requirements back to text if needed
            $table->text('requirements')->nullable()->change();
            
            // Drop quota column if it was added
            if (Schema::hasColumn('divisions', 'quota')) {
                $table->dropColumn('quota');
            }
        });
    }
};
