#!/usr/bin/env php
<?php

/**
 * Test Script untuk Notifikasi Aplikasi
 * 
 * Script ini untuk testing notifikasi aplikasi diterima/ditolak
 * 
 * Usage:
 *   php tests/test-notification.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Application;
use App\Notifications\ApplicationVerified;

echo "========================================\n";
echo "Test Notifikasi Aplikasi\n";
echo "========================================\n\n";

// 1. Cari user peserta
echo "1. Mencari user peserta...\n";
$user = User::where('role', 'peserta')->first();

if (!$user) {
    echo "❌ Tidak ada user dengan role 'peserta'\n";
    exit(1);
}

echo "✅ User ditemukan: {$user->name} (ID: {$user->id})\n\n";

// 2. Cari aplikasi user tersebut
echo "2. Mencari aplikasi user...\n";
$application = Application::where('user_id', $user->id)->first();

if (!$application) {
    echo "❌ User tidak memiliki aplikasi\n";
    exit(1);
}

echo "✅ Aplikasi ditemukan: {$application->name}\n";
echo "   Status: {$application->status}\n\n";

// 3. Test notifikasi rejected
echo "3. Mengirim notifikasi 'rejected'...\n";
try {
    $user->notify(new ApplicationVerified($application, 'rejected'));
    echo "✅ Notifikasi 'rejected' berhasil dikirim\n\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}

sleep(2);

// 4. Test notifikasi verified
echo "4. Mengirim notifikasi 'verified'...\n";
try {
    $user->notify(new ApplicationVerified($application, 'verified'));
    echo "✅ Notifikasi 'verified' berhasil dikirim\n\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}

// 5. Cek database
echo "5. Verifikasi notifikasi di database...\n";
$count = $user->notifications()->count();
echo "✅ Total notifikasi: {$count}\n\n";

// 6. Tampilkan notifikasi terakhir
echo "6. Notifikasi terakhir:\n";
$latestNotification = $user->notifications()->latest()->first();
if ($latestNotification) {
    echo "   ID: {$latestNotification->id}\n";
    echo "   Type: {$latestNotification->type}\n";
    echo "   Title: {$latestNotification->data['title']}\n";
    echo "   Message: {$latestNotification->data['message']}\n";
    echo "   Read: " . ($latestNotification->read_at ? 'Yes' : 'No') . "\n";
} else {
    echo "   Tidak ada notifikasi\n";
}

echo "\n========================================\n";
echo "Testing selesai!\n";
echo "========================================\n";
echo "\nCek di browser:\n";
echo "1. Login sebagai: {$user->email}\n";
echo "2. Lihat NotificationBell di navbar\n";
echo "3. Notifikasi harus muncul dengan badge merah\n";
echo "\nUntuk test real-time:\n";
echo "1. Login peserta di browser\n";
echo "2. Jalankan script ini lagi\n";
echo "3. Notifikasi harus muncul otomatis tanpa refresh\n\n";
