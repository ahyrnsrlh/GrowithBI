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
        Schema::create('application_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->decimal('competency_score', 5, 2);
            $table->decimal('motivation_score', 5, 2);
            $table->decimal('interview_score', 5, 2);
            $table->decimal('competency_weight', 5, 2);
            $table->decimal('motivation_weight', 5, 2);
            $table->decimal('interview_weight', 5, 2);
            $table->decimal('final_score', 5, 2);
            $table->string('recommendation_level');
            $table->text('reviewer_notes')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('score_history')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_evaluations');
    }
};
