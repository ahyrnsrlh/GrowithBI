<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Two-Factor Audit Logs Table
 * 
 * Security audit trail for all 2FA-related events
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('two_factor_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_type', 50)->comment('Type of 2FA event');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable()->comment('Additional event data');
            $table->boolean('success')->default(true);
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            // Indexes for efficient querying
            $table->index(['user_id', 'event_type']);
            $table->index(['user_id', 'created_at']);
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('two_factor_audit_logs');
    }
};
