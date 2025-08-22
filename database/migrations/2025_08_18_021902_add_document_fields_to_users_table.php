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
            $table->string('profile_photo_path')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->integer('semester')->nullable();
            
            // Document fields
            $table->string('ktp_path')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('surat_lamaran_path')->nullable();
            $table->string('transkrip_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('sertifikat_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo_path',
                'phone',
                'address',
                'university',
                'major',
                'semester',
                'ktp_path',
                'cv_path',
                'surat_lamaran_path',
                'transkrip_path',
                'ijazah_path',
                'sertifikat_path'
            ]);
        });
    }
};
