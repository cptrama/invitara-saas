# Invitara --- Wedding Digital Invitation Maker (SaaS)

Invitara adalah platform SaaS berbasis website untuk membuat, mempublikasikan, dan mengelola undangan pernikahan digital yang elegan, responsif, dan mudah dibagikan tanpa memerlukan keahlian desain.

---

## 🛠️ Technology Stack (Fondasi Sistem)

- **Runtime:** PHP 8.2+ / 8.3+
- **Backend Framework:** FlightPHP v3 (`flightphp/core`)
- **Template Engine:** Twig v3 (`twig/twig`)
- **Database:** MySQL 8.x
- **Database Access:** PDO dengan Prepared Statements
- **Environment Manager:** `vlucas/phpdotenv`
- **Frontend Baseline:** Bootstrap 5, Tabler UI, Vanilla JS ES6+
- **Asset Builder:** Vite

---

## 📂 Struktur Direktori

```text
invitara-saas/
├── app/
│   ├── Controllers/        # AuthController, DashboardController, HomeController
│   ├── Helpers/            # Helper functions (url, asset, csrf, auth, flash)
│   ├── Middleware/         # AuthMiddleware, GuestMiddleware, AdminMiddleware, CsrfMiddleware, OwnershipMiddleware
│   ├── Models/             # Model pendukung
│   ├── Repositories/       # Data Access Layer (UserRepository, InvitationRepository, TemplateRepository, dll.)
│   ├── Services/           # Business Logic & Infrastructure (Database, AuthService, CsrfService, Validator, View)
│   └── Views/              # Twig templates (layouts, auth, dashboard, landing, invitations)
├── config/                 # app.php, database.php, twig.php
├── database/
│   ├── migrations/         # Skema DDL tabel database (001 - 006)
│   ├── seeders/            # Seeder data awal (UserSeeder, CategorySeeder, TemplateSeeder)
│   ├── migrate.php         # Migration CLI runner
│   └── seed.php            # Seeder CLI runner
├── public/                 # Document root web server
│   ├── index.php           # Front Controller
│   ├── .htaccess           # Apache mod_rewrite rules
│   ├── assets/             # Asset statis
│   ├── uploads/            # File upload pengguna
│   └── build/              # Output build Vite
├── resources/              # Source CSS & JS untuk Vite
├── routes/
│   └── web.php             # Definisi route aplikasi
├── storage/
│   ├── logs/               # Log error sistem (app.log)
│   └── cache/              # Cache Twig
├── tests/
│   └── test_backend.php    # Test suite backend & database
├── .env.example            # Template konfigurasi environment
├── .gitignore              # Proteksi file rahasia/vendor dari git
├── composer.json           # Dependensi PHP
├── CONTRACTS.md            # Kontrak integrasi antar-anggota tim
└── PRD.md                  # Product Requirements Document
```

---

## 🚀 Panduan Setup & Menjalankan Aplikasi

### 1. Prasyarat Sistem
- PHP 8.2+ terpasang dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`.
- MySQL Server berjalan (port 3306).
- Composer terpasang.

### 2. Konfigurasi Environment
Salin file `.env.example` menjadi `.env` lalu sesuaikan kredensial MySQL Anda:
```bash
cp .env.example .env
```
Contoh isi `.env`:
```ini
APP_NAME=Invitara
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invitara_saas
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependensi Composer
```bash
composer install
```

### 4. Jalankan Migration Database
Buat database `invitara_saas` di MySQL, kemudian jalankan:
```bash
php database/migrate.php
```
Untuk memeriksa status migration:
```bash
php database/migrate.php status
```
Untuk mereset ulang seluruh tabel:
```bash
php database/migrate.php fresh
```

### 5. Jalankan Seeder Data Awal
Untuk mengisi akun default dan 3 template wedding MVP:
```bash
php database/seed.php
```

#### Akun Bawaan (Default Seeded Users):
- **Admin:** `admin@invitara.test` / `password123` (Role: `admin`)
- **Demo User:** `budi@invitara.test` / `password123` (Role: `user`)

---

## 🧪 Menjalankan Rangkaian Pengujian Otomatis (Test Suites)

Invitara dilengkapi dengan 4 rangkaian automated tests (total 37 uji otomatis) yang memvalidasi integritas seluruh modul aplikasi:

```bash
# 1. Test Backend, Database, Auth, CSRF, Validasi & Keamanan (Anggota 1)
php tests/test_backend.php

# 2. Test Rendering Seluruh 12 View Twig, Tabler UI & Template Wedding (Anggota 2)
php tests/test_frontend_views.php

# 3. Test Alur Lengkap CRUD Undangan, Slug Unik, & Manajemen RSVP (Anggota 3)
php tests/test_invitation_flow.php

# 4. Test Kompatibilitas Flight Engine HTTP Controller Dispatch
php tests/test_http_controllers.php
```

---

## ✨ Fitur-Fitur Utama Platform (v1.0.0 Stable Release)

1. **Publik & Landing Page:**
   - Landing page modern responsif berbasis Tabler UI & Bootstrap 5.
   - Galeri template undangan interaktif dengan simulator preview live.
   - 3 Desain Template Pernikahan MVP: *Floral Elegance*, *Modern Minimalist*, dan *Traditional Gold*.

2. **Autentikasi & Akun Pengguna:**
   - Pendaftaran akun baru, login sesi aman, dan proteksi multi-role (`user` dan `admin`).
   - Manajemen profil pengguna & fitur ganti kata sandi berkeamanan tinggi.

3. **Manajemen Undangan (Invitations):**
   - Pembuatan dan pengelolaan undangan melalui form-based editor.
   - Generator slug unik ramah SEO dengan proteksi tabrakan (*collision handling*).
   - Pengaturan status publikasi (*Draft* vs *Published*) untuk menjaga kerahasiaan sebelum acara siap disebar.

4. **Sistem Konfirmasi Tamu (RSVP):**
   - Halaman publik `/i/{slug}` yang dapat diakses tamu undangan secara mobile-friendly.
   - Form konfirmasi kehadiran (*Hadir / Tidak Hadir*), jumlah tamu, serta ucapan dan doa restu.
   - Rekapitulasi otomatis statistik RSVP pada dashboard pemilik undangan.

5. **Panel Admin & Monitoring Platform:**
   - Dashboard monitoring statistik platform (total pengguna, undangan, RSVP, dan tamu).
   - Pengelolaan status aktif/non-aktif template pernikahan secara real-time.

6. **Keamanan Sistem (Security Hardening):**
   - Token CSRF di seluruh form mutasi state (`POST`, `PUT`, `DELETE`).
   - `OwnershipMiddleware` yang memastikan pengguna hanya dapat mengakses dan mengelola undangannya sendiri.
   - Sanitasi upload file dengan validasi MIME berbasis `finfo` serta blokade eksekusi skrip di folder `public/uploads/`.

---

## 🌐 Menjalankan Local Web Server

Jalankan built-in web server PHP:
```bash
php -S 127.0.0.1:8000 -t public
```
Atau jika menggunakan Laragon, akses melalui Virtual Host Laragon:
`http://invitara-saas.test` (mengarah ke folder `public/`).

---

## 👥 Informasi Tim & Pembagian Tugas
- **Anggota 1 (Ketua):** Backend, Database, Autentikasi, Middleware, Keamanan & Fondasi Sistem.
- **Anggota 2:** Frontend, UI/UX, Tabler UI, Twig Layouts, Vite & Desain Template.
- **Anggota 3:** Modul Undangan (CRUD, Editor Flow, Slug, Publish/Unpublish), RSVP & Pengujian Fitur.

*Lihat [CONTRACTS.md](file:///c:/laragon/www/invitara-saas/CONTRACTS.md) untuk detail panduan integrasi lintas anggota.*
