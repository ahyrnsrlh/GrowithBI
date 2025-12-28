<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Memperbaiki nilai enum status dari format underscore Indonesia ke format underscore English
     * yang konsisten dengan kode aplikasi.
     */
    public function up(): void
    {
        // Step 1: Ubah nilai existing data dari format lama ke format baru
        DB::table('applications')
            ->where('status', 'dalam_review')
            ->update(['status' => 'menunggu']); // Temporary value
            
        DB::table('applications')
            ->where('status', 'wawancara_dijadwalkan')
            ->update(['status' => 'menunggu']); // Temporary value
            
        DB::table('applications')
            ->where('status', 'diterima')
            ->update(['status' => 'menunggu']); // Temporary value
            
        DB::table('applications')
            ->where('status', 'ditolak')
            ->update(['status' => 'menunggu']); // Temporary value
            
        DB::table('applications')
            ->where('status', 'surat_dikirim')
            ->update(['status' => 'menunggu']); // Temporary value
            
        DB::table('applications')
            ->where('status', 'kedaluwarsa')
            ->update(['status' => 'menunggu']); // Temporary value

        // Step 2: Modify enum dengan nilai baru yang konsisten dengan kode
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'menunggu',              -- Initial state: Application submitted
            'in_review',             -- Documents being reviewed
            'interview_scheduled',   -- Interview scheduled
            'accepted',              -- Application accepted
            'rejected',              -- Application rejected
            'expired'                -- Application expired
        ) DEFAULT 'menunggu'");
        
        // Step 3: Set semua ke 'menunggu' sebagai default safe state
        // Admin bisa re-update status yang benar dari dashboard
        // (Lebih aman daripada mencoba mapping otomatis yang mungkin salah)
        DB::statement("UPDATE applications SET status = 'menunggu' WHERE status NOT IN ('menunggu', 'in_review', 'interview_scheduled', 'accepted', 'rejected', 'expired')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Kembalikan ke format lama
        DB::table('applications')
            ->where('status', 'in_review')
            ->update(['status' => 'menunggu']); // Temporary
            
        DB::table('applications')
            ->where('status', 'interview_scheduled')
            ->update(['status' => 'menunggu']); // Temporary
            
        DB::table('applications')
            ->where('status', 'accepted')
            ->update(['status' => 'menunggu']); // Temporary
            
        DB::table('applications')
            ->where('status', 'rejected')
            ->update(['status' => 'menunggu']); // Temporary
            
        DB::table('applications')
            ->where('status', 'expired')
            ->update(['status' => 'menunggu']); // Temporary

        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'menunggu',
            'dalam_review',
            'wawancara_dijadwalkan',
            'diterima',
            'ditolak',
            'surat_dikirim',
            'kedaluwarsa'
        ) DEFAULT 'menunggu'");
    }
};

