# Real-Time Notification System - GrowithBI

## 📋 Overview

Sistem notifikasi real-time yang dibangun menggunakan Laravel 12 + Vue 3 via Inertia.js dengan Laravel Broadcasting dan Pusher/Laravel WebSockets. Sistem ini memungkinkan user (peserta/admin) menerima notifikasi secara real-time tanpa reload halaman.

## 🏗️ Architecture

### Backend Components

1. **Broadcasting System**

    - Driver: Pusher (atau Laravel WebSockets untuk local development)
    - Channel: Private user channels (`App.User.{id}`)
    - Authentication: Laravel Sanctum via session

2. **Database**

    - Table: `notifications` (Laravel standard)
    - Columns: id, type, notifiable_type, notifiable_id, data (JSON), read_at, created_at, updated_at

3. **Notification Classes** (`app/Notifications/`)

    - `ApplicationSubmitted` - Notifikasi saat pendaftaran baru disubmit
    - `ApplicationVerified` - Notifikasi saat pendaftaran diverifikasi/ditolak
    - `AttendanceCheckedIn` - Notifikasi saat user check-in
    - `AttendanceCheckedOut` - Notifikasi saat user check-out
    - `GeneralAnnouncement` - Notifikasi pengumuman umum

4. **API Endpoints** (`routes/api.php`)
    ```
    GET    /api/notifications                    - Get all notifications
    GET    /api/notifications/unread-count       - Get unread count
    POST   /api/notifications/{id}/mark-as-read  - Mark as read
    POST   /api/notifications/mark-all-as-read   - Mark all as read
    DELETE /api/notifications/{id}               - Delete notification
    DELETE /api/notifications/read/all           - Delete all read
    ```

### Frontend Components

1. **NotificationBell.vue** (`resources/js/Components/NotificationBell.vue`)

    - Bell icon with unread badge
    - Dropdown panel with notifications list
    - Real-time updates via Laravel Echo
    - Mark as read/unread functionality
    - Click to navigate to detail page

2. **Layout Integration**

    - AdminLayout.vue
    - PesertaLayout.vue
    - AuthenticatedLayout.vue (optional)

3. **Laravel Echo Configuration** (`resources/js/bootstrap.js`)
    - Pusher client initialization
    - Private channel authentication
    - CSRF token handling

## 🚀 Installation & Setup

### 1. Backend Setup

#### Install Pusher PHP SDK

```bash
composer require pusher/pusher-php-server
```

#### Configure Environment (.env)

```env
BROADCAST_CONNECTION=pusher

# For local development with Laravel WebSockets
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1

# For production with Pusher Cloud
# PUSHER_APP_ID=your-app-id
# PUSHER_APP_KEY=your-app-key
# PUSHER_APP_SECRET=your-app-secret
# PUSHER_HOST=api-mt1.pusher.com
# PUSHER_PORT=443
# PUSHER_SCHEME=https
# PUSHER_APP_CLUSTER=mt1

# Vite Environment Variables
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

#### Activate BroadcastServiceProvider

File sudah aktif di `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
];
```

#### Run Migrations

```bash
php artisan migrate
```

### 2. Frontend Setup

#### Install Dependencies

```bash
npm install --save laravel-echo pusher-js
```

#### Build Assets

```bash
npm run build
# atau untuk development
npm run dev
```

### 3. WebSocket Server (Optional for Local Development)

Jika menggunakan Laravel WebSockets:

#### Install Laravel WebSockets

```bash
composer require beyondcode/laravel-websockets
php artisan websockets:install
php artisan migrate
```

#### Start WebSocket Server

```bash
php artisan websockets:serve
```

Dashboard: http://127.0.0.1:6001

## 📝 Usage Guide

### Sending Notifications from Controllers

#### 1. Application Verified Notification

```php
use App\Notifications\ApplicationVerified;

// Di Admin\ApplicationController
public function update(Request $request, Application $application)
{
    $application->update([
        'status' => $request->status,
        'admin_notes' => $request->admin_notes,
        'reviewed_at' => now(),
        'reviewed_by' => Auth::id()
    ]);

    // Send notification
    if ($request->status === 'diterima' || $request->status === 'ditolak') {
        $statusForNotification = $request->status === 'diterima' ? 'verified' : 'rejected';
        $application->user->notify(new ApplicationVerified($application, $statusForNotification));
    }

    return redirect()->back()->with('success', 'Status berhasil diupdate.');
}
```

#### 2. Attendance Notification

```php
use App\Notifications\AttendanceCheckedIn;
use App\Notifications\AttendanceCheckedOut;

// Di Peserta\AttendanceController
public function checkIn(Request $request)
{
    // ... validation & create attendance

    // Send notification
    $user->notify(new AttendanceCheckedIn($attendance));

    return redirect()->back()->with('success', 'Check-in berhasil!');
}

public function checkOut(Request $request)
{
    // ... validation & update attendance

    // Send notification
    $user->notify(new AttendanceCheckedOut($attendance));

    return redirect()->back()->with('success', 'Check-out berhasil!');
}
```

#### 3. General Announcement (Custom)

```php
use App\Notifications\GeneralAnnouncement;
use App\Models\User;

// Send to specific user
$user = User::find(1);
$user->notify(new GeneralAnnouncement(
    'Pengumuman Penting',
    'Libur tanggal 1 Oktober 2025',
    '/announcements/1',
    'megaphone',
    'indigo'
));

// Send to all users
$users = User::all();
foreach ($users as $user) {
    $user->notify(new GeneralAnnouncement(
        'Update Sistem',
        'Sistem akan maintenance pukul 22:00 WIB',
        null,
        'megaphone',
        'amber'
    ));
}
```

### Frontend Usage

NotificationBell component sudah terintegrasi di semua layout. Tidak perlu konfigurasi tambahan.

```vue
<!-- Sudah otomatis ada di layout -->
<NotificationBell v-if="auth.user" :userId="auth.user.id" />
```

### API Usage (Manual)

#### Get Notifications

```javascript
const response = await axios.get("/api/notifications?limit=20");
console.log(response.data.notifications);
console.log(response.data.unread_count);
```

#### Mark as Read

```javascript
await axios.post(`/api/notifications/${notificationId}/mark-as-read`);
```

#### Mark All as Read

```javascript
await axios.post("/api/notifications/mark-all-as-read");
```

#### Delete Notification

```javascript
await axios.delete(`/api/notifications/${notificationId}`);
```

## 🎨 Notification Types & Colors

| Type                     | Title              | Icon                    | Color       | Trigger Event        |
| ------------------------ | ------------------ | ----------------------- | ----------- | -------------------- |
| `application_verified`   | Status Pendaftaran | check-circle / x-circle | green / red | Admin approve/reject |
| `attendance_checked_in`  | Check-in Berhasil  | clock                   | emerald     | User check-in        |
| `attendance_checked_out` | Check-out Berhasil | clock                   | amber       | User check-out       |
| `general_announcement`   | Pengumuman         | megaphone               | indigo      | Admin announcement   |

## 🔧 Configuration

### Notification Data Structure

Setiap notification menyimpan data dalam format JSON di kolom `data`:

```json
{
    "type": "attendance_checked_in",
    "title": "Check-in Berhasil",
    "message": "Anda telah melakukan check-in pada 07:30",
    "attendance_id": 123,
    "check_in_time": "2025-09-30 07:30:00",
    "date": "2025-09-30",
    "status": "On-Time",
    "action_url": "/profile/attendance",
    "icon": "clock",
    "color": "emerald"
}
```

### Channel Authorization

Channels dikonfigurasi di `routes/channels.php`:

```php
// User-specific notification channel
Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```

## 🧪 Testing

### 1. Test Notification Storage

```php
// Artisan Tinker
php artisan tinker

$user = User::find(1);
$user->notify(new \App\Notifications\GeneralAnnouncement(
    'Test Notification',
    'This is a test message',
    null,
    'bell',
    'blue'
));

// Check database
DB::table('notifications')->where('notifiable_id', 1)->get();
```

### 2. Test Real-Time Broadcasting

1. Buka aplikasi di browser
2. Login sebagai user
3. Buka browser console (F12)
4. Lakukan action yang trigger notification (check-in, approve application, dll)
5. Check console log: "Received real-time notification: ..."
6. Notification bell badge should update automatically

### 3. Test API Endpoints

```bash
# Get notifications
curl -X GET http://localhost/api/notifications \
  -H "Accept: application/json" \
  -b "laravel_session=..."

# Mark as read
curl -X POST http://localhost/api/notifications/1/mark-as-read \
  -H "Accept: application/json" \
  -b "laravel_session=..."
```

## 🐛 Troubleshooting

### Issue: Notifications not received in real-time

**Solution:**

1. Check if WebSocket server is running (for local):
    ```bash
    php artisan websockets:serve
    ```
2. Check browser console for Echo connection errors
3. Verify `.env` configuration matches
4. Check `storage/logs/laravel.log` for broadcast errors

### Issue: Echo connection refused

**Solution:**

1. Verify PUSHER_HOST and PUSHER_PORT in `.env`
2. For local development, use `127.0.0.1` not `localhost`
3. Check firewall settings for port 6001
4. Verify WebSocket server is running

### Issue: 419 CSRF token mismatch on broadcasting auth

**Solution:**

```javascript
// Already configured in bootstrap.js
auth: {
    headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.content,
    }
}
```

### Issue: Notifications saved but not broadcasting

**Solution:**

1. Verify notification class implements `ShouldBroadcast`
2. Check `via()` method includes `'broadcast'`
3. Run queue worker if using queue:
    ```bash
    php artisan queue:work
    ```

## 📊 Database Schema

### Notifications Table

```sql
CREATE TABLE `notifications` (
  `id` char(36) PRIMARY KEY,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
);
```

## 🔐 Security

1. **Channel Authorization**: Only authenticated users can listen to their own notification channel
2. **API Authentication**: All notification API endpoints require authentication
3. **CSRF Protection**: Broadcasting auth uses CSRF token
4. **Private Channels**: User notifications use private channels with authorization

## 🚀 Production Deployment

### Using Pusher Cloud

1. Register at https://pusher.com
2. Create new app
3. Update `.env`:
    ```env
    PUSHER_APP_ID=your-app-id
    PUSHER_APP_KEY=your-app-key
    PUSHER_APP_SECRET=your-app-secret
    PUSHER_APP_CLUSTER=ap1  # or your cluster
    PUSHER_HOST=api-ap1.pusher.com
    PUSHER_PORT=443
    PUSHER_SCHEME=https
    ```
4. Update frontend environment:
    ```env
    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    ```
5. Build assets: `npm run build`

### Queue Configuration (Recommended for Production)

```env
QUEUE_CONNECTION=redis
```

```bash
# Run queue worker
php artisan queue:work --queue=default,notifications
```

## 📚 References

-   [Laravel Broadcasting Documentation](https://laravel.com/docs/11.x/broadcasting)
-   [Laravel Notifications Documentation](https://laravel.com/docs/11.x/notifications)
-   [Laravel Echo Documentation](https://laravel.com/docs/11.x/broadcasting#client-side-installation)
-   [Pusher Documentation](https://pusher.com/docs)
-   [Laravel WebSockets](https://beyondco.de/docs/laravel-websockets)

## 🎯 Future Enhancements

-   [ ] Email notifications untuk critical events
-   [ ] SMS notifications via Twilio
-   [ ] Notification preferences per user
-   [ ] Notification history page
-   [ ] Notification filtering by type
-   [ ] Push notifications untuk mobile app
-   [ ] Notification scheduling
-   [ ] Notification templates

---

**Built with ❤️ for GrowithBI Project**

_Last Updated: September 30, 2025_
