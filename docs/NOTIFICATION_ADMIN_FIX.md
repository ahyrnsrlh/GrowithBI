# 🔧 TROUBLESHOOTING: Notifikasi Admin Tidak Muncul

## ✅ Perbaikan yang Sudah Dilakukan

### 1. **Fix API Routes Authentication**

**Masalah**: API routes menggunakan `auth:sanctum` yang tidak kompatibel dengan session auth Inertia.js

**Solusi**:

```php
// routes/api.php - BEFORE
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {

// routes/api.php - AFTER
Route::middleware(['auth:sanctum,web'])->prefix('notifications')->group(function () {
```

**Penjelasan**: Menambahkan `web` guard agar API bisa diakses dengan session auth dari Inertia.

---

### 2. **Tambah Logging untuk Debugging**

**Masalah**: Tidak ada visibility kenapa notifikasi tidak muncul.

**Solusi**: Tambahkan console.log di NotificationBell.vue:

-   ✅ Log saat component mounted
-   ✅ Log saat fetch notifications
-   ✅ Log saat fetch unread count
-   ✅ Log error dengan detail (status, data, message)

---

### 3. **Admin Sudah Dapat Notifikasi dari Backend**

**Masalah**: Sebelumnya hanya user yang dapat notifikasi attendance.

**Solusi**: Di `AttendanceController.php` sudah ditambahkan:

```php
// Send notification to all admins
$admins = \App\Models\User::where('role', 'admin')->get();
foreach ($admins as $admin) {
    $admin->notify(new AttendanceNotification($attendance, $notificationType));
}
```

---

## 🔍 Cara Testing

### Step 1: Cek Console Browser

Buka dashboard admin → Inspect → Console, lihat log:

```
🔔 NotificationBell mounted for userId: X
🔢 Fetching unread count for userId: X
✅ Unread count fetched: Y
```

Jika ada error, akan muncul:

```
❌ Error fetching unread count: { status: 401, ... }
```

### Step 2: Test Manual Notification

```bash
php artisan tinker
```

```php
// Get admin
$admin = \App\Models\User::where('role', 'admin')->first();

// Check notifications
$admin->notifications()->count();
$admin->unreadNotifications()->count();

// Create test notification
$attendance = \App\Models\Attendance::latest()->first();
$admin->notify(new \App\Notifications\AttendanceNotification($attendance, 'checked_in'));

// Verify
$admin->unreadNotifications()->count(); // Should increase by 1
```

### Step 3: Test dari Peserta

1. Login sebagai peserta
2. Lakukan check-in
3. Login sebagai admin
4. Lihat notification bell → harus ada notifikasi baru

---

## 🐛 Kemungkinan Masalah Lain

### Problem 1: 401 Unauthorized

**Gejala**: Console menunjukkan error 401

**Penyebab**:

-   Session expired
-   User tidak authenticated
-   CSRF token invalid

**Solusi**:

1. Clear browser cookies
2. Logout dan login kembali
3. Hard refresh (Ctrl+Shift+R)

---

### Problem 2: CORS Issue

**Gejala**: Console menunjukkan CORS error

**Penyebab**: API request blocked by CORS policy

**Solusi**: Pastikan `config/cors.php` sudah benar:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'supports_credentials' => true,
```

---

### Problem 3: Database Kosong

**Gejala**: Unread count = 0 tapi harusnya ada

**Penyebab**: Notifikasi tidak tersimpan di database

**Solusi**: Cek di tinker:

```php
\App\Models\User::where('role', 'admin')->first()->notifications()->latest()->get();
```

Jika kosong, berarti backend tidak mengirim notifikasi ke admin.

---

### Problem 4: Echo Not Connected

**Gejala**: Dot kuning (polling mode) terus menerus

**Penyebab**:

-   Reverb server tidak jalan
-   WebSocket connection gagal
-   Environment variables salah

**Solusi**:

```bash
# Start Reverb
php artisan reverb:start

# Check .env
VITE_REVERB_APP_KEY=local-key
VITE_REVERB_HOST=127.0.0.1
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

---

## 📊 Checklist Debugging

-   [ ] Build assets berhasil: `npm run build`
-   [ ] API routes accessible: Test di browser `/api/notifications/unread-count`
-   [ ] Admin punya userId valid: Lihat di AdminLayout props
-   [ ] Console tidak ada error 401/403/500
-   [ ] Database punya notifikasi untuk admin: Cek via tinker
-   [ ] CSRF token valid: Lihat meta tag di HTML
-   [ ] Session auth bekerja: User bisa akses fitur lain
-   [ ] NotificationBell component mounted: Lihat console log
-   [ ] Axios config benar: withCredentials = true

---

## 🎯 Expected Behavior

### Saat Component Load:

```
Console:
🔔 NotificationBell mounted for userId: 1
🔢 Fetching unread count for userId: 1
✅ Unread count fetched: 5
```

### Saat User Check-in:

```
Backend:
- Attendance saved
- Notification sent to user
- Notification sent to all admins

Database:
- notifications table: New row for each admin
- type: AttendanceNotification
- data.type: attendance_checked_in

Frontend (Admin Dashboard):
- Unread badge: Count increases (+1)
- Real-time: Notification muncul langsung (jika WebSocket connected)
- Polling: Notification muncul dalam 30 detik (jika WebSocket failed)
```

### Saat Klik Bell:

```
Console:
🔍 Fetching notifications for userId: 1
✅ Notifications fetched: { notifications: [...], unread_count: 5 }

UI:
- Dropdown terbuka
- List notifikasi muncul
- Unread notification highlight dengan background biru
```

---

## 🚀 Next Steps After Fix

1. **Clear browser cache** dan **logout/login** kembali
2. **Test dengan scenario real**: Peserta check-in → Admin harus dapat notifikasi
3. **Monitor console logs** untuk error
4. **Verify database**: `notifications` table harus punya row untuk admin
5. **Test real-time**: Pastikan WebSocket connected (dot hijau)

---

## 📞 Still Not Working?

Jika masih tidak bekerja setelah semua perbaikan:

1. **Share console logs** (screenshot atau copy text)
2. **Check Laravel logs**: `storage/logs/laravel.log`
3. **Run test script**: `php test-admin-notification.php`
4. **Verify API response**:
    ```bash
    curl -X GET "http://localhost/api/notifications/unread-count" \
         -H "Cookie: laravel_session=YOUR_SESSION_COOKIE"
    ```

---

**Last Updated**: December 26, 2025  
**Status**: 🔧 FIXED - Ready for testing
