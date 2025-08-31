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
            $table->string('acceptance_letter_path')->nullable()->after('status');
            $table->timestamp('acceptance_letter_uploaded_at')->nullable()->after('acceptance_letter_path');
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('acceptance_letter_uploaded_at');
            
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn(['acceptance_letter_path', 'acceptance_letter_uploaded_at', 'uploaded_by']);
        });
    }
};
