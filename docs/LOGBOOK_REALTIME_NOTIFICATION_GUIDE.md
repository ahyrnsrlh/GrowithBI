# Logbook Real-Time Notification System - Complete Guide

> Last updated: January 3, 2026

## 📋 Overview

This document describes the real-time notification system for the Logbook feature in GrowithBI. The system uses:

-   **Laravel 11** with Notifications
-   **Laravel Reverb** for WebSocket broadcasting
-   **Vue 3 + Laravel Echo** for frontend real-time updates
-   **Database queue** for job processing

---

## 🏗️ Architecture

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Controller    │───▶│  Notification    │───▶│     Queue       │
│  (trigger)      │    │  (ShouldQueue)   │    │   (database)    │
└─────────────────┘    └──────────────────┘    └────────┬────────┘
                                                        │
                                                        ▼
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Vue (Echo)    │◀───│   Reverb WS      │◀───│  Queue Worker   │
│   Frontend      │    │   Server         │    │  (broadcast)    │
└─────────────────┘    └──────────────────┘    └─────────────────┘
```

---

## 📁 Key Files

| File                                               | Purpose                       |
| -------------------------------------------------- | ----------------------------- |
| `app/Notifications/LogbookNotification.php`        | Main notification class       |
| `app/Http/Controllers/LogbookController.php`       | Participant's logbook actions |
| `app/Http/Controllers/Admin/LogbookController.php` | Admin/Mentor review actions   |
| `config/broadcasting.php`                          | Reverb broadcasting config    |
| `routes/channels.php`                              | Channel authorization rules   |
| `resources/js/bootstrap.js`                        | Echo initialization           |
| `resources/js/Components/NotificationBell.vue`     | Real-time notification UI     |

---

## 🔔 Event Types

| Type                  | Target       | Email | Description                       |
| --------------------- | ------------ | ----- | --------------------------------- |
| `submitted`           | Admin/Mentor | ❌    | Logbook baru dikirim oleh peserta |
| `approved`            | Participant  | ✅    | Logbook disetujui supervisor      |
| `rejected`            | Participant  | ✅    | Logbook ditolak                   |
| `revision_requested`  | Participant  | ❌    | Permintaan revisi logbook         |
| `commented`           | Participant  | ❌    | Komentar baru pada logbook        |
| `reminder`            | Participant  | ❌    | Reminder pengisian logbook        |
| `pending_over_3_days` | Admin        | ❌    | Logbook pending > 3 hari          |
| `not_submitted_today` | Admin        | ❌    | User belum submit hari ini        |

---

## 🚀 Quick Start Commands

### 1. Start Reverb WebSocket Server

```bash
php artisan reverb:start --debug
```

### 2. Start Queue Worker

```bash
php artisan queue:work --queue=default --tries=3
```

### 3. Clear Caches (if config changed)

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 4. Rebuild Frontend

```bash
npm run dev
# or for production
npm run build
```

---

## 🧪 Testing with Tinker

### Test 1: Send notification to a specific user

```php
php artisan tinker

// Find a logbook with user
$logbook = \App\Models\Logbook::with('user')->latest()->first();

// Send notification to the user
$logbook->user->notify(new \App\Notifications\LogbookNotification($logbook, 'approved'));

// Check if notification was created in database
\DB::table('notifications')->latest()->first();
```

### Test 2: Verify broadcast connection

```php
php artisan tinker

// Test event broadcasting
event(new \Illuminate\Broadcasting\TestBroadcast);
```

### Test 3: Check failed jobs

```bash
php artisan queue:failed
php artisan queue:retry all
```

---

## 🔍 Debugging Checklist

### Backend Issues

| Check                     | Command/Action                     | Expected Result               |
| ------------------------- | ---------------------------------- | ----------------------------- |
| Queue worker running      | `php artisan queue:work`           | Shows "Processing jobs..."    |
| Reverb server running     | `php artisan reverb:start --debug` | Shows "Server running at..."  |
| Notification in DB        | Check `notifications` table        | New row with `type` & `data`  |
| Failed jobs               | `php artisan queue:failed`         | Empty or shows failed jobs    |
| Broadcasting config       | Check `config/broadcasting.php`    | `default` = `reverb`          |
| .env BROADCAST_CONNECTION | Check `.env`                       | `BROADCAST_CONNECTION=reverb` |

### Frontend Issues

| Check                 | How to Verify                                       | Solution                    |
| --------------------- | --------------------------------------------------- | --------------------------- |
| Echo initialized      | Browser console: "Echo initialized successfully"    | Check `bootstrap.js`        |
| WebSocket connected   | Browser console: "WebSocket connected"              | Verify Reverb is running    |
| Channel subscribed    | Network tab → WS → messages                         | Check channel name matches  |
| CSRF token present    | `document.querySelector('meta[name="csrf-token"]')` | Ensure meta tag exists      |
| Auth endpoint working | POST `/broadcasting/auth` returns 200               | Check `routes/channels.php` |

### Common Issues & Solutions

#### 1. Notification saved to DB but not broadcasting

```
Cause: Queue worker not processing broadcast channel
Fix: php artisan queue:work
```

#### 2. "Channel not authorized" error

```
Cause: Channel name mismatch between frontend and backend
Fix: Ensure frontend uses 'App.Models.User.{id}' not 'App.User.{id}'
```

#### 3. WebSocket connection fails

```
Cause: Reverb not running or wrong port
Fix:
1. php artisan reverb:start
2. Check REVERB_PORT in .env matches frontend
3. Check firewall allows port 8080
```

#### 4. Notification received but UI not updating

```
Cause: handleRealtimeNotification not updating reactive state
Fix: Check NotificationBell.vue handleRealtimeNotification function
```

#### 5. Email not sending for approved/rejected

```
Cause: Mail config incorrect or queue not processing mail
Fix:
1. Check MAIL_* settings in .env
2. php artisan queue:work --queue=default,mail
```

---

## 📝 Example Code

### Triggering Notification from Controller

```php
use App\Notifications\LogbookNotification;

// After approving a logbook
$logbook->user->notify(new LogbookNotification(
    logbook: $logbook->fresh(),
    type: 'approved',
    senderId: auth()->id(),
    receiverRole: 'user'
));
```

### Frontend Echo Listener

```javascript
// In NotificationBell.vue or any component
window.Echo.private(`App.Models.User.${userId}`).notification(
    (notification) => {
        console.log("Received:", notification);
        // Update UI
        notifications.value.unshift(notification);
        unreadCount.value++;
    }
);
```

---

## 🔧 Environment Variables Required

```env
# Broadcasting
BROADCAST_CONNECTION=reverb

# Reverb WebSocket
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite (Frontend)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Queue
QUEUE_CONNECTION=database
```

---

## 🎯 Production Checklist

-   [ ] Reverb running as a service (Supervisor/systemd)
-   [ ] Queue worker running as a service
-   [ ] REVERB_SCHEME=https for production
-   [ ] REVERB_HOST matches your domain
-   [ ] SSL certificate configured for WSS
-   [ ] Firewall allows WebSocket port
-   [ ] Redis configured for scaling (optional)
-   [ ] Failed jobs monitoring configured

---

## 📊 Monitoring Commands

```bash
# Watch queue in real-time
php artisan queue:work --verbose

# Check queue size
php artisan queue:size

# Monitor failed jobs
php artisan queue:failed

# Retry all failed jobs
php artisan queue:retry all

# Prune old failed jobs
php artisan queue:prune-failed --hours=24
```

---

## 🆘 Emergency Recovery

If notifications are completely broken:

```bash
# 1. Stop everything
# 2. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan queue:flush
php artisan route:clear
php artisan view:clear

# 3. Restart services
php artisan reverb:start --debug &
php artisan queue:work --tries=3 &

# 4. Rebuild frontend
npm run build

# 5. Test with tinker
php artisan tinker
>>> $user = \App\Models\User::first();
>>> $logbook = \App\Models\Logbook::first();
>>> $user->notify(new \App\Notifications\LogbookNotification($logbook, 'approved'));
```

---

## ✅ Success Indicators

When everything is working correctly, you should see:

1. **Browser Console:**

    - "✅ Laravel Echo initialized successfully"
    - "✅ WebSocket connected"
    - "📡 Subscribing to channel: App.Models.User.X"

2. **Reverb Terminal:**

    - "Connection established"
    - "Subscribed to private-App.Models.User.X"
    - "Message sent to channel"

3. **Queue Worker Terminal:**

    - "Processing: App\Notifications\LogbookNotification"
    - "Processed: App\Notifications\LogbookNotification"

4. **Database:**

    - New row in `notifications` table
    - `type` = "App\Notifications\LogbookNotification"
    - `data` contains JSON with title, message, etc.

5. **UI:**
    - Bell icon shows updated count
    - Notification appears in dropdown without page refresh
    - Browser notification shows (if permitted)
