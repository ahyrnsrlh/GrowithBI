<?php

/**
 * Test script untuk memastikan admin mendapat notifikasi
 * Run: php artisan tinker < test-admin-notification.php
 */

use App\Models\User;
use App\Models\Attendance;
use App\Notifications\AttendanceNotification;

// Get admin user
$admin = User::where('role', 'admin')->first();

if (!$admin) {
    echo "❌ Admin user not found!\n";
    exit;
}

echo "✅ Admin found: {$admin->name} (ID: {$admin->id})\n";

// Check admin notifications
$notificationCount = $admin->notifications()->count();
$unreadCount = $admin->unreadNotifications()->count();

echo "📊 Admin notifications:\n";
echo "  - Total: {$notificationCount}\n";
echo "  - Unread: {$unreadCount}\n";

// Get latest notifications
$latestNotifications = $admin->notifications()->latest()->take(5)->get();

echo "\n📝 Latest 5 notifications:\n";
foreach ($latestNotifications as $notif) {
    $type = $notif->data['type'] ?? 'unknown';
    $title = $notif->data['title'] ?? 'No title';
    $isRead = $notif->read_at ? '✓' : '✗';
    echo "  {$isRead} [{$type}] {$title} - " . $notif->created_at->diffForHumans() . "\n";
}

// Create test notification
echo "\n🧪 Creating test notification...\n";

// Get a peserta user for testing
$peserta = User::where('role', 'peserta')->first();

if ($peserta) {
    // Get or create attendance
    $attendance = Attendance::firstOrCreate([
        'user_id' => $peserta->id,
        'date' => now()->toDateString()
    ], [
        'check_in' => now(),
        'status' => 'On-Time',
        'latitude' => -5.397140,
        'longitude' => 105.266792
    ]);
    
    // Send notification to admin
    $admin->notify(new AttendanceNotification($attendance, 'checked_in'));
    
    echo "✅ Test notification sent to admin!\n";
    echo "  - From: {$peserta->name}\n";
    echo "  - Type: checked_in\n";
    echo "  - Attendance ID: {$attendance->id}\n";
    
    // Verify notification was created
    $newCount = $admin->unreadNotifications()->count();
    echo "\n📊 Admin unread count after test: {$newCount}\n";
    
    if ($newCount > $unreadCount) {
        echo "✅ SUCCESS: Notification count increased!\n";
    } else {
        echo "⚠️ WARNING: Notification count did not increase\n";
    }
} else {
    echo "❌ No peserta user found for testing\n";
}

echo "\n✅ Test complete!\n";
