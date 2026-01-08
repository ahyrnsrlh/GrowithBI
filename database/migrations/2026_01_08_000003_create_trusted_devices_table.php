<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Trusted Devices Table
 * 
 * Security considerations:
 * - Device fingerprint stored as hash for privacy
 * - Expiration tracking for automatic device trust revocation
 * - Last used tracking for security auditing
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_hash', 255)->comment('Hashed device fingerprint');
            $table->string('device_name', 255)->nullable()->comment('Friendly device name for user reference');
            $table->string('ip_address', 45)->nullable()->comment('IP address when device was trusted');
            $table->text('user_agent')->nullable()->comment('Browser/device info');
            $table->timestamp('last_used_at')->nullable()->comment('Last successful login from this device');
            $table->timestamp('expires_at')->comment('When this trusted device expires');
            $table->timestamps();

            // Indexes for efficient lookups
            $table->index(['user_id', 'device_hash']);
            $table->index(['user_id', 'expires_at']);
            $table->unique(['user_id', 'device_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trusted_devices');
    }
};
