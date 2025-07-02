# Panduan Testing Sistem Autentikasi GrowithBI

## 🔐 Fitur Autentikasi yang Sudah Implementasi

### 1. **Role-based Registration & Login**
- ✅ Register dengan pilihan role (peserta/pembimbing)
- ✅ Login otomatis redirect berdasarkan role
- ✅ Session management yang aman
- ✅ Middleware protection untuk setiap route

### 2. **Dashboard Role-specific**
- ✅ Admin Dashboard: `/admin/dashboard`
- ✅ Pembimbing Dashboard: `/pembimbing/dashboard` 
- ✅ Peserta Dashboard: `/peserta/dashboard`

### 3. **Access Control**
- ✅ Route protection berdasarkan role
- ✅ Automatic redirect jika akses tidak sesuai role
- ✅ Logout dengan session cleanup

## 🧪 Cara Testing

### **Testing Registration:**
1. Buka `/register`
2. Isi form dengan:
   - Nama lengkap
   - Email unik
   - Password + konfirmasi
   - **Role: Pilih "Peserta Magang" atau "Pembimbing"**
   - Nomor telepon (opsional)
   - Alamat (opsional)
3. Submit form
4. Sistem akan otomatis login dan redirect ke dashboard sesuai role

### **Testing Login:**
1. Buka `/login`
2. Gunakan kredensial:
   - **Admin**: admin@growithbi.com / password123
   - **Pembimbing**: sarah.wijaya@growithbi.com / password123
   - **Peserta**: Buat akun baru atau gunakan existing
3. Login akan redirect otomatis ke dashboard sesuai role

### **Testing Role Protection:**
1. Login sebagai peserta
2. Coba akses `/admin/dashboard` → Akan error 403 Forbidden
3. Coba akses `/pembimbing/dashboard` → Akan error 403 Forbidden
4. Hanya bisa akses `/peserta/dashboard`

### **Testing Navigation:**
1. Setiap role memiliki navigation menu yang berbeda
2. Admin: Dashboard, Applications, Divisions, Supervisors, Participants, Reports
3. Pembimbing: Dashboard, Peserta, Logbook, Aplikasi
4. Peserta: Dashboard, Aplikasi, Logbook, Profile

## 🎯 User Accounts untuk Testing

```
// Admin Account
Email: admin@growithbi.com
Password: password123
Access: Full system management

// Pembimbing Account  
Email: sarah.wijaya@growithbi.com
Password: password123
Access: Participant & logbook management

// Pembimbing Account 2
Email: budi.santoso@growithbi.com  
Password: password123
Access: Participant & logbook management
```

## 🔄 Flow Testing Scenario

### **Scenario 1: New User Registration**
1. User buka `/register`
2. Pilih role "Peserta Magang"
3. Isi semua data yang diperlukan
4. Submit → Otomatis login → Redirect ke `/peserta/dashboard`

### **Scenario 2: Existing User Login**
1. User buka `/login`
2. Input credentials
3. Submit → Redirect berdasarkan role:
   - Admin → `/admin/dashboard`
   - Pembimbing → `/pembimbing/dashboard`  
   - Peserta → `/peserta/dashboard`

### **Scenario 3: Unauthorized Access**
1. Login sebagai peserta
2. Manually navigate ke `/admin/dashboard`
3. Expected: Error 403 atau redirect ke login

### **Scenario 4: Session Management**
1. Login dengan "Remember Me" checked
2. Close browser
3. Reopen → Still logged in
4. Click Logout → Session cleared, redirect ke home

## 🛠️ Technical Implementation

### **Middleware Stack:**
```php
// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])

// Pembimbing routes  
Route::middleware(['auth', 'verified', 'role:pembimbing'])

// Peserta routes
Route::middleware(['auth', 'verified', 'role:peserta'])
```

### **Controllers:**
- `RegisteredUserController`: Handle registration with role
- `AuthenticatedSessionController`: Handle login with role redirect
- `Admin\DashboardController`: Admin dashboard
- `Pembimbing\DashboardController`: Pembimbing dashboard
- `Peserta\DashboardController`: Peserta dashboard

### **Layouts:**
- `AdminLayout.vue`: Admin interface
- `PembimbingLayout.vue`: Pembimbing interface  
- `PesertaLayout.vue`: Peserta interface
- `GuestLayout.vue`: Login/Register interface

## ⚡ Quick Commands

```bash
# Run application
php artisan serve

# Check routes
php artisan route:list

# Reset database & seed
php artisan migrate:fresh --seed

# Build assets
npm run build
```

## 🚀 Next Steps

1. **Email Verification**: Implementasi email verification untuk security tambahan
2. **Password Reset**: Forgot password functionality  
3. **Advanced Role Management**: Role permissions yang lebih granular
4. **Profile Management**: Enhanced user profile editing
5. **Account Settings**: User preferences dan settings

---

**Status: ✅ Authentication System Complete & Ready for Testing**
