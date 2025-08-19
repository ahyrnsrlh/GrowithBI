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
            $table->string('ktp_path')->nullable()->after('address');
            $table->string('cv_path')->nullable()->after('ktp_path');
            $table->string('surat_lamaran_path')->nullable()->after('cv_path');
            $table->string('transkrip_path')->nullable()->after('surat_lamaran_path');
            $table->string('foto_path')->nullable()->after('transkrip_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ktp_path',
                'cv_path', 
                'surat_lamaran_path',
                'transkrip_path',
                'foto_path'
            ]);
        });
    }
};
