# 🔔 Use Case Notifikasi Real-Time

Berikut saya berikan **use case notifikasi real-time** yang relevan untuk sistem manajemen magang (Admin, Pembimbing, dan Peserta):

---

## 📋 **4 KATEGORI NOTIFIKASI**

### **1. Status Pendaftaran & Seleksi** 📝

#### **Untuk Peserta:**

-   ✅ Pendaftaran berhasil dikirim
-   📧 Perubahan status seleksi (menunggu, wawancara, diterima, ditolak)
-   📅 Penjadwalan atau perubahan jadwal wawancara
-   ✉️ Surat penerimaan tersedia untuk diunduh

#### **Untuk Admin:**

-   🆕 Pendaftaran baru masuk
-   📝 User melengkapi dokumen pendaftaran

**Channel**: Database + Broadcast  
**Notification Class**: `RegistrationStatusNotification`

**Event Types:**

-   `application_submit` - User submit pendaftaran
-   `selection_in_progress` - Proses seleksi dimulai
-   `interview_scheduled` - Jadwal wawancara
-   `accepted` - Diterima
-   `rejected` - Ditolak
-   `acceptance_letter_ready` - Surat penerimaan siap

---

### **2. Absensi & Kehadiran** ✅

#### **Untuk Peserta:**

-   ✅ Check-in berhasil (tepat waktu/terlambat)
-   ✅ Check-out berhasil
-   ❌ Check-in gagal (lokasi tidak valid)
-   ❌ Face recognition gagal

#### **Untuk Admin:**

-   👤 User check-in (On-Time)
-   ⏰ User check-in TERLAMBAT
-   ✅ User check-out
-   🚨 Check-in gagal (lokasi/face recognition)
-   ⚠️ User belum check-in/check-out

**Channel**: Database + Broadcast (**TANPA EMAIL**)  
**Notification Class**: `AttendanceNotification`

**Event Types:**

-   `checked_in` - Check-in berhasil (On-Time)
-   `late` - Check-in terlambat
-   `checked_out` - Check-out berhasil
-   `location_invalid` - Lokasi di luar radius
-   `face_not_recognized` - Face recognition gagal
-   `face_registered` - Face descriptor berhasil didaftarkan
-   `missing_checkin` - Belum check-in (reminder)
-   `missing_checkout` - Belum check-out (reminder)

---

### **3. Logbook & Aktivitas Harian** 📔

#### **Untuk Peserta:**

-   ✅ Logbook berhasil dikirim
-   ✅ Logbook disetujui pembimbing
-   ❌ Logbook ditolak pembimbing
-   📝 Permintaan revisi logbook

#### **Untuk Admin/Pembimbing:**

-   📔 Logbook baru submitted
-   ⏰ Logbook pending review >3 hari
-   ⚠️ User belum submit logbook hari ini

**Channel**: Database + Broadcast  
**Notification Class**: `LogbookNotification`

**Event Types:**

-   `submitted` - Logbook baru disubmit
-   `approved` - Logbook disetujui
-   `rejected` - Logbook ditolak
-   `revision_requested` - Perlu revisi
-   `commented` - Ada komentar baru
-   `pending_overdue` - Pending review > 3 hari (scheduler)
-   `not_submitted_today` - Belum submit hari ini (scheduler)

---

### **4. Laporan Akhir** 📄

#### **Untuk Peserta:**

-   ✅ Laporan akhir berhasil diunggah
-   ✅ Laporan disetujui
-   ❌ Laporan perlu revisi
-   ⏰ Tenggat pengumpulan mendekat (3 hari)

#### **Untuk Admin:**

-   📄 Laporan akhir baru submitted
-   ⏰ Deadline laporan 3 hari lagi
-   🚨 User OVERDUE laporan akhir
-   ✅ Laporan direvisi oleh user

**Channel**: Database + Broadcast  
**Notification Class**: `ReportNotification`

**Event Types:**

-   `submitted` - Laporan baru diupload
-   `under_review` - Sedang direview
-   `approved` - Laporan disetujui
-   `revision_required` - Perlu revisi
-   `deadline_approaching` - Deadline 3 hari lagi (scheduler)
-   `overdue` - Laporan melewati deadline (scheduler)
-   `resubmitted` - Laporan direvisi ulang

---

## 📊 **Summary Matrix**

| Kategori                  | Peserta | Admin | Email | Real-Time |
| ------------------------- | ------- | ----- | ----- | --------- |
| **Pendaftaran & Seleksi** | ✅      | ✅    | ❌    | ✅        |
| **Absensi & Kehadiran**   | ✅      | ✅    | ❌    | ✅        |
| **Logbook & Aktivitas**   | ✅      | ✅    | ❌    | ✅        |
| **Laporan Akhir**         | ✅      | ✅    | ❌    | ✅        |

> **Note**: Semua notifikasi hanya menggunakan **database + broadcast** (real-time). Tidak ada email notifications.

---

## 🔧 **Implementasi Saat Ini**

### ✅ **Sudah Diimplementasikan:**

1. ✅ 4 Notification Classes (RegistrationStatusNotification, AttendanceNotification, LogbookNotification, ReportNotification)
2. ✅ NotificationController dengan 7 methods
3. ✅ NotificationBell component dengan real-time updates
4. ✅ Fallback polling jika WebSocket gagal
5. ✅ Connection status indicator
6. ✅ Environment validation
7. ✅ Admin dan Peserta menerima notifikasi attendance
8. ✅ Notifikasi `location_invalid` dan `face_not_recognized` saat absensi gagal
9. ✅ Notifikasi `face_registered` saat user daftarkan wajah pertama kali
10. ✅ Notifikasi `commented` saat admin/mentor comment di logbook
11. ✅ Notifikasi `documents_completed` ke admin saat user lengkapi semua dokumen
12. ✅ Scheduled Commands untuk reminder notifications

### ✅ **Status Implementasi per Kategori:**

#### **1. Pendaftaran & Seleksi** ✅

| Event Type              | Trigger                       | Status |
| ----------------------- | ----------------------------- | ------ |
| `application_submitted` | User submit form              | ✅     |
| `documents_completed`   | User upload semua dokumen     | ✅     |
| `accepted`              | Admin approve aplikasi        | ✅     |
| `rejected`              | Admin reject aplikasi         | ✅     |
| `letter_ready`          | Admin upload surat penerimaan | ✅     |

#### **2. Absensi & Kehadiran** ✅

| Event Type            | Trigger                 | Status |
| --------------------- | ----------------------- | ------ |
| `checked_in`          | User check-in sukses    | ✅     |
| `late`                | User check-in terlambat | ✅     |
| `checked_out`         | User check-out sukses   | ✅     |
| `location_invalid`    | User di luar radius     | ✅     |
| `face_not_recognized` | Verifikasi wajah gagal  | ✅     |
| `face_registered`     | User daftarkan wajah    | ✅     |

#### **3. Logbook & Aktivitas** ✅

| Event Type            | Trigger                          | Status |
| --------------------- | -------------------------------- | ------ |
| `submitted`           | User submit logbook              | ✅     |
| `approved`            | Admin approve logbook            | ✅     |
| `rejected`            | Admin reject logbook             | ✅     |
| `revision_requested`  | Admin minta revisi               | ✅     |
| `commented`           | Admin tambah komentar            | ✅     |
| `pending_overdue`     | Scheduler: pending > 3 hari      | ✅     |
| `not_submitted_today` | Scheduler: belum submit hari ini | ✅     |

#### **4. Laporan Akhir** ✅

| Event Type             | Trigger                         | Status |
| ---------------------- | ------------------------------- | ------ |
| `submitted`            | User upload laporan             | ✅     |
| `under_review`         | Admin mulai review              | ✅     |
| `approved`             | Admin approve laporan           | ✅     |
| `revision_required`    | Admin minta revisi              | ✅     |
| `resubmitted`          | User upload revisi              | ✅     |
| `deadline_approaching` | Scheduler: deadline 3 hari lagi | ✅     |
| `overdue`              | Scheduler: melewati deadline    | ✅     |

---

## ⏰ **Scheduled Commands**

### Logbook Reminders

```bash
# Run daily at 16:00
php artisan notifications:logbook-reminders

# Options:
--type=all|pending_overdue|not_submitted_today
--dry-run  # Test tanpa kirim notifikasi
```

### Report Reminders

```bash
# Run daily at 09:00
php artisan notifications:report-reminders

# Options:
--type=all|deadline_approaching|overdue
--days=3   # Days before deadline
--dry-run  # Test tanpa kirim notifikasi
```

### Cron Setup (Production)

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Local Development

```bash
php artisan schedule:work
```

---

## � **Development Setup (Real-time)**

Untuk real-time notifications, jalankan **3 server** di terminal terpisah:

```bash
# Terminal 1: Laravel Dev Server (atau gunakan Laragon)
php artisan serve

# Terminal 2: Reverb WebSocket Server (WAJIB untuk real-time)
php artisan reverb:start

# Terminal 3: Queue Worker (WAJIB untuk broadcast)
php artisan queue:work
```

**Tanpa queue worker**, broadcast notifications tidak akan terkirim ke WebSocket!

---

## 💡 **Catatan Penting**

> **NO EMAIL NOTIFICATIONS**: Semua notifikasi menggunakan **database + broadcast** saja untuk real-time updates di web UI.

> **Database Synchronous**: Notifikasi database disimpan secara **sinkron** (langsung tersimpan), broadcast di-queue.

> **Broadcast via Queue**: Broadcast notifications di-queue dan butuh `php artisan queue:work` untuk diproses.

> **Real-time via WebSocket**: Menggunakan Laravel Reverb dengan **fallback polling** (30 detik) jika WebSocket gagal.

> **Scheduler Required**: Untuk reminder notifications (logbook not submitted, report deadline), scheduler harus berjalan via cron atau `schedule:work`.

---

**Last Updated**: January 8, 2026  
**Status**: ✅ **FULLY IMPLEMENTED** - Semua 4 kategori notifikasi berjalan dengan baik (NO EMAIL, database sync)
