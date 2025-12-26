# GrowithBI Documentation

Dokumentasi lengkap untuk sistem GrowithBI - Aplikasi Manajemen Magang Bank Indonesia.

---

## 📋 Daftar Dokumentasi

### ⚡ Quick Start & Troubleshooting
- **[QUICK_FIX_NOTIFICATION.md](QUICK_FIX_NOTIFICATION.md)** - Quick fix notifikasi tidak muncul ⚡ NEW!
- **[TROUBLESHOOTING_NOTIFICATION.md](TROUBLESHOOTING_NOTIFICATION.md)** - Troubleshooting lengkap notifikasi NEW!
- **[QUICK_REFERENCE_NOTIFICATION.md](QUICK_REFERENCE_NOTIFICATION.md)** - Quick guide untuk testing notifikasi

### Fitur Email Notification
- **[EMAIL_NOTIFICATION_QUICK_START.md](EMAIL_NOTIFICATION_QUICK_START.md)** - Quick start guide email notification
- **[EMAIL_NOTIFICATION_FEATURE.md](EMAIL_NOTIFICATION_FEATURE.md)** - Dokumentasi lengkap email notification

### Fitur Notifikasi Real-Time
- **[SUMMARY_NOTIFICATION_FIX.md](SUMMARY_NOTIFICATION_FIX.md)** - Summary lengkap fix notifikasi approval/rejection
- **[FIX_NOTIFICATION_APPLICATION.md](FIX_NOTIFICATION_APPLICATION.md)** - Detail fix notifikasi tidak muncul
- **[NOTIFICATION_REALTIME_COMPLETE.md](NOTIFICATION_REALTIME_COMPLETE.md)** - Guide lengkap notifikasi real-time dengan Reverb
- **[NOTIFICATION_FIX.md](NOTIFICATION_FIX.md)** - Fix error 401 Unauthorized pada API notifikasi
- **[NOTIFICATION_FEATURE.md](NOTIFICATION_FEATURE.md)** - Dokumentasi fitur notifikasi
- **[FIX_NOTIFICATION_DROPDOWN_TOGGLE.md](FIX_NOTIFICATION_DROPDOWN_TOGGLE.md)** - Fix dropdown notifikasi toggle behavior

### Fitur Lainnya
- **[APPLICATION_RULES.md](APPLICATION_RULES.md)** - Aturan dan validasi aplikasi magang
- **[LOGBOOK_DETAIL_REDESIGN.md](LOGBOOK_DETAIL_REDESIGN.md)** - Redesign halaman detail logbook
- **[MAPS_REALTIME.md](MAPS_REALTIME.md)** - Fitur peta real-time untuk tracking
- **[MINIMALIST_REDESIGN.md](MINIMALIST_REDESIGN.md)** - Redesign UI minimalist
- **[REDESIGN_SUCCESS.md](REDESIGN_SUCCESS.md)** - Success stories dari redesign
- **[TROUBLESHOOTING_REDESIGN.md](TROUBLESHOOTING_REDESIGN.md)** - Troubleshooting untuk redesign UI

---

## 🚀 Quick Start

### Setup Development Environment

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Run migrations
php artisan migrate --seed

# 4. Start services
php artisan reverb:start    # Terminal 1
php artisan serve            # Terminal 2  
php artisan queue:work       # Terminal 3
npm run dev                  # Terminal 4
```

### Testing Notifikasi Real-Time

```bash
# Quick test
php tests/test-notification.php

# Manual test
1. Login sebagai admin
2. Approve/reject aplikasi peserta
3. Login sebagai peserta di tab lain
4. Lihat notifikasi muncul real-time! ✨
```

---

## 📁 Struktur Dokumentasi

```
docs/
├── README.md                              # File ini
├── QUICK_REFERENCE_NOTIFICATION.md        # Quick reference notifikasi
├── SUMMARY_NOTIFICATION_FIX.md            # Summary fix notifikasi
├── FIX_NOTIFICATION_APPLICATION.md        # Detail fix approval/rejection
├── NOTIFICATION_REALTIME_COMPLETE.md      # Guide lengkap real-time
├── NOTIFICATION_FIX.md                    # Fix 401 error
├── NOTIFICATION_FEATURE.md                # Fitur notifikasi
├── APPLICATION_RULES.md                   # Aturan aplikasi
├── LOGBOOK_DETAIL_REDESIGN.md             # Redesign logbook
├── MAPS_REALTIME.md                       # Fitur maps
├── MINIMALIST_REDESIGN.md                 # UI redesign
├── REDESIGN_SUCCESS.md                    # Success stories
└── TROUBLESHOOTING_REDESIGN.md            # Troubleshooting
```

---

## 🔧 Troubleshooting Umum

### Notifikasi Tidak Muncul
→ Lihat: [FIX_NOTIFICATION_APPLICATION.md](FIX_NOTIFICATION_APPLICATION.md)

### Error 401 Unauthorized
→ Lihat: [NOTIFICATION_FIX.md](NOTIFICATION_FIX.md)

### WebSocket Connection Failed
```bash
# Check Reverb server
Get-NetTCPConnection -LocalPort 8080

# Restart Reverb
php artisan reverb:start
```

### Queue Jobs Tidak Terproses
```bash
# Start queue worker
php artisan queue:work

# Check failed jobs
php artisan queue:failed
```

---

## 🎯 Fitur Utama

### 1. Real-Time Notifications
- ✅ WebSocket dengan Laravel Reverb
- ✅ Broadcast events untuk update otomatis
- ✅ Badge unread count
- ✅ Browser notifications
- ✅ Notification history

### 2. Application Management
- ✅ Submit aplikasi magang
- ✅ Review dan approval workflow
- ✅ Interview scheduling
- ✅ Acceptance letter upload
- ✅ Status tracking

### 3. Logbook System
- ✅ Daily activity logging
- ✅ Mentor feedback
- ✅ Progress tracking
- ✅ Status workflow (draft → submitted → reviewed)
- ✅ Comments dan revisi

### 4. Attendance Tracking
- ✅ Check-in/check-out dengan GPS
- ✅ Real-time location tracking
- ✅ Maps integration
- ✅ Attendance reports
- ✅ Excel export

### 5. Report Management
- ✅ Periodic reports
- ✅ Review workflow
- ✅ File attachments
- ✅ Feedback system

---

## 📚 Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3 + Inertia.js
- **Styling**: Tailwind CSS
- **Broadcasting**: Laravel Reverb (WebSocket)
- **Authentication**: Laravel Sanctum
- **Database**: SQLite (dev), MySQL (prod)
- **Queue**: Database queue driver
- **Real-time**: Laravel Echo + Pusher JS

---

## 🤝 Contributing

Saat menambahkan fitur baru:

1. **Update dokumentasi** di folder `docs/`
2. **Buat test script** jika memungkinkan
3. **Update README** dengan fitur baru
4. **Commit dengan pesan jelas**

---

## 📝 Changelog

### 2025-10-11
- ✅ Fix notifikasi approval/rejection aplikasi
- ✅ Setup Laravel Sanctum untuk API auth
- ✅ Fix broadcast channel naming
- ✅ Tambah test script untuk notifikasi
- ✅ Dokumentasi lengkap notifikasi real-time

### Previous Changes
- Lihat dokumentasi individual untuk history lengkap

---

## 📧 Support

Jika menemukan bug atau butuh bantuan:

1. Check dokumentasi terkait di folder `docs/`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Check browser console untuk frontend errors
4. Check Reverb logs saat start dengan `--debug`

---

**Happy Coding! 🚀**
