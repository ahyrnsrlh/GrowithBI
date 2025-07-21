# GrowithBI - Informasi Akun Login

## 🔐 Akun Demo untuk Testing

Berikut adalah akun demo yang tersedia untuk testing sistem manajemen magang GrowithBI:

### 👨‍💼 Admin

-   **Email**: `admin@growithbi.com`
-   **Password**: `password123`
-   **Role**: Administrator
-   **Akses**: Dashboard admin, manajemen pengguna, review aplikasi magang, laporan lengkap

### 👨‍🏫 Pembimbing (Supervisor)

1. **Dr. Sarah Wijaya**

    - **Email**: `sarah.wijaya@growithbi.com`
    - **Password**: `password123`
    - **Role**: Pembimbing
    - **Akses**: Dashboard pembimbing, monitoring peserta bimbingan, review logbook

2. **Ir. Budi Santoso, M.T.**
    - **Email**: `budi.santoso@growithbi.com`
    - **Password**: `password123`
    - **Role**: Pembimbing
    - **Akses**: Dashboard pembimbing, monitoring peserta bimbingan, review logbook

### 🎓 Peserta Magang (Intern)

1. **Ahmad Rizki Pratama**

    - **Email**: `ahmad.rizki@student.university.ac.id`
    - **Password**: `password123`
    - **Role**: Peserta
    - **Pembimbing**: Dr. Sarah Wijaya
    - **Akses**: Dashboard peserta, buat/edit logbook, lihat feedback

2. **Siti Nurhaliza**

    - **Email**: `siti.nurhaliza@student.university.ac.id`
    - **Password**: `password123`
    - **Role**: Peserta
    - **Pembimbing**: Dr. Sarah Wijaya
    - **Akses**: Dashboard peserta, buat/edit logbook, lihat feedback

3. **Muhammad Farhan**

    - **Email**: `muhammad.farhan@student.university.ac.id`
    - **Password**: `password123`
    - **Role**: Peserta
    - **Pembimbing**: Ir. Budi Santoso, M.T.
    - **Akses**: Dashboard peserta, buat/edit logbook, lihat feedback

4. **Dewi Lestari**
    - **Email**: `dewi.lestari@student.university.ac.id`
    - **Password**: `password123`
    - **Role**: Peserta
    - **Pembimbing**: Ir. Budi Santoso, M.T.
    - **Akses**: Dashboard peserta, buat/edit logbook, lihat feedback

## 🏢 Divisi Magang Tersedia

1. **Business Intelligence & Analytics** (Max: 5 peserta)
2. **Software Development** (Max: 8 peserta)
3. **Data Science & Machine Learning** (Max: 3 peserta)
4. **UI/UX Design** (Max: 4 peserta)
5. **Digital Marketing & Growth** (Max: 3 peserta)
6. **Quality Assurance & Testing** (Max: 4 peserta)

## 🌐 URL Akses

-   **Aplikasi**: http://localhost:8000
-   **Login**: http://localhost:8000/login
-   **Daftar Magang (Public)**: http://localhost:8000/apply
-   **Cek Status Pendaftaran**: http://localhost:8000/check-status

## 🔄 Role-based Routing

Setelah login, sistem akan otomatis mengarahkan ke dashboard sesuai role:

-   **Admin** → `/admin/dashboard`
-   **Pembimbing** → `/pembimbing/dashboard`
-   **Peserta** → `/peserta/dashboard`

## 🧪 Testing Flow

### Sebagai Admin:

1. Login dengan akun admin
2. Lihat dashboard dengan statistik lengkap
3. Kelola pengguna di menu Users
4. Review aplikasi magang di menu Applications

### Sebagai Pembimbing:

1. Login dengan akun pembimbing
2. Lihat dashboard dengan daftar peserta bimbingan
3. Review logbook peserta
4. Berikan feedback dan rating

### Sebagai Peserta:

1. Login dengan akun peserta
2. Lihat dashboard dengan statistik personal
3. Buat logbook harian baru
4. Lihat feedback dari pembimbing

### Testing Aplikasi Publik:

1. Akses `/apply` tanpa login
2. Isi form pendaftaran magang
3. Upload CV dan dokumen pendukung
4. Cek status di `/check-status`

## 🔧 Development Info

-   **Framework**: Laravel 11 + Inertia.js + Vue 3
-   **Database**: MySQL
-   **Authentication**: Laravel Breeze
-   **CSS**: Tailwind CSS
-   **Icons**: Heroicons

## 📝 Catatan

-   Semua password demo adalah `password123`
-   Database sudah terseeded dengan data demo
-   Sistem menggunakan role-based access control
-   File upload disimpan di `storage/app/public`

---

**🚀 Selamat mencoba sistem GrowithBI!**
