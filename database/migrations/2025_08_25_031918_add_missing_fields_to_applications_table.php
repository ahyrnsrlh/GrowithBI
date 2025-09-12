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
            // Add gpa field that seems to be required
            if (!Schema::hasColumn('applications', 'gpa')) {
                $table->decimal('gpa', 4, 2)->nullable()->default(0.00);
            }
            
            // Add other potentially missing fields
            if (!Schema::hasColumn('applications', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'portfolio_url')) {
                $table->string('portfolio_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['gpa', 'birth_date', 'gender', 'portfolio_url']);
        });
    }
};
