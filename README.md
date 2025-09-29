# 🚀 GrowithBI - Sistem Manajemen Magang Terintegrasi

<div align="center">

![GrowithBI Logo](public/logo.png)

**Platform digital komprehensif untuk mengelola program magang dari pendaftaran hingga pelaporan**

[![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.4-green.svg)](https://vuejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.2-blue.svg)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

</div>

---

## 📋 Daftar Isi

1. [Tentang Proyek](#-tentang-proyek)
2. [Fitur Utama](#-fitur-utama)
3. [Teknologi Stack](#-teknologi-stack)
4. [Instalasi](#-instalasi)
5. [Penggunaan](#-penggunaan)
6. [Struktur Database](#-struktur-database)
7. [API Documentation](#-api-documentation)
8. [Demo & Testing](#-demo--testing)
9. [Roadmap](#-roadmap)
10. [Contributing](#-contributing)
11. [License](#-license)

---

## 🎯 Tentang Proyek

**GrowithBI** adalah sistem manajemen magang modern yang dikembangkan untuk memfasilitasi seluruh siklus program magang, dari pendaftaran peserta hingga monitoring dan pelaporan. Sistem ini menggabungkan kemudahan penggunaan dengan fitur-fitur canggih untuk memenuhi kebutuhan organisasi modern.

### ✨ Mengapa GrowithBI?

-   **🔄 Otomatisasi Penuh**: Proses pendaftaran hingga evaluasi
-   **📊 Analytics Real-time**: Dashboard komprehensif untuk monitoring
-   **🎯 Role-based Access**: Akses yang disesuaikan untuk setiap peran
-   **📱 Responsive Design**: Optimal di semua perangkat
-   **🔒 Security First**: Implementasi keamanan tingkat enterprise

---

## ⭐ Fitur Utama

### 👥 Multi-Role Management

-   **Admin**: Kontrol penuh sistem, analytics, dan manajemen user
-   **Pembimbing**: Supervisi peserta dan evaluasi logbook
-   **Peserta**: Pendaftaran, tracking progress, dan pelaporan

### 🛠️ Manajemen Administrasi

-   **📝 Pendaftaran Otomatis**: Sistem pendaftaran dengan validasi terintegrasi
-   **📊 Dashboard Analytics**: Visualisasi data real-time dengan Chart.js
-   **🏢 Manajemen Divisi**: CRUD lengkap dengan kuota dan supervisor
-   **📈 Reporting System**: Export data ke Excel dengan Maatwebsite Excel
-   **📋 Status Tracking**: Monitoring status aplikasi real-time

### 🎯 Fitur Canggih

-   **🔍 Advanced Search**: Pencarian dan filtering yang powerful
-   **📄 PDF Generation**: Generate dokumen dengan DomPDF
-   **📧 Email Notifications**: Sistem notifikasi otomatis
-   **📱 API Integration**: RESTful API untuk integrasi external
-   **🔐 Authentication**: Laravel Breeze dengan role-based access

---

## 🛠️ Teknologi Stack

### Backend

-   **Framework**: Laravel 12.0
-   **PHP Version**: 8.2+
-   **Database**: MySQL/SQLite
-   **Authentication**: Laravel Breeze + Sanctum
-   **PDF Generation**: DomPDF
-   **Excel Export**: Maatwebsite Excel

### Frontend

-   **Framework**: Vue.js 3.4
-   **Styling**: Tailwind CSS 3.2
-   **Icons**: Heroicons Vue
-   **Charts**: Chart.js + Vue-ChartJS
-   **Animations**: AOS (Animate On Scroll)
-   **Build Tool**: Vite 6.2

### Development Tools

-   **Package Manager**: Composer + NPM
-   **Testing**: PHPUnit
-   **Code Quality**: Laravel Pint
-   **Dev Server**: Laravel Sail / Laragon
-   **Version Control**: Git

---

## 📦 Instalasi

### Prerequisites

Pastikan sistem Anda memiliki:

-   PHP 8.2 atau lebih tinggi
-   Composer
-   Node.js 18+ & NPM
-   MySQL 8.0+ atau SQLite
-   Git

### Langkah Instalasi

1. **Clone Repository**

```bash
git clone https://github.com/ahyrnsrlh/GrowithBI.git
cd GrowithBI
```

2. **Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

3. **Environment Setup**

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

4. **Database Configuration**
   Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growithbi
DB_USERNAME=root
DB_PASSWORD=
```

5. **Database Migration & Seeding**

```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

6. **Build Assets**

```bash
# Development
npm run dev

# Production
npm run build
```

7. **Start Development Server**

```bash
# Laravel development server
php artisan serve

# Atau dengan Laragon/XAMPP
# Akses melalui http://localhost/GrowithBI/public
```

### Docker Installation (Alternative)

```bash
# Using Laravel Sail
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev
```

---

## 🎮 Penggunaan

### Akses Aplikasi

-   **URL Utama**: `http://localhost:8000`
-   **Admin Panel**: `http://localhost:8000/admin/dashboard`
-   **API Endpoint**: `http://localhost:8000/api/v1`

### Default Credentials

```
Admin:
Email: admin@growithbi.com
Password: password123

Pembimbing:
Email: sarah.wijaya@growithbi.com
Password: password123

Peserta:
Email: john.doe@example.com
Password: password123
```

### Navigation Flow

1. **Public Access**: Lihat divisi yang tersedia dan status pendaftaran
2. **Registration**: Daftar sebagai peserta/pembimbing
3. **Login**: Akses dashboard sesuai role
4. **Admin**: Kelola seluruh sistem
5. **Pembimbing**: Supervisi dan evaluasi peserta
6. **Peserta**: Submit aplikasi dan logbook

---

## 🗄️ Struktur Database

### Core Tables

| Table          | Description                | Key Fields                                   |
| -------------- | -------------------------- | -------------------------------------------- |
| `users`        | Multi-role user management | `id`, `name`, `email`, `role`, `division_id` |
| `divisions`    | Department/division data   | `id`, `name`, `quota`, `is_active`           |
| `applications` | Internship applications    | `id`, `user_id`, `division_id`, `status`     |
| `logbooks`     | Daily activity logging     | `id`, `user_id`, `date`, `activities`        |
| `reports`      | Final reports              | `id`, `user_id`, `title`, `file_path`        |

### Key Relationships

-   **Users** ↔ **Divisions**: Many-to-One (users belong to division)
-   **Users** ↔ **Applications**: One-to-Many (user can have multiple applications)
-   **Users** ↔ **Logbooks**: One-to-Many (user has many logbook entries)
-   **Applications** ↔ **Divisions**: Many-to-One (application for specific division)

### Database Features

-   ✅ **Foreign Key Constraints**: Data integrity
-   ✅ **Indexes**: Optimized performance
-   ✅ **Soft Deletes**: Safe data removal
-   ✅ **Timestamps**: Audit trail
-   ✅ **Validation Rules**: Data consistency

---

## 🔌 API Documentation

### Authentication Endpoints

```http
POST /api/login              # User login
POST /api/register           # User registration
POST /api/logout             # User logout
GET  /api/user               # Get current user
```

### Applications API

```http
GET    /api/applications                 # List applications
POST   /api/applications                 # Create application
GET    /api/applications/{id}            # Get application
PUT    /api/applications/{id}            # Update application
DELETE /api/applications/{id}            # Delete application
GET    /api/applications/check/{division} # Check existing application
```

### Divisions API

```http
GET    /api/divisions          # List divisions
GET    /api/divisions/{id}     # Get division details
```

### Response Format

```json
{
    "success": true,
    "data": {
        // Response data
    },
    "message": "Success message",
    "meta": {
        "current_page": 1,
        "total": 100
    }
}
```

---

## 🎯 Demo & Testing

### Live Demo

-   **Demo URL**: [https://growithbi-demo.com](https://growithbi-demo.com)
-   **Admin Demo**: Login dengan kredensial admin di atas
-   **API Testing**: Gunakan Postman collection yang disediakan

### Test Data

Sistem menyediakan data sample untuk testing:

-   **5 Admin users** dengan berbagai role
-   **15 Pembimbing** dari berbagai divisi
-   **50 Peserta** dengan status aplikasi bervariasi
-   **8 Divisi** dengan kuota dan requirement berbeda
-   **100+ Sample applications** untuk testing analytics

### Unit Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Generate test coverage
php artisan test --coverage
```

---

## 🚧 Roadmap

### ✅ Phase 1: Core System (COMPLETED)

-   [x] Authentication & Role Management
-   [x] Admin Dashboard & Analytics
-   [x] Division Management
-   [x] Application Management
-   [x] User Management
-   [x] Basic Reporting

### 🔄 Phase 2: Enhanced Features (IN PROGRESS)

-   [x] Advanced Analytics Dashboard
-   [x] Excel Export Functionality
-   [ ] Email Notification System
-   [ ] Document Upload & Management
-   [ ] Advanced Search & Filtering
-   [ ] Mobile App API

### 🔮 Phase 3: Advanced Features (PLANNED)

-   [ ] Real-time Notifications
-   [ ] Advanced Reporting & BI
-   [ ] Integration with External Systems
-   [ ] Mobile Application
-   [ ] AI-powered Recommendations
-   [ ] Multi-tenant Architecture

### 📈 Future Enhancements

-   [ ] **Machine Learning**: Prediksi keberhasilan aplikasi
-   [ ] **Blockchain**: Certificate verification
-   [ ] **IoT Integration**: Attendance tracking
-   [ ] **Chatbot**: Automated support
-   [ ] **Video Conferencing**: Built-in meeting system

---

## 🤝 Contributing

Kami menyambut kontribusi dari komunitas! Berikut cara untuk berkontribusi:

### Getting Started

1. Fork repository ini
2. Buat branch fitur: `git checkout -b feature/AmazingFeature`
3. Commit perubahan: `git commit -m 'Add AmazingFeature'`
4. Push ke branch: `git push origin feature/AmazingFeature`
5. Buat Pull Request

### Development Guidelines

-   **Code Style**: Ikuti PSR-12 dan ESLint configuration
-   **Testing**: Pastikan semua test passing
-   **Documentation**: Update dokumentasi untuk fitur baru
-   **Commit Message**: Gunakan conventional commits

### Code Quality

```bash
# Laravel Pint (PHP)
./vendor/bin/pint

# ESLint (JavaScript)
npm run lint

# PHPStan (Static Analysis)
./vendor/bin/phpstan analyse
```

---

## 🚀 Deployment

### Production Setup

1. **Server Requirements**

    - PHP 8.2+ dengan ekstensi yang dibutuhkan
    - MySQL 8.0+
    - Nginx/Apache
    - Composer
    - Node.js

2. **Environment Configuration**

    ```env
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://yourdomain.com
    ```

3. **Optimization Commands**
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    npm run build
    ```

### Docker Deployment

```dockerfile
# Dockerfile tersedia untuk deployment containerized
docker-compose -f docker-compose.prod.yml up -d
```

---

## 📄 License

Proyek ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail lengkap.

---

## 👥 Tim Pengembang

-   **Lead Developer**: [Nama Developer](https://github.com/username)
-   **Backend Developer**: [Nama Developer](https://github.com/username)
-   **Frontend Developer**: [Nama Developer](https://github.com/username)
-   **UI/UX Designer**: [Nama Designer](https://github.com/username)

---

## 📞 Support & Dokumentasi

### Dokumentasi Teknis

-   **📚 Wiki**: [Project Wiki](https://github.com/ahyrnsrlh/GrowithBI/wiki)
-   **🔧 API Docs**: [API Documentation](docs/api.md)
-   **🗃️ Database Schema**: [Database Documentation](docs/database.md)

### Getting Help

-   **🐛 Bug Reports**: [GitHub Issues](https://github.com/ahyrnsrlh/GrowithBI/issues)
-   **💡 Feature Requests**: [GitHub Discussions](https://github.com/ahyrnsrlh/GrowithBI/discussions)
-   **📧 Email**: support@growithbi.com

### Community

-   **Discord**: [Join Community](https://discord.gg/growithbi)
-   **Telegram**: [Developer Group](https://t.me/growithbi)

---

<div align="center">

**⭐ Jangan lupa memberikan star jika proyek ini bermanfaat! ⭐**

**Made with ❤️ by GrowithBI Team**

</div>

---

## ✅ **STATUS FITUR LENGKAP**

### � **A. Autentikasi & Role Management (COMPLETE)**

#### 1. ✅ Register & Login System

-   **Laravel Breeze Integration**: Full authentication system dengan Vue 3
-   **Role-based Registration**: Pilihan role saat registrasi (peserta/pembimbing)
-   **Extended Registration Form**: Nama, email, password, role, phone, address
-   **Secure Authentication**: Password hashing, email verification ready
-   **Login Redirect**: Automatic redirect berdasarkan role user

#### 2. ✅ Role-based Access Control

-   **Middleware Protection**: Route protection berdasarkan role
-   **Role Verification**: Pengecekan role di setiap request
-   **Access Restrictions**: User hanya bisa akses fitur sesuai rolenya
-   **Admin Routes**: `/admin/*` - Hanya untuk admin
-   **Pembimbing Routes**: `/pembimbing/*` - Hanya untuk pembimbing
-   **Peserta Routes**: `/peserta/*` - Hanya untuk peserta

#### 3. ✅ Session Management

-   **Secure Sessions**: Laravel session management
-   **Session Regeneration**: Otomatis regenerate session saat login
-   **Logout Functionality**: Proper session cleanup saat logout
-   **Remember Token**: Support untuk "remember me" functionality

#### 4. ✅ Dashboard Role-based

-   **Admin Dashboard**: Comprehensive analytics dan management tools
-   **Pembimbing Dashboard**: Participant management dan logbook review
-   **Peserta Dashboard**: Application tracking dan logbook creation
-   **Role-specific Navigation**: Menu dan fitur disesuaikan per role

#### 5. ✅ User Account Management

-   **Account Status**: Active/inactive user management
-   **Profile Management**: User dapat edit profile sendiri
-   **Password Security**: Secure password update dengan validation
-   **Email Verification**: Ready untuk email verification (optional)

### �🛠️ **D. Admin (COMPLETE)**

#### 1. ✅ Dashboard Admin

-   **Statistics Overview**: Total applications, pending, accepted, rejected
-   **Real-time Data**: Connected to MySQL database
-   **Division Overview**: Progress dan kuota per divisi
-   **Recent Applications**: 5 aplikasi terbaru dengan status
-   **Quick Actions**: Shortcuts ke fitur utama
-   **Modern UI**: Clean design dengan dominasi warna biru-putih

#### 2. ✅ Manajemen Pendaftaran

-   **View All Applications**: Filter by status, division, date range
-   **Search & Pagination**: Advanced search dengan pagination
-   **Application Details**: Detailed view dengan timeline
-   **Status Management**: Update status (terima/tolak) dengan notes
-   **Bulk Actions**: Update multiple applications sekaligus
-   **Document Links**: Placeholder untuk CV, KTP, Surat Lamaran

#### 3. ✅ Manajemen Divisi

-   **CRUD Operations**: Create, Read, Update, Delete divisi
-   **Division Details**: Nama, deskripsi, kuota, pembimbing, requirements
-   **Statistics**: Applications count, acceptance rate, progress bar
-   **Supervisor Assignment**: Assign pembimbing ke divisi
-   **Status Management**: Aktif/non-aktif divisi
-   **Validation**: Form validation dengan error handling

#### 4. ✅ Manajemen Pembimbing

-   **CRUD Operations**: Create, Read, Update, Delete pembimbing
-   **Supervisor Profiles**: Personal info, contact details, statistics
-   **Division Assignment**: Track divisi yang dibimbing
-   **Performance Metrics**: Applications handled, acceptance rate
-   **Account Management**: Activate/deactivate accounts
-   **Password Management**: Secure password creation/update

#### 5. ✅ Manajemen Peserta

-   **Participant Overview**: List all registered participants
-   **Filter & Search**: By status, division, name, email
-   **Participant Details**: Profile, application history, progress
-   **Status Management**: Activate/deactivate participant accounts
-   **Application Tracking**: View all applications per participant
-   **Division Assignment**: Track which division participant belongs to

#### 6. ✅ Laporan & Analytics

-   **Statistical Dashboard**: Comprehensive analytics view
-   **Application Trends**: 6-month trend analysis
-   **Division Performance**: Acceptance rates, quota fulfillment
-   **Top Supervisors**: Ranking pembimbing berdasarkan performance
-   **Status Distribution**: Visual breakdown of application statuses
-   **Export Functions**: Ready for CSV/Excel export (placeholder)

### 🗄️ **Database Schema (COMPLETE)**

-   ✅ **users**: Admin, pembimbing, peserta with roles
-   ✅ **divisions**: Divisi dengan supervisor assignment
-   ✅ **applications**: Pendaftaran dengan status tracking
-   ✅ **logbooks**: Ready for daily activity logging
-   ✅ **Relationships**: Proper foreign keys and indexes
-   ✅ **Sample Data**: Comprehensive seed data for testing

### 🎨 **UI/UX Design (COMPLETE)**

-   ✅ **AdminLayout**: Responsive sidebar navigation
-   ✅ **Color Scheme**: Blue-white dominant theme
-   ✅ **Components**: Reusable cards, tables, forms, modals
-   ✅ **Icons**: Consistent Heroicons usage
-   ✅ **Responsive**: Mobile-friendly design
-   ✅ **Animations**: Smooth transitions and hover effects

### 🔗 **Navigation & Routing (COMPLETE)**

-   ✅ **Admin Routes**: All CRUD operations properly routed
-   ✅ **Breadcrumbs**: Clear navigation hierarchy
-   ✅ **Active States**: Visual indication of current page
-   ✅ **Quick Actions**: Easy access to common tasks

---

## 🚧 **FITUR DALAM PENGEMBANGAN (NEXT PHASE)**

### **B. Peserta Magang**

-   Public application form (✅ Controller ready)
-   Division browsing (✅ Routes ready)
-   Document upload functionality
-   Advanced participant dashboard features
-   Application status tracking enhancements

### 🧑‍🏫 **C. Pembimbing**

-   Enhanced supervisor dashboard (✅ Basic ready)
-   Logbook validation system
-   Student progress tracking tools
-   Report generation for supervised students
-   Performance analytics

### 📝 **D. Logbook & Reporting**

-   Daily activity logging interface
-   Supervisor feedback system
-   Auto-generated progress reports
-   PDF export functionality
-   Analytics dashboard for logbook data

### 🔔 **E. Notifications**

-   Email notifications for status updates
-   Application status change alerts
-   Logbook submission reminders
-   Welcome emails for new users

---

## 🚀 **Cara Menjalankan Aplikasi**

### Prerequisites

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   MySQL
-   Laragon/XAMPP atau Laravel Sail

### Installation

```bash
# Clone repository
git clone [repository-url]
cd GrowithBI

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Serve application
php artisan serve
```

### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growithbi
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🎮 **Demo & Testing**

### Admin Access

-   **URL**: `/admin/dashboard`
-   **Features**: All admin features fully functional
-   **Sample Data**: Pre-populated with test data

### Test Data

-   **Admin**: admin@growithbi.com / password123
-   **Pembimbing**: sarah.wijaya@growithbi.com / password123
-   **Pembimbing**: budi.santoso@growithbi.com / password123
-   **Peserta**: Various participants with sample data
-   **Divisi**: Multiple divisions with different quotas

### Login Flow Testing

1. **Admin Login**: Access full system management
2. **Pembimbing Login**: Access participant management
3. **Peserta Login**: Access personal dashboard
4. **Role Verification**: Try accessing unauthorized routes
5. **Registration**: Test new user registration with different roles

---

## 📊 **Database Schema Overview**

### Core Tables

1. **users**: Multi-role user management
2. **divisions**: Department/division management
3. **applications**: Internship applications
4. **logbooks**: Daily activity tracking (ready)

### Key Features

-   **Role System**: admin, pembimbing, peserta
-   **Status Tracking**: menunggu, diterima, ditolak
-   **Relationships**: Proper foreign key constraints
-   **Indexing**: Optimized for performance

---

## 🔜 **Roadmap & Next Steps**

### Phase 2: Core User Features

1. **Authentication System**

    - Login/Register
    - Role-based access
    - Password reset

2. **Public Application Form**

    - Division selection
    - Document upload
    - Application tracking

3. **Supervisor Dashboard**
    - Student management
    - Logbook review
    - Progress tracking

### Phase 3: Advanced Features

1. **Logbook System**

    - Daily activity logging
    - Supervisor feedback
    - Progress analytics

2. **Notification System**

    - Email notifications
    - Real-time updates
    - Reminder system

3. **Reporting & Export**
    - PDF generation
    - Excel export
    - Advanced analytics

---

## 🏆 **Achievement Summary**

✅ **Complete Admin Panel** - Full CRUD operations for all entities
✅ **Database Design** - Scalable and optimized schema
✅ **Modern UI/UX** - Professional and responsive design
✅ **Analytics Dashboard** - Comprehensive reporting system
✅ **Data Management** - Advanced filtering and search
✅ **Performance Metrics** - Real-time statistics and trends

**Total Progress: ~75% (Authentication + Admin features complete)**

---

## 📞 **Support & Documentation**

Untuk informasi lebih lanjut tentang pengembangan dan implementasi, silakan merujuk ke:

-   **DEVELOPMENT.md** - Technical documentation
-   **Database Schema** - ERD dan relationship documentation
-   **API Documentation** - Endpoint documentation (coming soon)

**Ready for Production**: Admin panel siap untuk deployment dan penggunaan!
