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
            // Change status field to enum with proper values
            $table->enum('status', ['draft', 'submitted', 'approved', 'revision', 'rejected'])
                  ->default('draft')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            // Change back to string
            $table->string('status')->default('draft')->change();
        });
    }
};
