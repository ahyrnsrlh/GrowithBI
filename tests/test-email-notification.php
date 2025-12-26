#!/usr/bin/env php
<?php

/**
 * Test Script untuk Email Notification
 * 
 * Script ini untuk testing email notification aplikasi diterima/ditolak
 * 
 * Usage:
 *   php tests/test-email-notification.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Application;
use App\Mail\ApplicationStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "Test Email Notification - GrowithBI\n";
echo "========================================\n\n";

// 1. Cari aplikasi
echo "1. Mencari aplikasi untuk testing...\n";
$application = Application::with(['division'])->whereNotNull('email')->first();

if (!$application) {
    echo "❌ Tidak ada aplikasi dengan email\n";
    exit(1);
}

echo "✅ Aplikasi ditemukan:\n";
echo "   Nama: {$application->name}\n";
echo "   Email: {$application->email}\n";
echo "   Divisi: {$application->division->name}\n";
echo "   Status saat ini: {$application->status}\n\n";

// 2. Test email approved
echo "2. Mengirim test email 'APPROVED'...\n";
try {
    Mail::to($application->email)->send(new ApplicationStatusMail($application, 'approved'));
    echo "✅ Email 'APPROVED' berhasil dikirim (atau masuk queue)\n\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}

sleep(1);

// 3. Test email rejected
echo "3. Mengirim test email 'REJECTED'...\n";
try {
    Mail::to($application->email)->send(new ApplicationStatusMail($application, 'rejected'));
    echo "✅ Email 'REJECTED' berhasil dikirim (atau masuk queue)\n\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}

// 4. Check queue jobs
echo "4. Memeriksa queue jobs...\n";
$jobCount = DB::table('jobs')->count();
echo "✅ Total jobs di queue: {$jobCount}\n\n";

if ($jobCount > 0) {
    echo "⚠️  Queue worker belum memproses job.\n";
    echo "   Jalankan: php artisan queue:work\n\n";
}

// 5. Check failed jobs
echo "5. Memeriksa failed jobs...\n";
$failedCount = DB::table('failed_jobs')->count();
if ($failedCount > 0) {
    echo "⚠️  Ada {$failedCount} failed jobs\n";
    echo "   Check dengan: php artisan queue:failed\n";
    echo "   Retry dengan: php artisan queue:retry all\n\n";
} else {
    echo "✅ Tidak ada failed jobs\n\n";
}

echo "========================================\n";
echo "Testing selesai!\n";
echo "========================================\n\n";

echo "Langkah selanjutnya:\n";
echo "1. Start queue worker:\n";
echo "   php artisan queue:work\n\n";
echo "2. Check email di inbox:\n";
echo "   {$application->email}\n\n";
echo "3. Jika email tidak masuk:\n";
echo "   - Check spam folder\n";
echo "   - Check Laravel logs: storage/logs/laravel.log\n";
echo "   - Verify SMTP config di .env\n\n";

echo "4. Test dari admin panel:\n";
echo "   - Login sebagai admin\n";
echo "   - Approve/reject aplikasi\n";
echo "   - Check email peserta\n\n";
