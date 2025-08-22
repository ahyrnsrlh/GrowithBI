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
            // Check if columns exist before dropping
            if (Schema::hasColumn('users', 'surat_lamaran_path')) {
                $table->dropColumn('surat_lamaran_path');
            }
            if (Schema::hasColumn('users', 'ijazah_path')) {
                $table->dropColumn('ijazah_path');
            }
            if (Schema::hasColumn('users', 'sertifikat_path')) {
                $table->dropColumn('sertifikat_path');
            }
            
            // Add new document columns if they don't exist
            if (!Schema::hasColumn('users', 'surat_pengantar_path')) {
                $table->string('surat_pengantar_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'motivation_letter_path')) {
                $table->string('motivation_letter_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'npwp_path')) {
                $table->string('npwp_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'buku_rekening_path')) {
                $table->string('buku_rekening_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'pas_foto_path')) {
                $table->string('pas_foto_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'surat_pengantar_path',
                'motivation_letter_path',
                'npwp_path',
                'buku_rekening_path',
                'pas_foto_path'
            ]);
            
            // Add back old columns
            $table->string('surat_lamaran_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('sertifikat_path')->nullable();
        });
    }
};
