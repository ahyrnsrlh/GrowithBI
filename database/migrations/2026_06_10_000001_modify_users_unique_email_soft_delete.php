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
        Schema::table('users', function (Blueprint $table) {
            // Drop the old unique index on email and replace with composite unique(email, deleted_at)
            try {
                $table->dropUnique(['email']);
            } catch (\Exception $e) {
                // index might already be dropped, ignore
            }

            $table->unique(['email', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique(['email', 'deleted_at']);
            } catch (\Exception $e) {
            }

            $table->unique('email');
        });
    }
};
