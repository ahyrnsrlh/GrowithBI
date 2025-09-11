# Aturan Pendaftaran Aplikasi

## Overview
Sistem telah diperbarui agar setiap user hanya bisa mendaftar di 1 lowongan pada satu waktu. User baru bisa mendaftar ke lowongan lain setelah proses pendaftaran pertama selesai (diterima/ditolak).

## Aturan Bisnis

### 1. Status yang Menghalangi Pendaftaran Baru
User **TIDAK BISA** mendaftar ke divisi lain jika memiliki aplikasi dengan status:
- `menunggu` - Aplikasi sedang dalam proses review
- `dalam_review` - Aplikasi sedang direview oleh tim
- `wawancara` - User sedang dalam tahap wawancara
- `diterima` - User sudah diterima (tidak bisa apply lagi)

### 2. Status yang Membolehkan Pendaftaran Baru
User **BISA** mendaftar lagi jika aplikasi sebelumnya memiliki status:
- `ditolak` - Aplikasi sebelumnya ditolak
- Tidak ada aplikasi sebelumnya

## Implementasi Teknis

### 1. Custom Validation Rule
```php
App\Rules\UniqueActiveApplication
```
- Mengecek apakah user memiliki aplikasi aktif
- Mendukung validasi berdasarkan email atau user_id
- Memberikan pesan error yang informatif

### 2. Controller Updates
**PublicController:**
- `storeApplication()` - Form pendaftaran publik
- `quickRegister()` - Quick registration untuk user login
- `applicationForm()` - Menampilkan status aplikasi existing

**ProfileController:**
- `createApplication()` - Pendaftaran dari halaman profile
- `edit()` - Menampilkan status aplikasi aktif

### 3. Database Constraints
- Index `idx_email_status` untuk optimasi query
- Cleanup otomatis aplikasi duplikat

### 4. Frontend Integration
Controllers menyediakan data untuk frontend:
- `existingApplication` - Info aplikasi yang sedang aktif
- `canCreateNewApplication` - Boolean apakah bisa mendaftar
- `activeApplication` - Detail aplikasi aktif

## Testing

### Test Data (ApplicationTestSeeder)
1. **John Doe** - Status: `menunggu` (tidak bisa apply)
2. **Jane Smith** - Status: `diterima` (tidak bisa apply)
3. **Bob Wilson** - Status: `ditolak` (bisa apply)

### Test Command
```bash
php artisan test:application-rules
```

## Error Messages

### User dengan Aplikasi Pending
```
Anda sudah memiliki pendaftaran yang sedang diproses di divisi [Nama Divisi]. 
Tunggu hasil proses tersebut sebelum mendaftar ke divisi lain.
```

### User dengan Aplikasi Diterima
```
Anda sudah diterima di divisi [Nama Divisi]. 
Anda tidak bisa mendaftar ke divisi lain.
```

## Migrasi Database

```php
2025_08_28_093053_add_unique_constraint_for_active_applications.php
```
- Menghapus aplikasi duplikat existing
- Menambah index untuk optimasi performance

## Status Flow

```
[Tidak Ada Aplikasi] → [Mendaftar] → [menunggu]
                                        ↓
[menunggu] → [dalam_review] → [wawancara] → [diterima/ditolak]
                                               ↓
[ditolak] → [Bisa Mendaftar Lagi]
[diterima] → [Tidak Bisa Mendaftar Lagi]
```

## Penggunaan

### Frontend
```javascript
// Cek apakah user bisa mendaftar
if (canCreateNewApplication) {
    // Tampilkan form pendaftaran
} else {
    // Tampilkan pesan status aplikasi existing
}
```

### Backend Validation
```php
$request->validate([
    'division_id' => [
        'required',
        'exists:divisions,id',
        new UniqueActiveApplication(null, $user->id)
    ]
]);
```

## Monitoring

### Cek Status Aplikasi User
```sql
SELECT u.name, u.email, a.status, d.name as division 
FROM users u 
JOIN applications a ON u.id = a.user_id 
JOIN divisions d ON a.division_id = d.id 
WHERE a.status IN ('menunggu', 'dalam_review', 'wawancara', 'diterima')
ORDER BY a.created_at DESC;
```

### Cek Aplikasi Duplikat
```sql
SELECT email, COUNT(*) as count 
FROM applications 
WHERE status IN ('menunggu', 'dalam_review', 'wawancara', 'diterima')
GROUP BY email 
HAVING count > 1;
```
