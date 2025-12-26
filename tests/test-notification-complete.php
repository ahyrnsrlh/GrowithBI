<?php

/**
 * Test Complete Notification System
 * Tests both real-time notifications and email notifications
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Application;
use App\Notifications\ApplicationVerified;
use App\Mail\ApplicationStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

echo "\n🔍 TESTING COMPLETE NOTIFICATION SYSTEM\n";
echo str_repeat("=", 60) . "\n\n";

// 1. Find test application
$application = Application::with(['user', 'division'])->first();

if (!$application) {
    echo "❌ Tidak ada aplikasi yang ditemukan!\n";
    exit(1);
}

echo "✅ Aplikasi ditemukan:\n";
echo "   ID: {$application->id}\n";
echo "   Nama: {$application->name}\n";
echo "   Email: {$application->email}\n";
echo "   Divisi: {$application->division->name}\n";
echo "   User ID: " . ($application->user_id ?? 'NULL') . "\n\n";

// 2. Count jobs before
$jobsBefore = DB::table('jobs')->count();
echo "📊 Jobs di queue sebelum test: $jobsBefore\n\n";

// 3. Test Real-time Notification
echo "📢 Testing Real-time Notification...\n";
if ($application->user) {
    try {
        $application->user->notify(new ApplicationVerified($application, 'verified'));
        echo "   ✅ Notification sent to user\n";
    } catch (\Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ⚠️  Application doesn't have associated user\n";
}

// 4. Test Email Notification
echo "\n📧 Testing Email Notification...\n";
if ($application->email) {
    try {
        Mail::to($application->email)->queue(
            new ApplicationStatusMail($application, 'approved')
        );
        echo "   ✅ Email queued to: {$application->email}\n";
    } catch (\Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ⚠️  Application doesn't have email\n";
}

// 5. Count jobs after
sleep(1); // Wait for jobs to be queued
$jobsAfter = DB::table('jobs')->count();
$newJobs = $jobsAfter - $jobsBefore;

echo "\n📊 Jobs di queue setelah test: $jobsAfter\n";
echo "📊 Jobs baru yang dibuat: $newJobs\n\n";

// 6. Check queue worker status
echo "🔄 Queue Worker Status:\n";
if ($jobsAfter > 0) {
    echo "   ⚠️  Ada $jobsAfter jobs menunggu diproses\n";
    echo "   💡 Pastikan queue worker berjalan: php artisan queue:work\n";
} else {
    echo "   ✅ Semua jobs sudah diproses\n";
}

// 7. Check notifications in database
echo "\n📋 Notifications in Database:\n";
$notificationCount = DB::table('notifications')
    ->where('notifiable_id', $application->user_id)
    ->count();
echo "   Total notifications: $notificationCount\n";

// 8. Instructions
echo "\n" . str_repeat("=", 60) . "\n";
echo "📝 NEXT STEPS:\n";
echo "1. Pastikan queue worker berjalan di terminal terpisah\n";
echo "2. Cek dashboard peserta untuk melihat notifikasi real-time\n";
echo "3. Cek email peserta ({$application->email}) untuk melihat email\n";
echo "4. Monitor jobs dengan: php artisan queue:work --verbose\n";
echo str_repeat("=", 60) . "\n\n";
