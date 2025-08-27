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
        Schema::create('logbook_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->string('type')->default('comment'); // comment, feedback, revision_request
            $table->boolean('is_internal')->default(false); // for mentor-only comments
            $table->timestamps();
            
            $table->index(['logbook_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbook_comments');
    }
};
