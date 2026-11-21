# CONTRACTS.md --- Kontrak Integrasi Backend Invitara

Dokumen ini disusun oleh **Anggota 1 (Ketua - Backend & Fondasi)** sebagai acuan baku dan kontrak kerja sama bagi **Anggota 2 (Frontend & UI/UX)** dan **Anggota 3 (Feature Undangan & RSVP)** sesuai PRD Bab 16.

---

## 1. Kontrak Autentikasi & Session

### Struktur Session Pengguna (`$_SESSION['user']`)
Saat pengguna berhasil login, data session tersedia dalam format berikut:
```php
[
    'id'    => 1,                       // integer
    'name'  => 'Budi Santoso',          // string
    'email' => 'budi@invitara.test',    // string
    'role'  => 'user',                  // 'user' | 'admin'
]
```

### Helper Autentikasi PHP & Twig:
| Helper PHP / Fungsi Twig | Return Type | Keterangan |
|---|---|---|
| `auth_user()` | `?array` | Mengembalikan array pengguna aktif atau `null` jika belum login |
| `is_authenticated()` | `bool` | `true` jika pengguna sudah login |
| `is_admin()` | `bool` | `true` jika pengguna login dan memiliki `role === 'admin'` |
| `url(string $path = '')` | `string` | Menghasilkan URL absolut sesuai konfigurasi `APP_URL` |
| `asset(string $path)` | `string` | Menghasilkan URL absolut untuk file aset di `public/` |

---

## 2. Keamanan CSRF (Wajib pada Semua Form Mutasi)

Semua permintaan HTTP bertipe `POST`, `PUT`, `DELETE`, atau `PATCH` wajib menyertakan token CSRF yang valid melalui `CsrfMiddleware`.

### Pada Template Twig:
Sisipkan tag `{{ csrf_field() }}` di dalam tag `<form>`:
```twig
<form action="{{ url('/dashboard/invitations/create') }}" method="POST">
    {{ csrf_field() }}
    <!-- field formulir lainnya -->
</form>
```

### Pada Permintaan AJAX / Fetch (JavaScript):
Dapatkan token via meta tag atau helper Twig:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```
Kirimkan di header HTTP request:
```javascript
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(payload)
});
```

---

## 3. Flash Messages & Input Repopulation (Old Input)

### Menampilkan Notifikasi di Twig:
Komponen notifikasi siap pakai berada di `app/Views/components/alerts.twig`. Cukup panggil:
```twig
{% include "components/alerts.twig" %}
```
Atau manual melalui Twig:
```twig
{% if has_flash('success') %}
    {% for msg in flash('success') %}
        <div class="alert alert-success">{{ msg }}</div>
    {% endfor %}
{% endif %}
```

### Mengisi Kembali Input Formulir (`old`):
Jika validasi gagal, controller menyimpan data input ke sesi sebelumnya:
```twig
<input type="text" name="partner_one_name" value="{{ old('partner_one_name', invitation.partner_one_name ?? '') }}">
```

---

## 4. Repositories Siap Pakai (Data Access Layer)

Anggota 3 dan Anggota 2 dipersilakan langsung menggunakan repositori berikut tanpa perlu menulis query PDO mentah:

### A. `App\Repositories\InvitationRepository`
- `findById(int $id): ?array`
- `findBySlug(string $slug): ?array`
- `getPublishedBySlug(string $slug): ?array` (Hanya yang berstatus `'published'`)
- `getByUserId(int $userId): array`
- `countByUserId(int $userId): array` (Mengembalikan `['total', 'drafts', 'published', 'unpublished', 'total_rsvps']`)
- `isSlugAvailable(string $slug, ?int $ignoreId = null): bool`
- `checkOwnership(int $invitationId, int $userId): bool`
- `create(array $data): int` (Mengembalikan ID undangan yang baru dibuat)
- `update(int $id, array $data): bool`
- `delete(int $id): bool` (Menghapus undangan beserta relasi foto dan RSVP secara otomatis melalui ON DELETE CASCADE)

### B. `App\Repositories\TemplateRepository`
- `allActive(): array` (Daftar semua template aktif beserta data kategorinya)
- `all(): array`
- `findById(int $id): ?array`
- `findBySlug(string $slug): ?array`
- `create(array $data): int`
- `update(int $id, array $data): bool`

### C. `App\Repositories\RSVPRepository`
- `getByInvitationId(int $invitationId): array` (Daftar RSVP diurutkan dari yang terbaru)
- `getSummaryByInvitationId(int $invitationId): array`
  Mengembalikan:
  ```php
  [
      'total_responses'        => int,
      'total_attending'        => int,
      'total_not_attending'    => int,
      'total_attending_guests' => int, // Akumulasi guest_count dari tamu yang attending
  ]
  ```
- `create(array $data): int`
- `delete(int $id): bool`
- `countByInvitation(int $invitationId): int`

### D. `App\Repositories\InvitationPhotoRepository`
- `getByInvitationId(int $invitationId): array`
- `create(array $data): int`
- `delete(int $id): bool`
- `updateOrder(int $id, int $sortOrder): bool`

---

## 5. Validasi Server-Side (`App\Services\Validator`)

Gunakan `Validator` pada controller untuk memastikan semua data aman dan valid:
```php
use App\Services\Validator;

$validator = Validator::make(Flight::request()->data->getData(), [
    'title'            => 'required|min:3|max:150',
    'slug'             => 'required|slug|unique:invitations,slug',
    'partner_one_name' => 'required|max:100',
    'partner_two_name' => 'required|max:100',
    'event_date'       => 'required|date',
    'event_time'       => 'time',
    'theme_color'      => 'in:rose,emerald,gold,classic',
]);

if ($validator->fails()) {
    Flash::set('error', $validator->firstError());
    Flight::redirect('/dashboard/invitations/create');
    return;
}
```

---

## 6. Proteksi Hak Akses & Kepemilikan Data (AC-11)

### Melindungi Route Undangan dengan `OwnershipMiddleware`
Setiap route dashboard yang memanipulasi undangan milik pengguna wajib memasang `OwnershipMiddleware`:
```php
use App\Middleware\AuthMiddleware;
use App\Middleware\OwnershipMiddleware;

Flight::route('GET /dashboard/invitations/@id:[0-9]+/edit', [InvitationController::class, 'edit'])
    ->addMiddleware([new AuthMiddleware(), new OwnershipMiddleware()]);
```
Jika pengguna yang sedang login mencoba mengakses ID undangan milik orang lain, sistem otomatis menghentikan request dengan status **403 Forbidden**.

---

## 7. Status & Aturan Nilai Enum Database

- **Status Undangan (`invitations.status`):**
  - `'draft'` (Bawaan saat pertama kali disimpan)
  - `'published'` (Dapat diakses publik melalui URL `/i/{slug}`)
  - `'unpublished'` (Dinonaktifkan oleh pemilik)
- **Status RSVP (`rsvps.attendance_status`):**
  - `'attending'` (Hadir; kolom `guest_count` bernilai minimal 1)
  - `'not_attending'` (Tidak hadir; kolom `guest_count` otomatis 0)
- **Template `view_key`:**
  - `'minimal'` -> memetakan ke template Twig `invitations/public/minimal.twig`
  - `'floral'`  -> memetakan ke template Twig `invitations/public/floral.twig`
  - `'modern'`  -> memetakan ke template Twig `invitations/public/modern.twig`

---

## 8. Layanan Unggah Foto (`App\Services\UploadService`)

Digunakan oleh **Anggota 3** untuk menangani upload foto sampul (`covers`) dan foto galeri (`gallery`):

```php
use App\Services\UploadService;

$uploader = new UploadService();

try {
    // Unggah file dari $_FILES['cover_photo'] ke subfolder public/uploads/covers/
    $result = $uploader->upload($_FILES['cover_photo'], 'covers');
    
    // Hasil yang dikembalikan:
    // $result['path']     -> '/uploads/covers/a1b2c3d4e5f6...jpg' (Simpan path ini ke database)
    // $result['filename'] -> 'a1b2c3d4e5f6...jpg'
    // $result['size']     -> integer
    // $result['mime']     -> 'image/jpeg'
} catch (\RuntimeException $e) {
    Flash::set('error', $e->getMessage());
    Flight::redirect('/dashboard/invitations/create');
    return;
}

// Untuk menghapus foto lama saat diganti atau saat undangan dihapus:
$uploader->delete($invitation['cover_photo_path']);
```

### Fitur Keamanan Upload:
- Validasi MIME Type asli lewat PHP `finfo` (hanya `image/jpeg`, `image/png`, `image/webp`).
- Batas ukuran file 2MB (`UPLOAD_MAX_SIZE`).
- Sanitasi nama file menggunakan 32-karakter hash heksadesimal acak.
- Folder `public/uploads/` diproteksi `.htaccess` dari eksekusi script PHP/CGI.

---

## 9. Pengaturan Profil Pengguna (`PRD Bab 6.11`)

Dikelola oleh **Anggota 1**:
- Rute Tampilan: `GET /dashboard/profile`
- Update Nama: `POST /dashboard/profile` (membutuhkan field `name`)
- Ubah Password: `POST /dashboard/profile/password` (membutuhkan `current_password`, `password`, `password_confirmation`)
- Seluruh form dilindungi `CsrfMiddleware` dan `AuthMiddleware`.
