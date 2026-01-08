<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add Two-Factor Authentication Fields to Users Table
 * 
 * Security considerations:
 * - two_factor_enabled: Controls whether 2FA is active for the user
 * - two_factor_verified_at: Tracks when 2FA was last verified in the session
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(true)->after('is_active')
                    ->comment('Whether 2FA is enabled for this user');
            }
            if (!Schema::hasColumn('users', 'two_factor_verified_at')) {
                $table->timestamp('two_factor_verified_at')->nullable()->after('two_factor_enabled')
                    ->comment('Timestamp of last successful 2FA verification');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_enabled', 'two_factor_verified_at']);
        });
    }
};
