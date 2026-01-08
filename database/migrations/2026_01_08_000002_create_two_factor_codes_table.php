<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Two-Factor Codes Table
 * 
 * Security considerations:
 * - OTP codes are stored as hashed values (never plaintext)
 * - Expiration tracking for time-limited codes
 * - Attempt counter for brute-force protection
 * - Single-use enforcement via used_at timestamp
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('two_factor_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code_hash', 255)->comment('Hashed OTP code - never store plaintext');
            $table->timestamp('expires_at')->comment('When this OTP expires');
            $table->unsignedTinyInteger('attempts')->default(0)->comment('Failed verification attempts');
            $table->timestamp('used_at')->nullable()->comment('When this OTP was successfully used');
            $table->string('ip_address', 45)->nullable()->comment('IP address that requested the code');
            $table->text('user_agent')->nullable()->comment('Browser/device info');
            $table->timestamps();

            // Indexes for efficient lookups
            $table->index(['user_id', 'expires_at']);
            $table->index(['user_id', 'used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('two_factor_codes');
    }
};
