# PRD --- INVITARA

## Wedding Digital Invitation Maker

**Versi:** 1.1
**Status:** Spesifikasi pengembangan (SSR)
**Jenis Produk:** Software as a Service (SaaS) berbasis website
**Fokus Produk:** Pembuatan dan pengelolaan undangan pernikahan digital
**Target Pengguna:** Masyarakat umum
**Pendekatan Frontend:** Server-Side Rendering (SSR)
**Editor:** Form-based editor dengan preview
**Tim:** 3 mahasiswa

---

# 1. Ringkasan Produk

Invitara adalah platform SaaS berbasis website yang membantu pengguna
membuat, mempublikasikan, dan mengelola undangan pernikahan digital
tanpa harus memiliki keterampilan desain grafis. Pengguna memilih
template, mengisi formulir informasi pernikahan, menambahkan foto,
memilih variasi tampilan yang disediakan, melihat preview, kemudian
menerbitkan undangan sebagai halaman web dengan tautan unik.

Penerima undangan dapat membuka tautan melalui desktop maupun ponsel,
membaca informasi acara, dan mengirimkan konfirmasi kehadiran (RSVP).
Pemilik undangan dapat memantau respons tamu melalui dashboard.

### 1.1 Pernyataan Nilai

"Buat undangan pernikahan digital yang personal dan mudah dibagikan,
tanpa proses desain yang rumit."

### 1.2 Masalah yang Diselesaikan

1. Pembuatan undangan digital secara mandiri dapat membutuhkan
   keterampilan desain dan waktu.
2. Perubahan informasi acara dapat menyulitkan apabila undangan sudah
   dibagikan.
3. Penyelenggara membutuhkan satu halaman yang mudah dibagikan untuk
   menyampaikan detail acara.
4. Pengelola acara membutuhkan cara sederhana untuk mengetahui
   konfirmasi kehadiran tamu.

### 1.3 Solusi

Invitara menyediakan: - Template undangan pernikahan siap pakai. -
Formulir untuk mengisi informasi acara. - Preview undangan sebelum
diterbitkan. - Halaman undangan publik dengan URL unik. - Formulir RSVP
bagi penerima undangan. - Dashboard untuk mengelola undangan dan melihat
respons tamu.

---

# 2. Tujuan Produk dan Indikator Keberhasilan

### 2.1 Tujuan

- Memungkinkan pengguna membuat undangan pernikahan digital secara
  mandiri.
- Mempermudah publikasi dan pembaruan informasi acara.
- Menyediakan pencatatan RSVP yang terpusat.
- Menghasilkan produk SaaS yang dapat digunakan dan diuji oleh
  pengguna nyata.

### 2.2 Indikator Keberhasilan MVP

  Indikator                                               Target MVP

---

  Pengguna dapat mendaftar dan login                        Berhasil
  Pengguna dapat membuat undangan                           Berhasil
  Template yang tersedia                                   Minimal 3
  Undangan dapat dipublikasikan dengan URL unik             Berhasil
  Halaman publik dapat dibuka tanpa login                   Berhasil
  Tamu dapat mengirim RSVP                                  Berhasil
  Pemilik dapat melihat daftar RSVP                         Berhasil
  Pengujian pengguna awal                           Minimal 10 orang
  Website dapat diakses secara online                       Berhasil

Target di atas adalah sasaran pengembangan dan pengujian, bukan klaim
hasil yang sudah dicapai.

---

# 3. Target Pengguna dan Hak Akses

### 3.1 Target Pengguna Utama

Masyarakat umum yang sedang merencanakan pernikahan dan membutuhkan
undangan digital yang praktis.

Karakteristik: - Tidak harus memiliki kemampuan desain. - Menggunakan
ponsel atau komputer untuk mengelola undangan. - Membutuhkan cara mudah
untuk membagikan informasi acara. - Ingin mengetahui respons kehadiran
tamu.

### 3.2 Jenis Pengguna

---

  Peran                              Hak Akses

---

  Pengunjung                         Melihat landing page dan contoh
                                     template

  Pengguna terdaftar                 Membuat, mengedit, menerbitkan,
                                     dan mengelola undangan miliknya

  Tamu undangan                      Melihat undangan publik dan
                                     mengirim RSVP

Admin aplikasi                     Mengelola kategori dan template
                                     serta memantau penggunaan platform
-----------------------------------------------------------------------

Admin aplikasi berbeda dari pemilik undangan. Fungsi admin dapat dibuat
sederhana dan hanya tersedia bagi akun dengan peran admin.

### 3.3 Isolasi Data

Setiap pengguna hanya boleh mengakses dan mengubah undangan miliknya.
Pemeriksaan kepemilikan wajib dilakukan di backend pada setiap operasi
baca, ubah, dan hapus. Menyembunyikan tombol di frontend saja tidak
cukup.

---

# 4. Ruang Lingkup Produk

### 4.1 Termasuk MVP

1. Landing page.
2. Register, login, dan logout.
3. Dashboard pengguna.
4. Galeri kategori dan template wedding.
5. Pembuatan undangan menggunakan form-based editor.
6. Upload foto utama dan foto galeri dengan validasi.
7. Preview undangan.
8. Simpan sebagai draft.
9. Publikasi dan pembatalan publikasi.
10. URL publik unik untuk setiap undangan.
11. Halaman undangan publik yang responsif.
12. Form RSVP.
13. Daftar dan ringkasan RSVP di dashboard.
14. CRUD undangan milik pengguna.
15. Pengaturan profil dasar.

### 4.2 Di Luar MVP

Fitur berikut bukan syarat rilis awal dan hanya dikerjakan jika waktu
mencukupi: - Editor drag-and-drop seperti Canva. - Pembayaran online dan
payment gateway. - Paket berlangganan berbayar. - Custom domain. -
Pengiriman WhatsApp otomatis melalui Business API. - Buku tamu digital
dengan QR check-in. - Integrasi kalender. - Multi-bahasa. - Analitik
lanjutan. - Aplikasi mobile native.

---

# 5. Fondasi Teknologi dan Arsitektur

## 5.1 Technology Stack

---

  Lapisan                 Teknologi               Tanggung jawab

---

  Backend framework       FlightPHP v3            Routing,
                                                  request/response,
                                                  middleware

  Runtime                 PHP 8.3+                Logika aplikasi

  Frontend rendering      Twig                    Merender HTML dari data
                                                  server

  UI dashboard            Tabler UI               Komponen dan layout
                                                  admin

  CSS framework           Bootstrap 5             Grid, responsive
                                                  layout, komponen

  Frontend scripting      Vanilla JavaScript ES6+ Interaksi form,
                                                  preview, dan komponen

  Asset build             Vite                    Build dan pengelolaan
                                                  aset CSS/JS

  Database                MySQL 8.x               Penyimpanan data

  Database access         PDO                     Koneksi dan query
                                                  database

  Dependency manager      Composer                Library PHP

  Authentication          PHP Session             Sesi login

  Password security       `password_hash()` dan   Hashing dan verifikasi
                          `password_verify()`     password

  Migration               Flight Runway atau      Perubahan skema
                          migration tool yang     database
                          dipilih tim

  Version control         Git dan GitHub          Kolaborasi dan riwayat
                                                  kode

Web server              Apache atau Nginx       Menjalankan aplikasi
--------------------------------------------------------------------

**Ketentuan versi:** versi PHP, FlightPHP, Twig, Bootstrap, Tabler,
Vite, dan MySQL harus dikunci dan diuji kompatibilitasnya saat
inisialisasi proyek. PHP 8.3+ merupakan target awal; sesuaikan dengan
dukungan versi FlightPHP dan hosting yang dipilih.

## 5.2 Pendekatan SSR

Invitara menggunakan Server-Side Rendering (SSR). Browser meminta
halaman ke server, FlightPHP memproses route dan logika aplikasi, data
diambil dari MySQL melalui PDO, lalu Twig merender HTML yang dikirimkan
kepada browser.

Alur request:

```text
Browser
  |
  | HTTP request
  v
Web Server (Apache / Nginx)
  |
  v
FlightPHP Router
  |
  v
Middleware (session, auth, authorization)
  |
  v
Controller
  |
  v
Service / Repository
  |
  v
PDO
  |
  v
MySQL
  |
  v
Controller receives data
  |
  v
Twig renders HTML
  |
  v
Browser displays page
```

Halaman dashboard dan halaman undangan publik sama-sama dirender di
server. JavaScript digunakan untuk interaksi tambahan, bukan sebagai
aplikasi frontend terpisah.

## 5.3 Pemisahan Antarmuka

**A. Dashboard pengguna** - Menggunakan Tabler UI sebagai dasar tampilan
admin. - Menggunakan Bootstrap 5 untuk grid, form, modal, tabel, dan
komponen responsif. - Menggunakan Twig untuk layout dan data dinamis.

**B. Halaman undangan publik** - Menggunakan Twig untuk merender data
undangan. - Menggunakan HTML5, CSS3, Bootstrap 5 bila diperlukan, dan
JavaScript. - Memiliki desain wedding yang elegan dan berbeda dari
dashboard admin. - Tabler tidak wajib digunakan pada halaman publik
karena Tabler ditujukan terutama sebagai UI dashboard.

## 5.4 Tanggung Jawab Teknologi

- FlightPHP menangani route, controller, middleware, dan response.
- Twig memisahkan struktur HTML dari logika PHP.
- Tabler menyediakan komponen UI dashboard.
- Bootstrap 5 menyediakan sistem layout dan komponen.
- Vite mengelola aset frontend selama development dan menghasilkan
  aset production.
- PDO digunakan dengan prepared statements untuk query database.
- MySQL menyimpan akun, template, undangan, foto, dan RSVP.
- PHP Session mengelola sesi autentikasi.

## 5.5 Prinsip Implementasi

- Gunakan pola Controller--Service--Repository secara proporsional;
  hindari abstraksi berlebihan.
- Gunakan Composer untuk dependency PHP.
- Gunakan migration dan seeder untuk database.
- Gunakan Twig escaping untuk output teks dan sanitasi/validasi URL.
- Gunakan request validation pada server.
- Gunakan PDO prepared statements.
- Terapkan middleware untuk autentikasi dan pemeriksaan hak akses.
- Gunakan `password_hash()` dan `password_verify()`.
- Aktifkan perlindungan CSRF pada form yang mengubah data.
- Batasi tipe, ukuran, dan nama file upload.
- Simpan file upload di lokasi yang tidak dapat mengeksekusi script.
- Jangan menyimpan secret key atau kredensial di repository.
- Simpan konfigurasi sensitif pada environment variables.
- Jangan mengandalkan validasi JavaScript sebagai satu-satunya
  validasi.
- Hindari membangun SPA atau REST API terpisah untuk seluruh halaman
  pada MVP.

---

# 6. Struktur Fitur dan Kebutuhan Fungsional

## 6.1 Landing Page

**Tujuan:** Menjelaskan manfaat Invitara dan mengarahkan pengunjung
untuk mencoba produk.

Kebutuhan: - Menampilkan nama, logo, dan deskripsi singkat Invitara. -
Menampilkan contoh desain undangan. - Menjelaskan langkah penggunaan. -
Menampilkan CTA "Buat Undangan". - Menyediakan tautan login dan
register. - Responsif pada desktop dan perangkat mobile.

## 6.2 Autentikasi

Kebutuhan: - Pengguna dapat membuat akun dengan nama, email, dan
password. - Email harus unik. - Password disimpan menggunakan hashing. -
Pengguna dapat login dan logout. - Dashboard hanya dapat diakses setelah
autentikasi. - Pesan validasi ditampilkan jika input tidak sesuai. -
Reset password dapat ditambahkan jika infrastruktur email tersedia;
bukan penghambat MVP.

## 6.3 Dashboard Pengguna

Menampilkan: - Jumlah undangan milik pengguna. - Jumlah undangan
berstatus draft. - Jumlah undangan yang sudah dipublikasikan. - Total
respons RSVP. - Daftar undangan terbaru. - Tombol membuat undangan baru.

## 6.4 Galeri Template

Kebutuhan: - Menampilkan template wedding yang tersedia. - Setiap
template memiliki nama, thumbnail, deskripsi singkat, dan status
aktif. - Pengguna dapat melihat preview template. - Pengguna dapat
memilih template untuk membuat undangan. - Template yang dinonaktifkan
admin tidak dapat dipilih untuk undangan baru.

MVP menyediakan minimal tiga template: 1. Minimalis elegan. 2. Floral
klasik. 3. Modern sederhana.

Template memiliki struktur konten konsisten agar data undangan dapat
dirender oleh beberapa desain.

## 6.5 Form-based Invitation Editor

Pengguna mengisi formulir dan memilih opsi tampilan. Sistem memasukkan
data ke template. Pengguna tidak menggeser elemen secara bebas.

### A. Informasi pasangan

- Nama pasangan pertama.
- Nama pasangan kedua.
- Foto utama pasangan (opsional).

### B. Informasi acara

- Judul atau nama acara.
- Tanggal acara.
- Waktu acara.
- Zona waktu.
- Deskripsi atau pesan pembuka.

### C. Detail lokasi

- Nama tempat.
- Alamat.
- URL Google Maps (opsional).

### D. Konten tambahan

- Cerita singkat pasangan (opsional).
- Foto galeri (opsional).
- Kontak penyelenggara (opsional).

### E. Personalisasi

- Pilihan template.
- Pilihan variasi warna yang disediakan template.
- Teks penutup.

Ketentuan: - Validasi dilakukan di frontend dan backend. - Pengguna
dapat menyimpan draft. - Pengguna dapat melihat preview sebelum
publikasi. - Form dapat diedit kembali setelah publikasi. - Perubahan
pada undangan terbit harus disimpan secara eksplisit. - Pilihan desain
dibatasi pada opsi yang disediakan template.

## 6.6 Preview

Kebutuhan: - Menampilkan hasil berdasarkan data form dan template yang
dipilih. - Preview menyerupai halaman publik. - Pengguna dapat kembali
ke form. - Preview draft hanya dapat diakses pemilik atau pengguna
berwenang.

## 6.7 Publikasi dan URL

Kebutuhan: - Pengguna dapat menerbitkan undangan yang memenuhi validasi
minimum. - Sistem menghasilkan slug unik, misalnya `/i/andi-siti`
(domain hanya contoh). - Slug harus unik di seluruh platform. - Halaman
publik dapat dibuka tanpa login. - Pengguna dapat menyalin tautan
undangan. - Pengguna dapat membatalkan publikasi. - Sistem menangani
slug yang sudah digunakan dengan meminta slug alternatif atau membuat
akhiran unik.

## 6.8 Halaman Undangan Publik

Menampilkan bagian yang tersedia pada template: - Sampul dan nama
pasangan. - Tanggal acara. - Countdown jika diaktifkan. - Informasi
acara dan waktu. - Lokasi dan tombol menuju peta jika URL tersedia. -
Foto galeri jika diunggah. - Pesan penutup. - Form RSVP.

Ketentuan: - Halaman dapat dibuka tanpa akun. - Tampilan responsif dan
berorientasi mobile. - Data opsional yang kosong tidak menampilkan
bagian kosong. - Draft atau undangan yang tidak dipublikasikan tidak
dapat diakses sebagai undangan publik.

## 6.9 RSVP

Form tamu: - Nama tamu. - Status kehadiran: hadir atau tidak hadir. -
Jumlah tamu yang hadir, jika status hadir. - Pesan atau ucapan
(opsional).

Aturan: - RSVP hanya dapat dikirim untuk undangan yang dipublikasikan. -
Validasi dilakukan di backend. - Pemilik undangan dapat melihat respons
pada dashboard. - RSVP memiliki waktu pembuatan. - Untuk MVP, satu
pengiriman RSVP menghasilkan satu entri. Fitur edit respons oleh tamu
dapat ditambahkan kemudian. - Pemilik dapat menghapus entri RSVP yang
tidak valid melalui dashboard.

## 6.10 Manajemen RSVP

Menampilkan: - Nama tamu. - Status kehadiran. - Jumlah orang. - Pesan. -
Waktu respons.

Ringkasan: - Total respons. - Total respons hadir. - Total respons tidak
hadir. - Total jumlah orang yang menyatakan hadir.

Jumlah respons RSVP tidak selalu sama dengan jumlah orang yang hadir
karena satu respons dapat mewakili lebih dari satu orang.

## 6.11 Pengaturan Profil

- Melihat dan mengubah nama pengguna.
- Melihat email akun.
- Mengubah password melalui mekanisme autentikasi yang aman.
- Logout.

---

# 7. Alur Pengguna Utama

## 7.1 Alur Pemilik Undangan

1. Pengunjung membuka landing page.
2. Pengunjung membuat akun atau login.
3. Pengguna masuk ke dashboard.
4. Pengguna memilih "Buat Undangan".
5. Pengguna memilih template.
6. Pengguna mengisi formulir informasi pernikahan.
7. Pengguna menyimpan draft.
8. Pengguna melihat preview.
9. Pengguna memperbaiki informasi bila diperlukan.
10. Pengguna menerbitkan undangan.
11. Sistem menghasilkan URL unik.
12. Pengguna membagikan URL kepada tamu.
13. Pengguna memantau RSVP melalui dashboard.

## 7.2 Alur Tamu

1. Tamu menerima tautan undangan.
2. Tamu membuka halaman undangan.
3. Tamu membaca informasi acara.
4. Tamu mengisi formulir RSVP.
5. Sistem menyimpan respons.
6. Tamu mendapatkan pesan bahwa respons berhasil dikirim.

---

# 8. Aturan Bisnis

1. Satu akun dapat memiliki beberapa undangan.
2. Setiap undangan dimiliki oleh tepat satu pengguna.
3. Setiap undangan menggunakan satu template aktif.
4. Setiap undangan memiliki slug unik.
5. Undangan baru berstatus `draft`.
6. Undangan hanya dapat dilihat publik jika berstatus `published`.
7. Pengguna hanya dapat mengelola undangannya sendiri.
8. RSVP hanya dapat dibuat pada undangan berstatus `published`.
9. Tanggal dan waktu acara divalidasi sesuai aturan form.
10. Data opsional yang kosong tidak ditampilkan pada halaman publik.
11. Penghapusan undangan ditangani secara konsisten agar RSVP terkait
    tidak menjadi data yatim.
12. File gambar divalidasi berdasarkan tipe dan ukuran, disimpan dengan
    nama file aman, dan tidak menerima file executable.
13. Tanggal dan waktu ditampilkan secara konsisten dengan zona waktu
    yang disimpan atau ditentukan aplikasi.

### 8.1 Status Undangan

  Status          Arti

---

  `draft`         Sedang dibuat dan belum dipublikasikan
  `published`     Dapat diakses melalui URL publik
  `unpublished`   Pernah diterbitkan tetapi dinonaktifkan

---

# 9. Rancangan Database

Rancangan berikut merupakan struktur awal yang dapat disesuaikan saat
implementasi.

## 9.1 `users`

  Kolom        Tipe                   Keterangan

---

  id           BIGINT, PK             ID pengguna
  name         VARCHAR(100)           Nama pengguna
  email        VARCHAR(150), UNIQUE   Email
  password     VARCHAR(255)           Password hash
  role         VARCHAR(20)            `user` atau `admin`
  created_at   TIMESTAMP              Waktu dibuat
  updated_at   TIMESTAMP              Waktu diperbarui

## 9.2 `categories`

  Kolom         Tipe                   Keterangan

---

  id            BIGINT, PK             ID kategori
  name          VARCHAR(100)           Nama kategori
  slug          VARCHAR(120), UNIQUE   Identitas URL
  description   TEXT, nullable         Deskripsi

MVP dapat menggunakan satu kategori utama `Wedding`, tetapi tabel
kategori memudahkan pengembangan berikutnya.

## 9.3 `templates`

  Kolom            Tipe                   Keterangan

---

  id               BIGINT, PK             ID template
  category_id      BIGINT, FK             Kategori
  name             VARCHAR(120)           Nama template
  slug             VARCHAR(140), UNIQUE   Identitas template
  thumbnail_path   VARCHAR(255)           Lokasi thumbnail
  view_key         VARCHAR(100)           Nama view/template renderer
  description      TEXT, nullable         Deskripsi
  is_active        BOOLEAN                Status aktif
  created_at       TIMESTAMP              Waktu dibuat
  updated_at       TIMESTAMP              Waktu diperbarui

## 9.4 `invitations`

  Kolom              Tipe                     Keterangan

---

  id                 BIGINT, PK               ID undangan
  user_id            BIGINT, FK               Pemilik undangan
  template_id        BIGINT, FK               Template terpilih
  title              VARCHAR(150)             Judul undangan
  slug               VARCHAR(180), UNIQUE     URL publik
  partner_one_name   VARCHAR(100)             Nama pasangan pertama
  partner_two_name   VARCHAR(100)             Nama pasangan kedua
  cover_photo_path   VARCHAR(255), nullable   Foto sampul
  event_date         DATE                     Tanggal acara
  event_time         TIME, nullable           Waktu acara
  timezone           VARCHAR(50)              Zona waktu
  venue_name         VARCHAR(150), nullable   Nama tempat
  venue_address      TEXT, nullable           Alamat
  maps_url           TEXT, nullable           URL peta
  description        TEXT, nullable           Deskripsi
  closing_message    TEXT, nullable           Pesan penutup
  theme_color        VARCHAR(30), nullable    Pilihan warna yang diizinkan
  status             VARCHAR(20)              Status undangan
  published_at       TIMESTAMP, nullable      Waktu publikasi
  created_at         TIMESTAMP                Waktu dibuat
  updated_at         TIMESTAMP                Waktu diperbarui

## 9.5 `invitation_photos`

  Kolom           Tipe                     Keterangan

---

  id              BIGINT, PK               ID foto
  invitation_id   BIGINT, FK               Undangan
  photo_path      VARCHAR(255)             Lokasi file
  caption         VARCHAR(150), nullable   Keterangan
  sort_order      INTEGER                  Urutan foto
  created_at      TIMESTAMP                Waktu dibuat

## 9.6 `rsvps`

  Kolom               Tipe             Keterangan

---

  id                  BIGINT, PK       ID RSVP
  invitation_id       BIGINT, FK       Undangan
  guest_name          VARCHAR(120)     Nama tamu
  attendance_status   VARCHAR(20)      `attending` atau `not_attending`
  guest_count         INTEGER          Jumlah orang yang hadir
  message             TEXT, nullable   Ucapan
  created_at          TIMESTAMP        Waktu respons
  updated_at          TIMESTAMP        Waktu diperbarui

## 9.7 Relasi

```text
users
  └── invitations (one-to-many)

categories
  └── templates (one-to-many)

templates
  └── invitations (one-to-many)

invitations
  ├── invitation_photos (one-to-many)
  └── rsvps (one-to-many)
```

Gunakan foreign key, index pada kolom pencarian yang sering dipakai,
serta kebijakan penghapusan relasi yang konsisten.

Tabel `plans` dan `subscriptions` tidak diperlukan untuk MVP jika
pembayaran dan langganan belum diimplementasikan.

---

# 10. Rancangan Halaman dan Navigasi

## 10.1 Halaman Publik

- `/` --- Landing page.
- `/templates` --- Galeri template.
- `/templates/{slug}` --- Preview template.
- `/login` --- Login.
- `/register` --- Registrasi.
- `/i/{slug}` --- Undangan publik.

## 10.2 Halaman Pengguna Terautentikasi

- `/dashboard` --- Dashboard.
- `/dashboard/invitations` --- Daftar undangan.
- `/dashboard/invitations/create` --- Buat undangan.
- `/dashboard/invitations/{id}/edit` --- Edit undangan.
- `/dashboard/invitations/{id}/preview` --- Preview.
- `/dashboard/invitations/{id}/rsvps` --- Manajemen RSVP.
- `/dashboard/profile` --- Pengaturan profil.

Rute aktual mengikuti konvensi FlightPHP yang digunakan. Semua rute
dashboard wajib dilindungi middleware autentikasi dan pemeriksaan
kepemilikan.

## 10.3 Navigasi Dashboard

- Overview
- My Invitations
- Create Invitation
- Templates
- Profile
- Logout

## 10.4 Struktur View Twig

```text
app/
└── Views/
    ├── layouts/
    │   ├── dashboard.twig
    │   ├── public.twig
    │   └── auth.twig
    ├── components/
    ├── landing/
    ├── auth/
    ├── dashboard/
    ├── templates/
    ├── editor/
    └── invitations/
        ├── preview.twig
        └── public/
            ├── minimal.twig
            ├── floral.twig
            └── modern.twig
```

Dashboard memakai layout berbasis Tabler. Halaman undangan publik
memakai layout Twig khusus wedding. Twig digunakan untuk rendering di
server; template undangan tidak menggunakan Tabler sebagai desain wajib.

---

# 11. Pengelolaan Aset dengan Vite

Vite digunakan untuk mengelola CSS dan JavaScript selama development
serta menghasilkan aset production.

Ketentuan: - Source CSS dan JavaScript disimpan dalam direktori aset
frontend yang ditentukan tim. - Bootstrap dan Tabler dipasang melalui
npm atau dikelola sebagai aset vendor secara konsisten. - Vite membangun
file production ke direktori publik. - Twig memanggil file hasil build
melalui manifest Vite atau mekanisme asset helper yang dibuat tim. -
Jangan memuat versi CDN dan versi lokal dari library yang sama secara
bersamaan. - File hasil build production harus tersedia pada deployment.

Contoh alur:

```text
resources/
  css/
  js/
      app.js

        |
        v
      Vite

        |
        v

public/build/
  assets/
  manifest.json

        |
        v

Twig layout references built assets
```

---

# 12. Kebutuhan Nonfungsional

## 12.1 Usability

- Form mudah dipahami pengguna umum.
- Label dan pesan validasi menggunakan Bahasa Indonesia.
- Alur pembuatan undangan tidak berbelit-belit.
- Preview membantu pengguna memeriksa hasil sebelum publikasi.

## 12.2 Responsive Design

- Halaman publik nyaman dibuka melalui ponsel.
- Dashboard dapat digunakan pada desktop dan ponsel.
- Form tidak melebar keluar dari layar.

## 12.3 Security

- Password tidak disimpan dalam bentuk teks biasa.
- Endpoint dashboard memerlukan autentikasi.
- Backend memeriksa kepemilikan data.
- Validasi input dan upload dilakukan di server.
- Data pribadi tidak ditampilkan pada halaman publik tanpa kebutuhan.
- Slug publik tidak boleh menjadi jalan untuk mengakses draft.
- Gunakan session cookie dengan konfigurasi aman pada production.
- Terapkan CSRF protection pada form yang mengubah data.
- Gunakan output escaping Twig dan validasi URL untuk mencegah injeksi
  konten.

## 12.4 Reliability

- Kegagalan upload atau penyimpanan menampilkan pesan jelas.
- Data tidak hilang saat pengguna berpindah halaman sebelum menyimpan.
- Publikasi hanya berhasil jika data minimum valid.

## 12.5 Performance

- Optimalkan ukuran foto.
- Gunakan thumbnail pada galeri template.
- Hindari memuat aset yang tidak digunakan.
- Gunakan pagination untuk daftar panjang.
- Gunakan cache yang sesuai bila diperlukan, tanpa mengorbankan
  konsistensi data.

---

# 13. Kriteria Penerimaan (Acceptance Criteria)

---

  ID                      Skenario                Kriteria diterima

---

  AC-01                   Registrasi              Akun baru tersimpan dan
                                                  email unik diterapkan

  AC-02                   Login                   Pengguna dapat masuk
                                                  dengan kredensial benar

  AC-03                   Proteksi dashboard      Pengunjung tanpa login
                                                  tidak dapat membuka
                                                  dashboard

  AC-04                   Membuat undangan        Form valid menghasilkan
                                                  undangan draft

  AC-05                   Memilih template        Template aktif dapat
                                                  dipilih

  AC-06                   Preview                 Data undangan tampil
                                                  pada template yang
                                                  dipilih

  AC-07                   Publikasi               Undangan valid mendapat
                                                  slug unik dan status
                                                  published

  AC-08                   Halaman publik          URL undangan dapat
                                                  dibuka tanpa login

  AC-09                   RSVP                    Tamu dapat mengirim
                                                  respons valid

  AC-10                   Statistik RSVP          Dashboard menampilkan
                                                  ringkasan respons
                                                  sesuai

  AC-11                   Isolasi data            Pengguna tidak dapat
                                                  mengubah undangan
                                                  pengguna lain

  AC-12                   Responsif               Halaman utama dapat
                                                  digunakan pada layar
                                                  ponsel

AC-13                   Unpublish               Undangan yang
                                                  dinonaktifkan tidak
                                                  tampil sebagai halaman
                                                  publik
--------------------------------------------------------

---

# 14. Pengujian Produk dan Early Users

Sesuai panduan proyek, Invitara harus diuji oleh minimal 10 pengguna
awal yang benar-benar menggunakan produk.

## 14.1 Skenario Pengujian

1. Tester membuka website.
2. Tester membuat akun.
3. Tester memilih template.
4. Tester mengisi informasi undangan.
5. Tester melihat preview.
6. Tester menerbitkan undangan.
7. Tester membuka URL publik.
8. Tester atau responden lain mengirim RSVP.
9. Tester melihat respons pada dashboard.

## 14.2 Data yang Dikumpulkan

- Kode atau identitas singkat tester.
- Fitur yang dicoba.
- Keberhasilan atau kendala.
- Feedback kemudahan penggunaan.
- Bagian yang membingungkan.
- Saran perbaikan.
- Bukti pengujian berupa dokumentasi atau screenshot.

## 14.3 Contoh Pertanyaan Feedback

- Apakah proses membuat undangan mudah dipahami?
- Apakah pilihan template menarik?
- Apakah formulir mudah diisi?
- Apakah preview sesuai dengan informasi yang dimasukkan?
- Apakah tautan undangan mudah dibagikan?
- Apakah fitur RSVP mudah digunakan?
- Apa bagian yang perlu diperbaiki?

Hasil pengujian harus dianalisis dan ditindaklanjuti. Jangan mengarang
hasil atau testimoni pengguna.

---

# 15. GitHub dan Manajemen Proyek

Gunakan satu repository untuk seluruh source code.

## 15.1 Struktur Repository yang Disarankan

Struktur berikut adalah rancangan organisasi kode yang dapat disesuaikan
dengan FlightPHP Skeleton yang dipilih:

```text
invitara/
├── app/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   └── Views/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── assets/
│   ├── uploads/
│   ├── build/
│   └── index.php
├── resources/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── storage/
│   ├── logs/
│   └── cache/
├── tests/
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

Folder `vendor/` dan `node_modules/` dikelola oleh Composer/npm dan
tidak perlu dimasukkan ke Git.

## 15.2 Board GitHub Projects

Gunakan alur: - Backlog - In Progress - Review - Done

## 15.3 Contoh Issue

- `feat: implement user registration and login`
- `feat: create wedding template gallery`
- `feat: implement invitation form editor`
- `feat: render public invitation page`
- `feat: implement RSVP submission`
- `feat: create RSVP dashboard`
- `fix: validate invitation ownership`
- `test: cover invitation publishing flow`
- `docs: write setup instructions`

Setiap issue memiliki penanggung jawab dan kriteria selesai. Ketiga
anggota wajib menjadi collaborator dan kontribusinya dapat dibuktikan
melalui commit serta pull request.

---

# 16. Pembagian Tugas Tim dan Batas Tanggung Jawab

Bagian ini menjadi acuan pembagian pekerjaan tiga anggota kelompok.
Pembagian dibuat berdasarkan modul agar setiap anggota dapat
mengembangkan bagiannya secara paralel menggunakan vibe coding, tanpa
membuat AI mengubah atau mengambil alih modul anggota lain.

**Prinsip kerja:** setiap anggota bertanggung jawab atas modul utama
yang ditetapkan, tetapi seluruh anggota tetap wajib melakukan integrasi,
code review, pengujian, dokumentasi, dan membantu penyelesaian masalah
lintas modul.

## 16.1 Anggota 1 --- Backend, Database, dan Fondasi Sistem

**Fokus utama:** fondasi aplikasi, pengelolaan data, autentikasi,
keamanan, dan integrasi backend FlightPHP.

### Tanggung jawab

1. Menyiapkan project FlightPHP v3 dan konfigurasi Composer.
2. Mengatur konfigurasi environment melalui `.env` dan `.env.example`.
3. Menyiapkan koneksi MySQL menggunakan PDO.
4. Menentukan dan mengimplementasikan migration serta seeder database.
5. Membuat struktur model/repository untuk tabel utama:
   - `users`
   - `categories`
   - `templates`
   - `invitations`
   - `invitation_photos`
   - `rsvps`
6. Membuat konfigurasi Twig dan mekanisme rendering view.
7. Membuat route dan controller autentikasi.
8. Mengimplementasikan register, login, logout, session, dan proteksi
   route.
9. Membuat middleware autentikasi dan pemeriksaan role admin.
10. Menyediakan pemeriksaan kepemilikan data agar pengguna hanya dapat
    mengakses undangannya sendiri.
11. Menyediakan validasi server-side, CSRF protection, dan penanganan
    error.
12. Menyediakan kontrak fungsi/service atau repository yang digunakan
    anggota lain.
13. Meninjau keamanan query, session, upload, dan otorisasi sebelum
    integrasi.

### File/folder utama yang dikelola

- `config/`
- `routes/`
- `app/Controllers/AuthController.php`
- `app/Middleware/`
- `app/Models/` atau `app/Repositories/`
- `app/Services/` untuk layanan lintas modul
- `database/migrations/`
- `database/seeders/`
- konfigurasi bootstrap aplikasi dan koneksi PDO.

### Hasil kerja yang harus diserahkan

- Project FlightPHP dapat dijalankan.
- Koneksi MySQL berfungsi.
- Migration dan seeder dapat dijalankan.
- Register, login, dan logout berfungsi.
- Route privat terlindungi.
- Pemeriksaan kepemilikan undangan tersedia.
- Panduan konfigurasi `.env.example` dan setup lokal.
- Dokumentasi struktur tabel dan kontrak backend.

### Batas tanggung jawab

Anggota 1 tidak mengambil alih desain visual template wedding atau
implementasi detail halaman editor. Anggota 1 menyediakan data, route,
validasi, dan kontrak backend yang dibutuhkan modul tersebut.

---

## 16.2 Anggota 2 --- Frontend, UI/UX, Tabler, Twig, dan Vite

**Fokus utama:** tampilan website, konsistensi desain, layout dashboard,
landing page, dan pengalaman pengguna.

### Tanggung jawab

1. Menyiapkan aset frontend menggunakan Vite.
2. Mengintegrasikan Tabler UI dan Bootstrap 5.
3. Membuat layout Twig:
   - `layouts/dashboard.twig`
   - `layouts/public.twig`
   - `layouts/auth.twig`
4. Membuat komponen antarmuka yang dapat digunakan ulang, seperti
   navbar, sidebar, alert, modal, tombol, dan form field.
5. Membuat landing page Invitara.
6. Membuat halaman login dan register dari sisi tampilan, lalu
   menghubungkannya dengan route autentikasi.
7. Membuat tampilan dashboard pengguna.
8. Membuat galeri template dan kartu preview template.
9. Membuat tampilan form-based editor berdasarkan field yang ditetapkan
   PRD.
10. Membuat tampilan preview undangan.
11. Membuat desain halaman undangan publik yang elegan dan responsif,
    terpisah dari tampilan dashboard Tabler.
12. Menangani responsivitas desktop dan mobile.
13. Mengelola CSS khusus setiap template wedding.
14. Mengoptimalkan aset gambar dan hasil build Vite.
15. Memastikan seluruh view menggunakan data dinamis dari
    controller/Twig, bukan data contoh yang ditanam permanen.

### File/folder utama yang dikelola

- `resources/css/`
- `resources/js/`
- `public/build/` sebagai hasil build, bukan tempat utama mengedit
  source.
- `app/Views/layouts/`
- `app/Views/components/`
- `app/Views/landing/`
- `app/Views/auth/`
- `app/Views/dashboard/`
- `app/Views/templates/`
- bagian presentasi pada `app/Views/editor/` dan
  `app/Views/invitations/`.

### Hasil kerja yang harus diserahkan

- Vite dapat menjalankan development dan production build.
- Layout dashboard berbasis Tabler berfungsi.
- Landing page dan halaman autentikasi tampil baik.
- Dashboard responsif.
- Galeri template dapat menampilkan data dari backend.
- Form editor dan preview memiliki tampilan yang konsisten.
- Minimal tiga desain template wedding responsif.
- Halaman undangan publik memiliki tampilan yang layak digunakan.

### Batas tanggung jawab

Anggota 2 tidak mengubah skema database, aturan bisnis, query PDO, atau
mekanisme autentikasi tanpa koordinasi dengan Anggota 1. Anggota 2
bertanggung jawab pada presentasi UI dan interaksi browser.

---

## 16.3 Anggota 3 --- Modul Undangan, Publikasi, RSVP, dan Pengujian Fitur

**Fokus utama:** alur bisnis utama Invitara dari pembuatan undangan
sampai penerimaan RSVP.

### Tanggung jawab

1. Mengimplementasikan proses CRUD undangan menggunakan kontrak backend
   yang disepakati.
2. Menghubungkan pemilihan template dengan pembuatan undangan.
3. Mengimplementasikan form-based editor dari sisi alur fitur:
   - menerima input informasi pasangan;
   - menerima informasi tanggal, waktu, dan lokasi;
   - menerima deskripsi dan pesan penutup;
   - menerima pilihan template dan warna;
   - memproses penyimpanan draft.
4. Mengimplementasikan preview berdasarkan data undangan dan template.
5. Mengimplementasikan upload foto sampul dan foto galeri melalui
   service upload yang telah disepakati.
6. Mengimplementasikan pembuatan slug unik.
7. Mengimplementasikan publish dan unpublish undangan.
8. Mengimplementasikan halaman undangan publik berdasarkan slug.
9. Mengimplementasikan form RSVP untuk tamu.
10. Mengimplementasikan daftar RSVP dan ringkasan statistik pada
    dashboard.
11. Menguji aturan bisnis, termasuk status draft/published/unpublished
    dan akses pemilik.
12. Membuat skenario pengujian fitur undangan dan RSVP.
13. Mencatat bug dan mengoordinasikan perbaikan dengan anggota yang
    memiliki modul terkait.
14. Mendukung persiapan pengujian 10 pengguna awal dan dokumentasi hasil
    feedback.

### File/folder utama yang dikelola

- `app/Controllers/InvitationController.php`
- `app/Controllers/TemplateController.php` bila terkait alur pemilihan
  template
- `app/Controllers/RSVPController.php`
- `app/Services/InvitationService.php`
- `app/Services/UploadService.php` dengan koordinasi Anggota 1
- view khusus editor, preview, dan undangan publik sesuai koordinasi
  dengan Anggota 2
- `tests/` untuk pengujian alur undangan dan RSVP.

### Hasil kerja yang harus diserahkan

- CRUD undangan berfungsi.
- Pengguna dapat memilih template dan menyimpan draft.
- Preview menampilkan data yang benar.
- Publish menghasilkan URL unik.
- Halaman publik hanya menampilkan undangan yang dipublikasikan.
- Unpublish menonaktifkan akses publik.
- Tamu dapat mengirim RSVP.
- Pemilik dapat melihat daftar dan ringkasan RSVP.
- Skenario pengujian dan catatan bug tersedia.

### Batas tanggung jawab

Anggota 3 tidak membuat koneksi database atau sistem autentikasi
sendiri. Gunakan repository, service, middleware, dan route convention
yang disepakati bersama Anggota 1. Untuk tampilan, gunakan layout dan
komponen yang disediakan Anggota 2.

---

## 16.4 Kontrak Integrasi Antaranggota

Agar pekerjaan paralel tidak saling bertabrakan, tim wajib menyepakati
kontrak berikut sebelum pengembangan fitur berjalan:

---

  Area                    Pemilik utama           Kontrak untuk anggota
                                                  lain

---

  Konfigurasi FlightPHP,  Anggota 1               Anggota lain
  PDO, session                                    menggunakan konfigurasi
                                                  yang tersedia

  Skema database dan      Anggota 1               Perubahan kolom harus
  migration                                       melalui diskusi dan
                                                  migration

  Autentikasi dan         Anggota 1               Anggota 2 membuat
  middleware                                      tampilan; Anggota 3
                                                  memakai middleware

  Layout Twig dan         Anggota 2               Anggota 1 dan 3
  komponen UI                                     menggunakan nama
                                                  layout/komponen yang
                                                  disepakati

  Aset Tabler, Bootstrap, Anggota 2               Tidak memasang
  Vite                                            duplikasi library tanpa
                                                  koordinasi

  CRUD dan alur undangan  Anggota 3               Memakai
                                                  repository/service dan
                                                  skema database yang
                                                  disepakati

  Publish, slug, halaman  Anggota 3               Menggunakan layout
  publik                                          publik dari Anggota 2

  RSVP dan statistik RSVP Anggota 3               Menggunakan tabel dan
                                                  query/service yang
                                                  disepakati dengan
                                                  Anggota 1

Pengujian integrasi     Semua anggota           Setiap anggota menguji
                                                  modulnya dan alur
                                                  lintas modul
--------------------------------------------------------------

### Aturan integrasi

1. Jangan mengubah nama route, nama kolom database, nama view, atau
   bentuk data yang dipakai anggota lain tanpa memberi tahu tim.
2. Perubahan database harus dibuat melalui migration, bukan mengubah
   database lokal saja.
3. Controller tidak boleh menaruh seluruh query dan logika bisnis
   sekaligus; gunakan service/repository secara proporsional.
4. Semua operasi data privat wajib memeriksa user yang sedang login dan
   kepemilikan record di backend.
5. View Twig menerima data dari controller; view tidak melakukan query
   database.
6. JavaScript hanya menangani interaksi antarmuka; validasi dan aturan
   bisnis tetap diperiksa di backend.
7. Setiap anggota mengerjakan branch fitur masing-masing dan mengajukan
   Pull Request sebelum digabung ke branch utama.
8. Konflik file atau perubahan lintas modul harus dibicarakan sebelum
   diselesaikan, bukan ditimpa secara sepihak.

### Kontrak data minimal

Gunakan nama field dan status yang konsisten dengan rancangan database
pada bagian 9 PRD.

- Identitas pengguna aktif tersedia melalui mekanisme session yang
  disepakati.
- Undangan memiliki `user_id`, `template_id`, `slug`, dan `status`.
- Status undangan menggunakan `draft`, `published`, atau
  `unpublished`.
- Status RSVP menggunakan `attending` atau `not_attending`.
- Template memiliki `view_key` yang dipetakan ke view Twig yang sudah
  terdaftar; jangan menerima nama file view bebas dari input pengguna.
- Semua waktu acara disimpan dan ditampilkan dengan aturan zona waktu
  yang konsisten.

---

## 16.5 Panduan Vibe Coding untuk Setiap Anggota

Setiap anggota boleh menggunakan AI coding assistant, tetapi AI harus
diberi konteks peran dan batas pekerjaannya. Jangan meminta AI mengubah
seluruh project jika tugasnya hanya satu modul.

### Prompt dasar Anggota 1

```text
Anda bertindak sebagai Backend Developer untuk proyek Invitara.

Stack:
- FlightPHP v3
- PHP 8.3+
- MySQL
- PDO
- Twig
- PHP Session
- Composer

Tanggung jawab saya:
Backend, database, autentikasi, middleware, konfigurasi, dan keamanan.

Kerjakan hanya modul backend yang menjadi tanggung jawab saya.
Ikuti struktur project dan kontrak integrasi dalam PRD.
Jangan mengubah desain UI, template wedding, atau file frontend milik anggota lain
kecuali diperlukan dan telah dijelaskan terlebih dahulu.

Sebelum mengubah file:
1. Periksa struktur project yang sudah ada.
2. Jelaskan file yang akan dibuat atau diubah.
3. Pastikan query menggunakan PDO prepared statements.
4. Pastikan validasi dan otorisasi dilakukan di server.
5. Jangan membuat skema database berbeda dari PRD tanpa mengusulkan migration.
6. Sertakan cara menjalankan dan menguji hasilnya.
```

### Prompt dasar Anggota 2

```text
Anda bertindak sebagai Frontend Developer untuk proyek Invitara.

Stack:
- Twig untuk server-side rendering
- Tabler UI untuk dashboard
- Bootstrap 5
- HTML5 dan CSS3
- Vanilla JavaScript ES6+
- Vite

Tanggung jawab saya:
Landing page, layout Twig, dashboard UI, galeri template,
form editor dari sisi tampilan, preview UI, dan desain halaman undangan publik.

Kerjakan hanya tampilan dan interaksi frontend.
Gunakan data dinamis yang dikirim controller melalui Twig.
Jangan membuat koneksi database, query PDO, autentikasi, atau mengubah aturan bisnis.
Jangan mengganti stack dengan React, Vue, atau SPA.

Sebelum mengubah file:
1. Periksa struktur view dan aset yang sudah ada.
2. Gunakan layout dan komponen yang konsisten.
3. Pisahkan layout dashboard Tabler dari desain undangan publik.
4. Pastikan tampilan responsif.
5. Jangan menanam data contoh permanen jika data seharusnya berasal dari backend.
6. Sertakan cara menjalankan Vite dan menguji tampilan.
```

### Prompt dasar Anggota 3

```text
Anda bertindak sebagai Feature Developer untuk modul undangan dan RSVP proyek Invitara.

Stack:
- FlightPHP v3
- PHP 8.3+
- PDO/MySQL
- Twig SSR
- Tabler dan Bootstrap 5
- Vanilla JavaScript

Tanggung jawab saya:
CRUD undangan, pemilihan template, form editor dari sisi alur fitur,
preview, upload foto melalui service yang disepakati,
slug, publish/unpublish, halaman publik, RSVP, dan ringkasan RSVP.

Gunakan koneksi database, middleware, repository/service, layout Twig,
dan aturan database yang sudah disediakan tim.
Jangan membuat autentikasi atau koneksi database kedua.
Jangan mengubah skema database tanpa migration dan persetujuan tim.

Sebelum mengubah file:
1. Periksa implementasi backend dan view yang sudah tersedia.
2. Jelaskan file yang akan dibuat atau diubah.
3. Pastikan semua operasi privat memeriksa kepemilikan undangan.
4. Pastikan halaman publik hanya menampilkan undangan published.
5. Pastikan RSVP divalidasi di server.
6. Sertakan skenario pengujian untuk setiap alur.
```

### Instruksi bersama untuk semua AI coding assistant

- PRD ini adalah acuan kebutuhan produk.
- Periksa kode yang sudah ada sebelum membuat perubahan.
- Jangan menghapus atau menimpa pekerjaan anggota lain.
- Jangan membuat library, tabel, route, atau field duplikat.
- Jika kebutuhan belum jelas, ajukan pertanyaan sebelum mengubah
  arsitektur.
- Laporkan file yang dibuat/diubah dan alasan perubahannya.
- Jalankan pemeriksaan atau pengujian yang relevan.
- Jangan mengklaim fitur selesai jika belum dijalankan dan diuji.
- Jangan memasukkan `.env`, kredensial, atau data pribadi ke
  repository.

---

## 16.6 Definition of Done per Anggota

---

  Anggota                             Selesai jika

---

  Anggota 1                           FlightPHP berjalan, PDO terhubung,
                                      migration tersedia, autentikasi dan
                                      middleware teruji, kontrak backend
                                      terdokumentasi

  Anggota 2                           Layout Twig, Tabler, Bootstrap, dan
                                      Vite berjalan; halaman utama,
                                      dashboard, template, dan halaman
                                      publik responsif

Anggota 3                           Alur buat/edit/publish undangan,
                                      URL publik, RSVP, dan ringkasan
                                      RSVP berjalan dengan otorisasi yang
                                      benar
-------------------------------------------

Penyelesaian modul oleh satu anggota belum berarti produk selesai. MVP
baru dianggap selesai setelah ketiga modul terintegrasi dan alur
end-to-end berhasil diuji.

---

# 16.7 Strategi Branch dan Pull Request GitHub

Bagian ini menetapkan alur kontribusi kode, integrasi, dan pemeriksaan
perubahan untuk seluruh anggota tim. Tujuannya adalah memastikan bahwa
setiap anggota berkontribusi secara nyata melalui akun GitHub masing-masing,
sementara perubahan kode tetap terkontrol dan dapat ditinjau sebelum
digabungkan.

## Tujuan

1. Memastikan kontribusi setiap anggota dapat ditelusuri melalui commit dan
   Pull Request (PR).
2. Mengurangi risiko konflik kode dan perubahan yang merusak fitur anggota
   lain.
3. Menetapkan proses review sebelum kode masuk ke branch integrasi.
4. Menjaga branch `main` tetap stabil dan siap digunakan untuk demonstrasi
   atau rilis.

## Struktur branch

Repository Invitara menggunakan pola `main` - `develop` - feature branches.

```text
main
└── develop
    ├── feature/backend-database
    ├── feature/frontend-ui
    └── feature/invitation-rsvp
```

| Branch                       | Fungsi                                    | Aturan                                                                                    |
| ---------------------------- | ----------------------------------------- | ----------------------------------------------------------------------------------------- |
| `main`                     | Versi stabil untuk rilis atau demonstrasi | Tidak menerima commit langsung dari anggota; perubahan masuk melalui PR dari `develop`. |
| `develop`                  | Branch integrasi seluruh fitur            | Fitur anggota digabungkan ke sini setelah review dan pengujian.                           |
| `feature/backend-database` | Pekerjaan Anggota 1                       | Khusus backend, database, autentikasi, middleware, dan fondasi sistem.                    |
| `feature/frontend-ui`      | Pekerjaan Anggota 2                       | Khusus tampilan, layout Twig, Tabler, Bootstrap, Vite, dan aset frontend.                 |
| `feature/invitation-rsvp`  | Pekerjaan Anggota 3                       | Khusus alur undangan, publikasi, RSVP, dan pengujian fitur bisnis.                        |

Branch fitur dapat dibuat lebih kecil berdasarkan satu pekerjaan tertentu,
misalnya `feature/user-authentication` atau `feature/rsvp-dashboard`.
Jika menggunakan branch turunan seperti ini, setiap PR tetap harus memiliki
target integrasi yang jelas dan tidak boleh mengubah branch utama secara
langsung.

## Aturan kontribusi setiap anggota

Setiap anggota wajib:

1. Menggunakan akun GitHub pribadi yang telah ditambahkan sebagai
   collaborator repository.
2. Mengatur `user.name` dan `user.email` Git sesuai identitas yang terhubung
   dengan akun GitHub.
3. Membuat branch untuk pekerjaan yang menjadi tanggung jawabnya.
4. Melakukan commit secara berkala dengan pesan yang menjelaskan perubahan.
5. Mengirim branch ke repository remote dan membuat PR.
6. Menanggapi komentar reviewer serta memperbaiki temuan sebelum merge.
7. Tidak membuat commit atas nama anggota lain atau melakukan perubahan
   semu hanya untuk menambah jumlah kontribusi.

Contoh pengaturan identitas Git:

```bash
git config --global user.name "Nama 


Anggota"
git config --global user.email "email-yang-terhubung-ke-github"
```

Kontribusi yang ditampilkan GitHub bergantung pada identitas commit dan
aturan atribusi GitHub. Commit yang masuk ke riwayat repository merupakan
bukti kontribusi kode yang paling mudah ditelusuri. Issue, diskusi, dan
review juga mendokumentasikan partisipasi, tetapi tidak selalu dihitung
sebagai kontribusi pada grafik atau daftar Contributors.

## Rencana Pull Request

PR dibuat berdasarkan unit pekerjaan yang cukup kecil untuk diperiksa.
Daftar berikut merupakan rencana awal dan dapat disesuaikan dengan
perkembangan implementasi.

| Urutan | Pembuat PR                       | Ruang lingkup                                                                   | Target      |
| ------ | -------------------------------- | ------------------------------------------------------------------------------- | ----------- |
| PR 1   | Anggota 1                        | Inisialisasi FlightPHP, Composer, konfigurasi environment, dan struktur backend | `develop` |
| PR 2   | Anggota 2                        | Setup Vite, Tabler, Bootstrap, Twig layout, dan komponen UI dasar               | `develop` |
| PR 3   | Anggota 1                        | Koneksi PDO/MySQL, migration, seeder, autentikasi, session, dan middleware      | `develop` |
| PR 4   | Anggota 2                        | Landing page, halaman login/register, dan kerangka dashboard                    | `develop` |
| PR 5   | Anggota 3                        | Alur CRUD undangan, pemilihan template, dan penyimpanan draft                   | `develop` |
| PR 6   | Anggota 2                        | Galeri template, antarmuka editor form, preview, dan tampilan undangan publik   | `develop` |
| PR 7   | Anggota 3                        | Validasi data undangan, slug unik, publish, dan unpublish                       | `develop` |
| PR 8   | Anggota 3                        | Form RSVP, daftar RSVP, dan ringkasan data tamu                                 | `develop` |
| PR 9   | Anggota 1                        | Pemeriksaan keamanan, validasi backend, CSRF, dan ownership check               | `develop` |
| PR 10  | Tim                              | Perbaikan integrasi lintas modul dan bug hasil pengujian                        | `develop` |
| PR 11  | Ketua atau anggota yang ditunjuk | Pemeriksaan rilis dan penggabungan versi stabil                                 | `main`    |

Satu PR tidak harus sama dengan satu anggota atau satu tahap besar. Jika
pekerjaan terlalu luas, pecah menjadi beberapa PR yang lebih kecil. Jika
beberapa perubahan sangat erat dan aman diperiksa bersama, perubahan dapat
digabung dalam satu PR dengan persetujuan tim.

## Alur pembuatan Pull Request

1. Sinkronkan branch kerja dengan branch integrasi yang terbaru.
2. Buat atau pindah ke branch fitur yang sesuai.
3. Implementasikan pekerjaan sesuai batas tanggung jawab.
4. Jalankan pengujian yang relevan dan periksa perubahan file.
5. Commit perubahan dengan pesan yang deskriptif.
6. Push branch ke GitHub.
7. Buat PR dengan target `develop`, kecuali PR rilis yang menargetkan `main`.
8. Isi deskripsi PR menggunakan format standar tim.
9. Tetapkan reviewer yang bukan pembuat PR.
10. Perbaiki komentar reviewer dan pastikan pemeriksaan berhasil.
11. Merge PR setelah persetujuan dan pemeriksaan terpenuhi.
12. Hapus branch fitur setelah merge jika tidak lagi diperlukan.

Contoh perintah Git:

```bash
git checkout develop
git pull origin develop
git checkout -b feature/nama-fitur

# Kerjakan perubahan, kemudian:
git add .
git commit -m "feat: jelaskan fitur yang ditambahkan"
git push -u origin feature/nama-fitur
```

Setelah PR di-merge, anggota memperbarui branch lokal:

```bash
git checkout develop
git pull origin develop
```

Jika anggota menggunakan branch fitur yang dipakai bersama dalam waktu
lama, lakukan sinkronisasi secara berkala agar perbedaan kode tidak
menumpuk.

## Pembagian reviewer

Gunakan review silang agar perubahan diperiksa oleh anggota lain.

| Pembuat PR           | Reviewer utama yang disarankan          | Fokus pemeriksaan                                                  |
| -------------------- | --------------------------------------- | ------------------------------------------------------------------ |
| Anggota 1            | Anggota 2 atau Anggota 3                | Struktur backend, keamanan, integrasi, dan dampak terhadap fitur.  |
| Anggota 2            | Anggota 1 atau Anggota 3                | Konsistensi UI, responsivitas, integrasi Twig, dan alur tampilan.  |
| Anggota 3            | Anggota 1 atau Anggota 2                | Alur bisnis undangan, publikasi, RSVP, dan integrasi antarmuka.    |
| PR rilis ke `main` | Ketua tim dan minimal satu anggota lain | Stabilitas aplikasi, hasil pengujian, dan kelengkapan dokumentasi. |

Reviewer harus membaca perubahan dan memberikan persetujuan hanya jika
perubahan dapat dipahami serta tidak memiliki masalah penting yang belum
diselesaikan. Pembuat PR tidak boleh menyetujui PR miliknya sendiri.

## Standar judul dan deskripsi PR

Gunakan awalan judul yang konsisten:

- `feat:` untuk fitur baru.
- `fix:` untuk perbaikan bug.
- `refactor:` untuk perbaikan struktur kode tanpa mengubah perilaku utama.
- `docs:` untuk dokumentasi.
- `test:` untuk penambahan atau perubahan pengujian.
- `chore:` untuk konfigurasi dan pemeliharaan proyek.

Template deskripsi PR:

```markdown
## Deskripsi
Jelaskan tujuan dan ringkasan perubahan.

## Tugas yang diselesaikan
- [ ] Pekerjaan pertama
- [ ] Pekerjaan kedua

## Perubahan teknis
- File atau komponen yang ditambahkan:
- File yang dimodifikasi:
- Integrasi yang terdampak:

## Pengujian
- [ ] Fitur diuji secara lokal
- [ ] Tidak ada error pada alur terkait
- [ ] Hasil pengujian dijelaskan

## Catatan untuk Reviewer
Tuliskan bagian yang perlu diperhatikan reviewer.
```

## Kriteria PR siap di-merge

PR dapat di-merge jika:

- Ruang lingkup PR sesuai dengan tugas yang disepakati.
- Kode mengikuti stack dan arsitektur dalam PRD.
- Tidak mengandung kredensial, token, file `.env`, atau data rahasia.
- Validasi backend dan pemeriksaan kepemilikan tetap diterapkan untuk
  operasi privat.
- Perubahan database disertai migration yang sesuai.
- Tidak menghapus atau menimpa pekerjaan anggota lain tanpa koordinasi.
- Pengujian yang relevan telah dilakukan dan hasilnya dicatat.
- Tidak terdapat konflik merge yang belum diselesaikan.
- Minimal satu reviewer selain pembuat PR telah menyetujui perubahan.
- Pemeriksaan otomatis yang dikonfigurasi repository berhasil, jika tersedia.

## Pengaturan repository yang disarankan

Jika fitur dan paket GitHub yang digunakan mendukungnya, aktifkan
perlindungan branch untuk `main` dan `develop` dengan aturan berikut:

1. Larang direct push ke `main`.
2. Wajibkan Pull Request sebelum merge ke `main`.
3. Wajibkan minimal satu approval untuk PR ke `main`.
4. Wajibkan status checks berhasil jika workflow pengujian otomatis telah
   tersedia.
5. Gunakan aturan serupa pada `develop` jika tim sudah siap menerapkan
   integrasi yang lebih ketat.

Jika pengaturan perlindungan branch tidak tersedia pada paket repository,
tim tetap harus menjalankan aturan review dan merge secara disiplin.

## Dokumentasi kontribusi

Untuk kebutuhan evaluasi proyek, simpan bukti proses pengembangan berupa:

- Riwayat commit masing-masing anggota.
- Daftar Pull Request beserta pembuat dan reviewer.
- GitHub Issues yang terhubung dengan pekerjaan.
- GitHub Projects yang menunjukkan status pekerjaan.
- Screenshot atau tautan PR penting sebagai bukti dokumentasi.
- Catatan pengujian dan hasil integrasi.

Jumlah commit atau PR tidak boleh dijadikan satu-satunya ukuran kontribusi.
Penilaian internal tim perlu mempertimbangkan kualitas kode, penyelesaian
tugas, pengujian, review, dan kolaborasi.

## Definition of Done GitHub

- [ ] Repository memiliki branch `main` dan `develop`.
- [ ] Ketiga anggota terdaftar sebagai collaborator menggunakan akun masing-masing.
- [ ] Setiap anggota memiliki pekerjaan dan branch yang jelas.
- [ ] Setiap fitur utama diajukan melalui Pull Request.
- [ ] PR memiliki deskripsi, hasil pengujian, dan reviewer.
- [ ] Perubahan diintegrasikan ke `develop` setelah review.
- [ ] Versi stabil digabungkan ke `main` melalui PR rilis.
- [ ] Riwayat commit dan PR dapat digunakan untuk menelusuri kontribusi tim.

# 17. Tahapan Pengembangan

## Tahap 1 --- Persiapan

- Membuat repository GitHub.
- Menambahkan tiga collaborator.
- Membuat GitHub Project dan issues.
- Menyiapkan FlightPHP v3.
- Memasang Twig dan mengatur view rendering.
- Menyiapkan Tabler, Bootstrap, JavaScript, dan Vite.
- Menentukan struktur database.

## Tahap 2 --- Fondasi Aplikasi

- Mengatur konfigurasi environment.
- Menghubungkan PDO ke MySQL.
- Membuat migration dan model/repository.
- Mengimplementasikan register, login, dan logout.
- Membuat middleware autentikasi dan otorisasi.
- Membuat layout dashboard dan landing page.

## Tahap 3 --- Template dan Editor

- Membuat data template.
- Membuat galeri template.
- Membuat form-based editor.
- Mengimplementasikan upload foto.
- Membuat preview Twig.

## Tahap 4 --- Publikasi dan RSVP

- Membuat slug unik.
- Membuat halaman undangan publik.
- Mengimplementasikan publish dan unpublish.
- Membuat form RSVP.
- Membuat dashboard RSVP.

## Tahap 5 --- Pengujian dan Deployment

- Menjalankan pengujian fungsional.
- Memperbaiki bug.
- Menguji tampilan mobile.
- Menjalankan Vite build untuk production.
- Deploy website.
- Memastikan database production berfungsi.

## Tahap 6 --- Marketing dan Early Users

- Membuat identitas visual dan akun media sosial.
- Menyiapkan minimal 10 konten.
- Mengajak minimal 10 tester menggunakan aplikasi.
- Mengumpulkan feedback.
- Melakukan perbaikan berdasarkan hasil pengujian.

## Tahap 7 --- Dokumentasi dan Presentasi

- Menyiapkan product overview.
- Mendokumentasikan teknologi dan fitur.
- Menyertakan bukti GitHub.
- Menyusun hasil pemasaran dan pengujian.
- Menyiapkan live demo maksimal 15 menit sesuai panduan tugas.

---

# 18. Risiko dan Mitigasi

---

  Risiko                             Mitigasi

---

  Editor terlalu kompleks            Tetap gunakan form-based editor

  Template kurang menarik            Fokus pada kualitas minimal tiga
                                     template

  Bentrok slug                       Gunakan unique constraint dan
                                     validasi

  Upload file gagal                  Validasi ukuran/tipe dan tampilkan
                                     pesan kesalahan

  Data antar pengguna bocor          Terapkan ownership check di
                                     backend

  RSVP spam                          Validasi input dan pembatasan
                                     request sederhana

  Waktu pengembangan terbatas        Prioritaskan fitur MVP

  Hosting tidak kompatibel           Uji kompatibilitas PHP, FlightPHP,
                                     ekstensi PHP, dan MySQL sebelum
                                     deployment

  Aset Vite tidak ditemukan          Pastikan production build dan
                                     manifest ikut dideploy

Integrasi Twig bermasalah          Uji konfigurasi loader, path view,
                                     dan escaping sejak awal
------------------------------------------------------------

---

# 19. Batasan dan Asumsi

1. Invitara berfokus pada undangan pernikahan untuk MVP.
2. Sistem menggunakan form-based editor, bukan editor drag-and-drop.
3. Template disediakan dan dikelola oleh aplikasi.
4. Pengguna mengisi data sendiri.
5. RSVP merupakan konfirmasi yang diinput penerima undangan, bukan
   verifikasi kehadiran fisik.
6. Pengiriman undangan dilakukan dengan membagikan tautan, bukan
   pengiriman massal otomatis.
7. Google Maps dibuka melalui tautan yang dimasukkan pengguna;
   integrasi API peta tidak wajib.
8. Pembayaran online tidak termasuk MVP.
9. Penyimpanan foto menggunakan storage aplikasi/hosting pada tahap
   awal.
10. Fitur premium dan langganan dapat dipertimbangkan setelah MVP
    berjalan.
11. SSR digunakan untuk dashboard dan halaman undangan publik.
12. JavaScript digunakan untuk interaksi tambahan; frontend tidak
    dibangun sebagai SPA pada MVP.

---

# 20. Definition of Done

Fitur dianggap selesai jika: - Kebutuhan fungsional berjalan. - Validasi
frontend dan backend tersedia sesuai kebutuhan. - Otorisasi diterapkan
pada data privat. - Tampilan dapat digunakan pada desktop dan mobile. -
Tidak ada bug kritis yang diketahui pada alur utama. - Perubahan masuk
ke repository melalui proses review tim. - Dokumentasi penggunaan atau
konfigurasi diperbarui jika diperlukan.

MVP Invitara dianggap selesai jika pengguna dapat mendaftar, membuat
undangan pernikahan menggunakan template, mengisi form, melihat preview,
menerbitkan tautan publik, dan menerima RSVP yang dapat dipantau melalui
dashboard.

---

# 21. Kesesuaian dengan Panduan Tugas Proyek SaaS

---

  Komponen Panduan                   Rencana pada Invitara

---

  Website SaaS --- 30%               Landing page, autentikasi,
                                     dashboard, database, CRUD, fitur
                                     inti, responsif, deployment

  GitHub & Project Management ---    Repository, tiga collaborator,
  20%                                GitHub Projects, Issues, commit

  Media Sosial & Content Marketing   Akun produk dan minimal 10 konten
  --- 20%

  Early User / Tester --- 20%        Minimal 10 pengguna yang mencoba,
                                     feedback, analisis, dan perbaikan

Presentasi & Dokumentasi --- 10%   Product overview, dokumentasi
                                     teknis, bukti GitHub, marketing,
                                     tester, dan live demo
----------------------------------------------------------

PRD ini merupakan spesifikasi produk untuk memandu pengembangan, bukan
bukti bahwa persyaratan sudah terpenuhi. Setiap komponen dalam panduan
tugas tetap harus dikerjakan dan didokumentasikan oleh kelompok.

---

# 22. Ringkasan Produk

---

  Aspek                              Keputusan

---

  Nama                               Invitara

  Tagline                            Create Your Moment, Share Your
                                     Invitation.

  Jenis                              Wedding Digital Invitation Maker

  Model                              SaaS berbasis website

  Target                             Masyarakat umum

  Editor                             Form-based editor

  Rendering                          Server-Side Rendering (SSR)

  Backend                            FlightPHP v3 dan PHP

  Template engine                    Twig

  UI dashboard                       Tabler

  CSS framework                      Bootstrap 5

  JavaScript                         Vanilla JS ES6+

  Build tool                         Vite

  Database                           MySQL

  Database access                    PDO

  Authentication                     PHP Session

Fitur inti                         Template, form editor, preview,
                                     publikasi URL, halaman undangan
                                     publik, RSVP, dashboard
------------------------------------------------------------

**Prioritas utama:** Bangun alur pembuatan undangan sampai RSVP yang
benar-benar berfungsi sebelum menambahkan fitur lanjutan.
