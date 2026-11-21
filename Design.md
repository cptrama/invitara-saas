# DESIGN — INVITARA

## Panduan Desain Tampilan Web (Landing, Dashboard, Undangan Publik)

**Basis:** PRD Invitara v1.1
**Pemilik dokumen:** Anggota 2 (Frontend, UI/UX, Tabler, Twig, Vite)
**Stack tampilan:** Twig (SSR), Tabler UI (dashboard), Bootstrap 5, Vanilla JS ES6+, Vite
**Status:** Draf untuk disepakati tim

---

## 0. Cara Memakai Dokumen Ini

Dokumen ini adalah acuan tampilan. Untuk kebutuhan fitur, aturan bisnis, dan skema database, PRD tetap menjadi sumber utama.

- Anggota 2 memakai dokumen ini sebagai spesifikasi kerja.
- Anggota 1 dan 3 memakainya untuk mengetahui data apa yang dibutuhkan view dan nama komponen yang harus dipakai.
- Jika memakai AI coding assistant, sertakan **Bagian 2 (Aturan anti-AI-slop)**, **Bagian 3 (Fondasi visual)**, dan **Bagian 10 (Copywriting)** dalam konteks prompt. Ketiganya yang paling sering dilanggar oleh output AI.
- Bagian 14 berisi hal-hal dalam PRD yang belum jelas atau bertabrakan dan perlu diputuskan tim sebelum desain dikunci.

Semua angka kontras warna dalam dokumen ini sudah dihitung dengan rumus WCAG 2.1, bukan perkiraan. Target ukuran dan performa adalah sasaran awal yang harus diukur ulang setelah build, bukan klaim hasil.

---

## 1. Arah Desain

### 1.1 Konsep: undangan adalah benda, dashboard adalah bengkelnya

Produk ini menghasilkan satu hal: sebuah undangan yang dibuka orang di ponsel. Maka desainnya dibagi dua dunia yang sengaja berbeda.

| Dunia | Sifat | Perumpamaan |
|---|---|---|
| Landing, autentikasi, dashboard | Tenang, rata, jelas, hemat dekorasi | Meja kerja percetakan undangan yang rapi |
| Undangan publik (3 template) | Hangat, bertekstur tipis, bertipografi kuat | Kertas undangan yang sudah jadi |

Satu aturan yang menyatukan keduanya: **benda berbentuk kertas** (pratinjau undangan, thumbnail template, bingkai foto) memakai sudut hampir siku (2px) dan bayangan tipis berwarna hijau tua. **Antarmuka** (tombol, input, panel) datar dan berbatas garis. Dengan begitu, di mana pun pengguna melihat kertas, ia tahu itu hasil karyanya, bukan bagian dari aplikasi.

### 1.2 Kepribadian

Ramah, jelas, dan jujur seperti tukang cetak undangan yang menjelaskan apa yang akan Anda dapatkan. Bukan galeri butik pengantin yang mewah, bukan startup yang menjual "revolusi".

Target pengguna PRD adalah masyarakat umum yang tidak harus bisa mendesain. Asumsi desain (belum diuji, harus divalidasi pada 10 tester): sebagian pengguna dibantu orang tua atau keluarga, memakai ponsel Android kelas menengah, dan membuka tautan dari WhatsApp. Karena itu teks tidak boleh kecil, tombol tidak boleh sempit, dan bahasa tidak boleh berbelit.

### 1.3 Satu hal yang dibuat tak terlupakan

Keberanian visual dipusatkan di satu tempat. Di Invitara tempatnya adalah **hero landing page**: pengunjung mengetik nama dua mempelai di formulir kecil dan melihat kertas undangan di sebelahnya terisi langsung. Itu inti produk (form-based editor dengan preview) yang didemokan, bukan dijelaskan.

Semua hal lain di landing dan dashboard sengaja dibuat tenang agar satu momen ini terbaca.

### 1.4 Dasar visual: dari bunga dan daun pernikahan Indonesia

Palet dan ornamen diambil dari yang memang ada di pernikahan Indonesia, bukan dari pola template pernikahan luar negeri.

- **Melati**: rangkaian melati pengantin. Menjadi warna latar putih kehijauan.
- **Daun**: hijau tua dari daun pembungkus dan hiasan janur. Menjadi warna tinta dan warna utama.
- **Kunyit**: kuning kunyit/janur kuning. Menjadi satu-satunya aksen hangat.

Ornamen (varian Floral) digambar sebagai garis tipis melati dan janur. Tidak memakai mawar cat air, hati, cincin, atau kilau.

### 1.5 Yang sengaja tidak dipakai

Pink blush dengan emas gradien, huruf sambung tipis (script) untuk nama, latar krem dengan aksen terakota, gradien dekoratif, glassmorphism, blob/orb, foto stok pasangan, ilustrasi 3D, emoji sebagai ikon, musik otomatis, layar "Buka Undangan" yang menghalangi isi, dan hujan kelopak atau konfeti. Alasan lengkap ada di Bagian 2.

---

## 2. Aturan Anti-AI-Slop

Desain yang dihasilkan AI cenderung mengumpul di beberapa pola. Pola itu sah untuk beberapa proyek, tetapi bila muncul tanpa alasan, halaman terasa seperti template. Tabel di bawah adalah daftar larangan beserta penggantinya. Aturan ini berlaku juga untuk kode yang ditulis dengan bantuan AI.

### 2.1 Visual dan layout

| Jangan | Mengapa | Gantinya di Invitara |
|---|---|---|
| Latar krem hangat + serif kontras tinggi + aksen terakota | Kombinasi default hasil AI | Melati (putih kehijauan) + Young Serif + Daun Tua dan Kunyit |
| Latar hitam + satu aksen hijau neon atau merah-oranye | Default AI lain | Tidak ada mode gelap di aplikasi. Varian gelap hanya ada di template undangan tertentu dan berwarna biru malam atau hijau sirih |
| Pink blush + emas gradien + kilau | Klise pernikahan, tidak spesifik | Palet melati/daun; emas hanya sebagai aksen datar pada varian tertentu |
| Semua blok menjadi kartu sama: radius sama, bayangan abu yang sama | Tidak ada hierarki | Panel datar berbatas 1px. Radius mengikuti peran (Bagian 3.5). Bayangan hanya untuk lapisan yang melayang |
| Grid fitur 3x2: ikon di lingkaran pastel + judul + dua baris teks | Template SaaS paling umum | Dua baris "gambar produk nyata + penjelasan", tanpa ikon dekoratif |
| Hero dengan angka besar + tiga statistik + gradien | Default hero | Hero demo hidup (Bagian 5.2) |
| Statistik, logo klien, atau testimoni karangan | PRD melarang mengarang testimoni dan hasil | Tidak ada. Isi hanya setelah ada data tester nyata, dengan izin mereka |
| Setiap bagian muncul dengan fade-and-slide-up; semua kartu naik saat hover | Gerak tanpa fungsi | Gerak hanya untuk satu momen hero, cover undangan, dan respons terhadap aksi pengguna |
| Semua bagian rata tengah | Membuat teks sulit dibaca | Rata kiri untuk membaca dan formulir. Rata tengah hanya untuk cover dan penutup template yang memang klasik |
| Gradien dekoratif, blob, mesh, partikel | Hiasan tanpa informasi | Warna datar. Tekstur hanya dari tipografi dan ornamen garis di template Floral |
| Foto stok pasangan, ilustrasi orang tersenyum | Generik dan tidak mewakili pengguna | Hanya render nyata dari template. Tanpa foto, cover memakai tipografi |
| Ikon emoji (hati, cincin, kilau) | Tidak konsisten antar perangkat | Satu set ikon garis (Tabler Icons, SVG) |
| Tampilan bawaan Tabler (biru #206bc4, ikon dan warna default) | Dashboard terlihat seperti demo Tabler | Override token (Bagian 11.3) |

### 2.2 Tipografi

| Jangan | Gantinya |
|---|---|
| Label HURUF KAPITAL ber-letter-spacing di atas heading (eyebrow) | Tidak ada label di atas heading. Heading cukup berdiri sendiri |
| Memberi satu kata di headline warna atau miring berbeda | Seluruh headline satu perlakuan |
| Penomoran 01 / 02 / 03 pada konten yang bukan urutan | Nomor hanya untuk urutan sungguhan (langkah cara kerja, langkah membuat undangan) |
| Font monospace untuk label kecil | Hanken Grotesk dengan angka tabular |
| Font default (Inter, Poppins, Playfair, Cormorant, Montserrat) | Font pilihan di Bagian 3.2 |
| Huruf script tipis untuk nama mempelai | Serif berkarakter dan italik. Alasan: terbaca di layar ponsel kecil dan di bawah sinar matahari |
| Mengubah nama menjadi huruf kapital semua | Nama ditampilkan persis seperti diketik (menghormati gelar dan "bin/binti") |
| Tanda panah "→" di akhir setiap tombol atau tautan | Teks tombol saja. Ikon hanya bila menjelaskan aksi (misalnya ikon salin) |
| Teks gabungan dengan titik tengah "A · B · C" atau "KATA — fragmen" | Kalimat biasa atau daftar |

### 2.3 Copy

| Jangan | Gantinya |
|---|---|
| "Wujudkan momen impian Anda", "hari bahagia tak terlupakan", "solusi all-in-one", "revolusi", "powerful", "seamless" | Kalimat yang menyebut apa yang terjadi: "Isi formulir, lihat undangannya, bagikan tautannya." |
| "Selamat datang di Invitara!" disertai emoji | Langsung ke tugas: "Halo, Dewi" atau judul halaman |
| Tombol "Submit", "Kirim", "OK" | Kata kerja + objek: "Simpan draf", "Terbitkan", "Kirim konfirmasi" |
| "Oops! Terjadi kesalahan 😢" | "Foto belum terunggah. Periksa koneksi Anda, lalu coba lagi." |
| Placeholder "Lorem ipsum", "John Doe", nama fiktif tertanam di view | Data dari controller. Bila kosong, tampilkan keadaan kosong yang menyuruh bertindak |

### 2.4 Tiga uji sebelum PR

1. **Uji tukar subjek.** Ganti kata "undangan" dengan "webinar" atau "toko online" di halaman. Bila desain masih pas tanpa diubah, desain itu terlalu generik.
2. **Uji tutup logo.** Tutup logo dan nama produk. Apakah halaman masih terasa seperti Invitara (melati, daun, kertas) atau seperti template Bootstrap?
3. **Uji lepas satu hiasan.** Pilih satu hiasan (bayangan, garis, ikon, animasi), hapus, dan lihat apakah ada informasi yang hilang. Bila tidak ada, biarkan terhapus.

---

## 3. Fondasi Visual

### 3.1 Warna

#### Warna merek (landing, autentikasi, dashboard)

| Token | Hex | Peran |
|---|---|---|
| `--melati` | `#F2F5EE` | Latar halaman |
| `--kertas` | `#FFFFFF` | Permukaan panel, input, modal |
| `--cekung` | `#E8EDE3` | Area tenggelam: header tabel, input readonly |
| `--daun-tua` | `#17382D` | Teks utama, tombol utama, tinta |
| `--daun` | `#2C6A52` | Hover tombol utama, tautan, fokus |
| `--daun-muda` | `#DCE8D9` | Item nav aktif, status "Terbit", pilihan terpilih |
| `--kunyit` | `#E0A22A` | Aksen hangat: tanda merek, fokus di latar gelap |
| `--kunyit-muda` | `#F7E8BE` | Status "Draf", sorotan lembut |
| `--batu` | `#566860` | Teks sekunder, label bantu |
| `--garis` | `#D3DCD0` | Pembatas dekoratif panel dan tabel |
| `--tepi-input` | `#7C8C83` | Border input dan kontrol (wajib kontras minimal 3:1) |
| `--bata` | `#A93A2C` | Error dan aksi destruktif |
| `--bata-muda` | `#F6DDD8` | Latar pesan error |
| `--netral-chip` | `#E7ECE4` | Status "Tidak terbit" |

Teks utama memakai Daun Tua, bukan hitam atau abu.

#### Kontras (dihitung, WCAG 2.1)

| Pasangan | Rasio | Lolos |
|---|---|---|
| Daun Tua di atas Melati | 11,63 | AAA |
| Daun Tua di atas putih | 12,80 | AAA |
| Daun di atas putih | 6,38 | AA |
| Putih di atas Daun (tombol hover) | 6,38 | AA |
| Batu di atas Melati | 5,38 | AA |
| Batu di atas putih | 5,92 | AA |
| Bata di atas putih | 6,31 | AA |
| Putih di atas Bata (tombol hapus) | 6,31 | AA |
| Daun Tua di atas Kunyit Muda | 10,51 | AAA |
| Daun Tua di atas Daun Muda | 10,11 | AAA |
| Batu di atas Netral Chip | 4,94 | AA |
| Bata di atas Bata Muda | 4,88 | AA |
| Kunyit di atas Daun Tua | 5,71 | AA |
| Tepi Input di atas putih | 3,54 | lolos 3:1 untuk komponen UI |

`--garis` (1,28:1) hanya untuk pembatas dekoratif. Jangan pernah dipakai sebagai batas input atau satu-satunya penanda status.

#### Pemetaan status

| Status (database) | Label tampilan | Warna chip | Ikon |
|---|---|---|---|
| `draft` | Draf | Kunyit Muda, teks Daun Tua | pensil |
| `published` | Terbit | Daun Muda, teks Daun Tua | globe/tautan |
| `unpublished` | Tidak terbit | Netral Chip, teks Batu | mata dicoret |
| `attending` | Hadir | Daun Muda, teks Daun Tua | centang |
| `not_attending` | Tidak hadir | Netral Chip, teks Batu | silang |

Status selalu **ikon + teks**, tidak pernah warna saja.

### 3.2 Tipografi

| Peran | Font | Dipakai untuk | Paket (Fontsource) |
|---|---|---|---|
| Tampil | **Young Serif** 400 | Judul besar landing, judul halaman autentikasi, judul keadaan kosong | `@fontsource/young-serif` |
| UI | **Hanken Grotesk** (variabel, 400–700) | Semua teks antarmuka, formulir, tabel, badan teks | `@fontsource-variable/hanken-grotesk` |

Font template undangan didefinisikan di Bagian 8 (Libre Caslon Text, Sorts Mill Goudy, Archivo). Nama dan versi paket harus diverifikasi saat instalasi karena PRD mewajibkan versi dikunci.

Alasan pilihan: Young Serif kokoh dan ramah, bukan serif "butik" bergaris tipis, sesuai kepribadian untuk masyarakat umum. Hanken Grotesk terbaca baik pada ukuran kecil dan tidak terlalu sering muncul di desain hasil AI.

#### Skala (rem, basis 16px)

| Token | Ukuran | Pemakaian |
|---|---|---|
| `--t-xs` | 0,8125 (13px) | Catatan kecil, keterangan tabel |
| `--t-sm` | 0,875 (14px) | Label bantu, teks sekunder |
| `--t-base` | 1 (16px) | Badan teks, input (16px mencegah zoom otomatis iOS) |
| `--t-lg` | 1,125 | Teks pembuka |
| `--t-xl` | 1,375 | Judul bagian |
| `--t-2xl` | 1,75 | Judul halaman dashboard |
| `--t-3xl` | 2,25 | Judul halaman autentikasi |
| `--t-4xl` | `clamp(2.25rem, 1.2rem + 4.5vw, 3.75rem)` | Headline hero |

#### Aturan tipografi

- Tinggi baris: sans 1,5 untuk badan; heading 1,2–1,25; serif untuk badan 1,6.
- Panjang baris maksimal 65 karakter (`max-width: 65ch`).
- Letter-spacing: heading tampil −0,01em; selainnya 0. Tidak ada tracking lebar.
- Angka di tabel dan ringkasan: `font-variant-numeric: tabular-nums`.
- `text-wrap: balance` untuk heading, `text-wrap: pretty` untuk paragraf.
- Tidak ada `text-transform: uppercase` di mana pun.
- Teks interaktif minimal 14px; badan minimal 16px.

### 3.3 Jarak

Basis 4px: `4, 8, 12, 16, 24, 32, 48, 64, 96`.

| Konteks | Nilai |
|---|---|
| Padding konten dashboard | 24px desktop, 16px ponsel |
| Jarak antar bagian landing | 96px desktop, 64px ponsel |
| Jarak antar field formulir | 20px |
| Jarak antar bagian undangan | Didefinisikan per template (Bagian 8) |

### 3.4 Perataan

- **Rata kiri** untuk landing, dashboard, formulir, tabel.
- **Rata tengah** hanya untuk cover dan penutup template Floral, dan keadaan kosong.
- Template Minimalis dan Modern rata kiri (sengaja berbeda dari kebiasaan undangan yang serba tengah).

### 3.5 Radius

Radius mengikuti **peran benda**, bukan satu nilai untuk semua.

| Token | Nilai | Dipakai untuk |
|---|---|---|
| `--r-kertas` | 2px | Pratinjau undangan, thumbnail template, foto di undangan |
| `--r-kontrol` | 8px | Tombol, input, select |
| `--r-panel` | 12px | Panel, modal, dropdown |
| `--r-bingkai-hp` | 32px | Bingkai ponsel di panel pratinjau |
| `--r-pil` | 999px | Chip status, avatar |

Dilarang memakai nilai radius di luar token ini.

### 3.6 Elevasi

Panel dan tabel **tidak punya bayangan**; dipisahkan oleh border 1px `--garis`. Bayangan hanya untuk benda yang melayang atau benda kertas. Warna bayangan adalah hijau tua, bukan abu.

| Token | Nilai | Dipakai untuk |
|---|---|---|
| `--bayang-kertas` | `0 1px 0 rgb(23 56 45 / .08), 0 12px 24px -12px rgb(23 56 45 / .30)` | Pratinjau undangan, thumbnail template |
| `--bayang-melayang` | `0 6px 20px -6px rgb(23 56 45 / .28)` | Dropdown, toast |
| `--bayang-modal` | `0 20px 48px -12px rgb(23 56 45 / .35)` | Modal |

### 3.7 Ikon

- Tabler Icons dalam bentuk **SVG inline** lewat macro Twig `icon('nama')`. Jangan memuat webfont ikon penuh.
- Garis 1,75px; ukuran 20px di navigasi, 16px sebaris dengan teks.
- Di navigasi, ikon selalu disertai label.
- Ikon tidak ditaruh dalam lingkaran berwarna sebagai dekorasi.

### 3.8 Gerak

| Gerak | Boleh | Durasi |
|---|---|---|
| Respons terhadap aksi (modal buka, toast, tombol salin berubah menjadi "Tautan disalin", formulir RSVP berubah menjadi pesan sukses) | Ya | 120–180ms, `ease-out` |
| Satu urutan saat halaman dimuat: cover undangan, dan hero landing | Ya, sekali | maks. 600ms total |
| Fade-up pada setiap bagian, hover-lift pada kartu, parallax, kelopak/kilau melayang, hitung mundur berdetik | Tidak | |

Wajib menghormati `prefers-reduced-motion: reduce` (semua gerak non-esensial mati).

---

## 4. Peta Antarmuka

| Area | Rute (PRD 10) | Dasar teknis | Karakter |
|---|---|---|---|
| Landing | `/` | Bootstrap 5 (grid) + CSS merek | Tenang, tipografi besar |
| Galeri template publik | `/templates`, `/templates/{slug}` | Bootstrap 5 + CSS merek | Kertas di atas latar Melati |
| Autentikasi | `/login`, `/register` | Bootstrap 5 + CSS merek | Satu kolom formulir |
| Dashboard | `/dashboard/*` | Tabler + override token | Datar, padat informasi, berborder |
| Editor dan pratinjau | `/dashboard/invitations/{id}/edit`, `.../preview` | Tabler + CSS editor | Mode fokus (Bagian 7.5) |
| Undangan publik | `/i/{slug}` | CSS khusus per template, **tanpa Tabler**, Bootstrap hanya bila perlu | Mengikuti template |

Rekomendasi performa: halaman undangan publik tidak memuat Bootstrap JS maupun Tabler. Entry Vite dipisah per area (Bagian 11.2).

---

## 5. Landing Page

Memenuhi PRD 6.1: nama dan deskripsi, contoh desain, langkah penggunaan, CTA "Buat Undangan", tautan login/register, responsif.

### 5.1 Struktur

```
┌──────────────────────────────────────────────────────────────┐
│ [tanda] Invitara                Template   Masuk  [Buat Undangan] │
├──────────────────────────────────────────────────────────────┤
│  Hero (2 kolom, rata kiri)                                   │
│  Isi formulir, lihat           ┌─ Coba langsung ──────────┐  │
│  undangannya, bagikan          │ Mempelai pertama [Andi ] │  │
│  tautannya.                    │ Mempelai kedua   [Siti ] │  │
│                                │ Tanggal      [14/02/2026]│  │
│  Invitara membantu ...         │     ┌──── kertas ────┐   │  │
│  [Buat Undangan] Lihat template│     │  Andi & Siti   │   │  │
│                                │     └────────────────┘   │  │
│                                └──────────────────────────┘  │
├──────────────────────────────────────────────────────────────┤
│  Cara kerja: 4 langkah berurutan, garis penghubung tipis      │
│  1 Pilih template  2 Isi formulir  3 Periksa & terbitkan  4 Bagikan │
├──────────────────────────────────────────────────────────────┤
│  Template: 3 thumbnail kertas dari database                  │
│  nama + satu kalimat + "Lihat contoh"                        │
├──────────────────────────────────────────────────────────────┤
│  Satu tautan untuk semua tamu   [gambar halaman publik]      │
│  RSVP terkumpul di satu tempat  [gambar tabel RSVP]          │
├──────────────────────────────────────────────────────────────┤
│  Buat undangan pertama Anda.   [Buat Undangan]               │
├──────────────────────────────────────────────────────────────┤
│  Invitara · Masuk · Daftar · Template      tagline merek     │
└──────────────────────────────────────────────────────────────┘
```

Tidak ada: harga, klaim "gratis", statistik, testimoni, logo klien, FAQ karangan. Harga dan paket berada di luar MVP (PRD 4.2); jangan mencantumkan sebelum diputuskan.

### 5.2 Hero demo hidup

- Tiga input (dua nama, satu tanggal) dengan nilai awal "Andi", "Siti", dan tanggal contoh. Nilai awal ini sengaja sesuai contoh slug PRD (`/i/andi-siti`).
- Di sebelahnya, pratinjau template Minimalis dalam bingkai ponsel. Teks nama dan tanggal berubah **saat diketik** (JavaScript murni, sekitar 40 baris, tanpa kirim data ke server).
- Tanggal diformat Indonesia: "Sabtu, 14 Februari 2026".
- Tanpa JavaScript, pratinjau tetap tampil statis dengan nilai awal.
- Keterangan kecil di bawah demo: "Hanya contoh. Tidak ada data yang disimpan."
- Gerak: pergantian teks langsung atau crossfade 120ms, tanpa efek lain.
- Di ponsel: formulir di atas, pratinjau di bawah; keduanya terlihat tanpa perlu menggulir jauh.

### 5.3 Copy hero (usulan final)

- **Headline:** Isi formulir, lihat undangannya, bagikan tautannya.
- **Paragraf:** Invitara membantu Anda membuat undangan pernikahan digital tanpa perlu bisa mendesain. Tamu membuka tautan dan mengisi RSVP, Anda melihat jawabannya di satu halaman.
- **CTA utama:** Buat Undangan
- **CTA sekunder (tautan teks):** Lihat template

### 5.4 Cara kerja

Urutan ini nyata, jadi penomoran boleh dipakai. Angka memakai Young Serif; garis tipis penghubung antar langkah menyatakan urutan (vertikal di ponsel, horizontal di desktop).

1. **Pilih template.** Tiga pilihan, masing-masing punya beberapa warna.
2. **Isi formulir.** Nama, tanggal, lokasi, foto. Simpan sebagai draf kapan saja.
3. **Periksa pratinjau, lalu terbitkan.** Anda mendapat satu tautan.
4. **Bagikan tautan dan pantau RSVP.** Jawaban tamu muncul di dashboard.

### 5.5 Bagian template

- Tiga kartu kertas (rasio 3:4, memotong bagian cover), data dari tabel `templates` (`name`, `thumbnail_path`, `description`, `is_active`). Tidak ditanam permanen di view.
- Satu tombol: "Lihat contoh" ke `/templates/{slug}`.
- Kartu tidak mengangkat saat hover. Penanda interaktif cukup garis bawah pada tautan dan outline fokus.

### 5.6 Identitas visual

- **Wordmark:** "Invitara" dalam Young Serif, warna Daun Tua.
- **Tanda:** persegi kertas (radius 2px), garis 2px Daun Tua, lipatan amplop berbentuk V di dalamnya, satu titik segel Kunyit. Dipakai sebagai favicon dan avatar media sosial.
- **Tagline merek:** "Create Your Moment, Share Your Invitation." (dipertahankan dari PRD, dipakai di footer dan meta, bukan di tubuh antarmuka yang berbahasa Indonesia).

---

## 6. Halaman Autentikasi

### 6.1 Layout

```
Desktop (≥992px)                          Ponsel
┌───────────────────┬─────────────────┐   ┌─────────────────┐
│ [tanda] Invitara  │  Daun Tua       │   │ [tanda] Invitara│
│                   │  ┌─kertas────┐  │   │                 │
│ Masuk             │  │ pratinjau │  │   │ Masuk           │
│ Email   [      ]  │  │ Minimalis │  │   │ Email   [     ] │
│ Kata sandi [   ]  │  │ terpotong │  │   │ Kata sandi [  ] │
│ [Masuk]           │  └───────────┘  │   │ [Masuk]         │
│ Belum punya akun? │  "Ini yang akan │   │ Belum punya...  │
│ Daftar            │   Anda buat."   │   └─────────────────┘
└───────────────────┴─────────────────┘
```

Panel kanan hanya tampil di desktop dan menunjukkan hasil nyata, bukan ilustrasi generik. Di ponsel hanya formulir.

### 6.2 Aturan

- Label di atas input, bukan placeholder sebagai label.
- Tombol tampil-sembunyi kata sandi (ikon + teks aksesibel "Tampilkan kata sandi").
- Error di bawah field, dengan ikon + teks, terhubung lewat `aria-describedby`. Bila ada lebih dari satu error, tampilkan ringkasan di atas formulir dan pindahkan fokus ke sana.
- Pesan login gagal tidak mengungkap apakah email terdaftar: "Email atau kata sandi salah."
- Tautan "Lupa kata sandi" **tidak ditampilkan** sampai fitur reset tersedia (PRD 6.2 menyebutnya opsional).
- Judul: "Masuk" dan "Buat akun". Tombol memakai kata kerja yang sama dengan judul.

---

## 7. Dashboard Pengguna (Tabler)

### 7.1 Layout dan navigasi

- Tabler `navbar-vertical` di kiri (248px) pada layar ≥992px. Di bawah itu menjadi bilah atas dengan drawer.
- Item navigasi memetakan PRD 10.3 ke bahasa Indonesia (PRD 12.1 mewajibkan label Bahasa Indonesia; lihat Bagian 14 butir 1).

| PRD 10.3 | Label tampilan | Rute |
|---|---|---|
| Overview | Ringkasan | `/dashboard` |
| My Invitations | Undangan Saya | `/dashboard/invitations` |
| Create Invitation | Buat Undangan | `/dashboard/invitations/create` |
| Templates | Template | pilih template (alur buat) |
| Profile | Profil | `/dashboard/profile` |
| Logout | Keluar | aksi POST |

- Item aktif: latar Daun Muda, teks Daun Tua, penanda 3px di tepi kiri.
- Grup "Admin" (kelola kategori dan template, pantau penggunaan) hanya dirender untuk role `admin`, dan tetap dilindungi middleware di backend.

### 7.2 Ringkasan

Memenuhi PRD 6.3: jumlah undangan, draf, terbit, total respons RSVP, undangan terbaru, tombol buat undangan.

```
Halo, Dewi                                         [Buat Undangan]

┌──────────────────────────────────────────────────────────────┐
│  3 undangan   │   1 draf   │   2 terbit   │   47 respons RSVP │
└──────────────────────────────────────────────────────────────┘

Undangan terbaru
┌──────────────────────────────────────────────────────────────┐
│ [kertas] Andi & Siti    [Terbit]   14 Feb 2026   32 respons  │
│ [kertas] Rina & Bayu    [Draf]     02 Mar 2026   0 respons   │
└──────────────────────────────────────────────────────────────┘
Lihat semua undangan
```

- Empat angka berada dalam **satu panel** dengan pembatas vertikal, bukan empat kartu terpisah. Angka memakai Hanken Grotesk 600, 28px, tabular. Label di bawah angka, `--t-sm`, warna Batu.
- "Respons RSVP" adalah jumlah entri, bukan jumlah orang. Jumlah orang yang hadir ditampilkan di halaman RSVP (Bagian 7.8).
- Pengguna baru tanpa undangan melihat keadaan kosong (Bagian 9.6) dengan tiga langkah, bukan panel angka nol.

### 7.3 Daftar undangan

- **Desktop:** tabel dengan kolom Undangan (thumbnail kertas kecil + nama pasangan + judul), Status (chip), Tanggal acara, RSVP (jumlah respons), Aksi.
- **Ponsel:** tabel berubah menjadi daftar baris bertumpuk. Tidak ada tabel yang tergulir ke samping.
- Saring status dengan tab sederhana: Semua, Draf, Terbit, Tidak terbit (parameter query, dirender di server).
- Aksi baris:
  - Selalu: Edit, Pratinjau, Lihat RSVP.
  - Bila terbit: Salin tautan, Batalkan penerbitan.
  - Bila draf atau tidak terbit: Terbitkan.
  - Menu "Lainnya": Hapus (destruktif, di dalam menu, dengan modal konfirmasi).
- Paginasi (PRD 12.5). Urutan terbaru di atas.

### 7.4 Pilih template

- Grid kartu kertas, 3 kolom desktop, 2 tablet, 1 ponsel.
- Setiap kartu: thumbnail 3:4, nama, satu kalimat deskripsi, tombol "Lihat contoh" (tab baru ke `/templates/{slug}`) dan "Pakai template ini".
- Template dengan `is_active = false` tidak ditampilkan di pilihan baru (PRD 6.4). Undangan lama yang sudah memakainya tetap dapat dirender.

### 7.5 Editor (mode fokus)

Pada halaman editor, sidebar utama disembunyikan agar ruang cukup. Bilah atas berisi: tautan "Undangan Saya", nama pasangan, chip status, lalu tombol **Pratinjau**, **Simpan draf**, dan **Terbitkan**.

```
┌ ‹ Undangan Saya   Andi & Siti [Draf]    [Pratinjau] [Simpan draf] [Terbitkan] ┐
├────────────────────────────────────────────────────────────────────────────────┤
│ Pasangan  Acara  Lokasi  Tambahan  Tampilan        ← tab jangkar, lengket       │
│                                                                                  │
│ ┌ Form (maks. 680px) ────────────────────┐   ┌ Pratinjau (lengket, 380px) ┐     │
│ │ Pasangan                               │   │   ╭────── HP ──────╮       │     │
│ │ Nama mempelai pertama                  │   │   │  kertas undangan│       │     │
│ │ [Andi                               ]  │   │   │                 │       │     │
│ │ Nama mempelai kedua                    │   │   ╰─────────────────╯       │     │
│ │ [Siti                               ]  │   │  Pratinjau diperbarui       │     │
│ │ Foto utama (opsional)  [Unggah foto]   │   │  saat Anda menyimpan        │     │
│ └────────────────────────────────────────┘   └─────────────────────────────┘     │
└────────────────────────────────────────────────────────────────────────────────┘
```

Ponsel: dua tab segmented di atas, **Isi** dan **Pratinjau**; tombol aksi ada di bilah bawah yang lengket.

#### Bagian formulir (mengikuti PRD 6.5)

| Bagian | Field | Catatan |
|---|---|---|
| **Pasangan** | Nama mempelai pertama, nama mempelai kedua, Foto utama (opsional) | Foto: JPG/PNG/WebP, tampilkan batas ukuran yang disepakati |
| **Acara** | Judul acara, Tanggal, Waktu, Zona waktu, Pesan pembuka | Zona waktu berupa pilihan WIB, WITA, WIT |
| **Lokasi** | Nama tempat, Alamat, Tautan Google Maps (opsional) | Tombol "Uji tautan" membuka tab baru |
| **Tambahan** | Cerita singkat, Foto galeri, Kontak penyelenggara, Hitung mundur (aktif/nonaktif) | Semua opsional. Urutan foto galeri diatur tombol naik/turun, bukan drag-and-drop (di luar MVP) |
| **Tampilan** | Template, Warna, Teks penutup | Warna berupa swatch dari daftar yang diizinkan template (Bagian 8.6) |

#### Aturan formulir editor

- Field opsional diberi tulisan "(opsional)" pada label. Field tanpa tulisan itu wajib. Satu kalimat di atas formulir menjelaskannya: "Kolom tanpa tulisan opsional wajib diisi."
- Swatch warna: lingkaran berwarna + nama warna + tanda centang pada yang terpilih (bukan hanya cincin berwarna).
- Penyimpanan eksplisit (PRD 6.5: perubahan pada undangan terbit harus disimpan secara eksplisit).
- Indikator di bilah atas: titik Kunyit + "Belum disimpan" saat ada perubahan; "Tersimpan pukul 14.32" setelah simpan. Peringatan `beforeunload` bila ada perubahan belum disimpan (PRD 12.4: data tidak hilang saat berpindah halaman).
- Pratinjau di panel kanan memakai `<iframe>` ke rute pratinjau SSR (`?embed=1`), dimuat ulang setelah simpan. Peningkatan opsional: kirim nama, tanggal, dan lokasi lewat `postMessage` agar teks berubah saat diketik. Foto dan ganti template baru tampil setelah simpan.
- Unggah foto: tampilkan pratinjau kecil, nama berkas, ukuran, dan tombol "Ganti"/"Hapus". Progres unggah berupa teks "Mengunggah 62%" dan bilah tipis. Kegagalan memakai copy di Bagian 10.3.

### 7.6 Pratinjau penuh

- Rute `/dashboard/invitations/{id}/preview` menampilkan halaman sesungguhnya dalam lebar ponsel di tengah, dengan pita lengket di atas: "Pratinjau draf. Halaman ini hanya terlihat oleh Anda." + tombol "Kembali ke formulir".
- Pita bukan bagian markup template undangan; ia dibungkus terpisah agar tidak bocor ke halaman publik.
- Sertakan `noindex`.

### 7.7 Terbitkan

Pemicu: tombol **Terbitkan** membuka modal.

```
┌ Terbitkan undangan ─────────────────────────────────┐
│ Alamat undangan                                      │
│ {domain}/i/ [andi-siti                            ]  │
│ Alamat ini dipakai tamu untuk membuka undangan.      │
│                                                      │
│ Syarat terbit                                        │
│  ✓ Nama kedua mempelai                               │
│  ✓ Tanggal acara                                     │
│  ✗ Zona waktu            Pilih zona waktu            │
│                                                      │
│                       [Batal]  [Terbitkan]           │
└──────────────────────────────────────────────────────┘
```

- `{domain}` diambil dari konfigurasi aplikasi; jangan menanam domain contoh.
- Tombol Terbitkan **tidak dinonaktifkan tanpa penjelasan**. Bila syarat belum lengkap, daftar syarat menunjukkan yang kurang, tiap baris yang gagal adalah tautan yang menggulir ke field terkait, dan tombol menjadi `aria-disabled` dengan alasan terbaca.
- Konflik slug (PRD 6.7): "Alamat 'andi-siti' sudah dipakai. Coba 'andi-siti-2'?" dengan tombol "Pakai saran ini". Slug hanya huruf kecil, angka, dan tanda hubung.
- Setelah berhasil, modal berubah menjadi keadaan sukses:
  - Judul "Undangan sudah terbit."
  - Kolom tautan hanya-baca + tombol **Salin tautan** (berubah menjadi "Tautan disalin" 2 detik, `aria-live="polite"`).
  - Tombol "Bagikan lewat WhatsApp" (tautan `wa.me` dengan teks siap kirim yang bisa diubah pengguna) dan "Buka undangan". Ini hanya tautan berbagi, bukan WhatsApp Business API (di luar MVP).
- Batalkan penerbitan: modal konfirmasi, "Tamu yang membuka tautan akan melihat halaman 'Undangan tidak tersedia'. RSVP yang sudah masuk tetap tersimpan." Tombol "Batalkan penerbitan". Toast: "Penerbitan dibatalkan."

### 7.8 Manajemen RSVP

Memenuhi PRD 6.10.

```
‹ Undangan Saya
RSVP: Andi & Siti                                  [Salin tautan]

┌──────────────────────────────────────────────────────────────────┐
│  32 respons │  26 hadir  │  6 tidak hadir  │  58 orang akan hadir │
└──────────────────────────────────────────────────────────────────┘
Satu respons bisa mewakili lebih dari satu orang.

[Semua] [Hadir] [Tidak hadir]

Nama         Status        Jumlah   Pesan                Waktu          
Budi H.      [✓ Hadir]     2        Selamat ya!          14 Feb, 19.42   [Hapus]
```

- Empat ringkasan wajib: total respons, hadir, tidak hadir, total orang hadir.
- Pesan panjang dipotong 2 baris di tabel dan bisa dibuka penuh (di baris atau modal). Tidak dipotong dengan "..." tanpa jalan untuk membaca utuh.
- Hapus per baris memakai modal konfirmasi (PRD 6.9).
- Ponsel: baris menjadi blok bertumpuk (nama + chip status, jumlah, pesan, waktu).
- Waktu respons ditulis dalam zona waktu acara dengan singkatannya (misalnya "19.42 WIB").
- Keadaan kosong: "Belum ada respons. Bagikan tautan undangan agar tamu bisa mengisi RSVP." + tombol "Salin tautan".

### 7.9 Profil

Dua panel: **Data akun** (nama dapat diubah; email hanya tampil) dan **Ganti kata sandi** (kata sandi lama, baru, ulangi). Tombol "Keluar" terpisah di bawah. Tidak ada bagian lain.

### 7.10 Admin

Cukup sederhana (PRD 3.2): tabel template dengan thumbnail, nama, `view_key` (hanya-baca), dan saklar "Aktif". Memakai komponen dan gaya dashboard yang sama tanpa layout baru.

---

## 8. Halaman Undangan Publik

Dirender SSR dengan Twig, dibuka tanpa login, kebanyakan dari ponsel melalui tautan WhatsApp.

### 8.1 Prinsip

1. **Mobile-first.** Rancang pada 360px dan 412px. Di desktop, undangan tetap satu kolom maksimal 560px di tengah, dengan latar luar berwarna lebih gelap atau terang dari kolom (token `--inv-latar`).
2. **Tanpa gerbang.** Tidak ada layar "Buka Undangan", tidak ada musik otomatis. Isi langsung terbaca; data seluler tamu tidak dihabiskan.
3. **Yang kosong tidak ada.** Setiap bagian opsional dirender hanya bila datanya ada (PRD 8.10). Jarak antar bagian memakai `.inv-stack > * + * { margin-block-start: var(--inv-jarak) }` agar tidak ada lubang kosong.
4. **Nama apa adanya.** Tidak ada `uppercase`. Nama panjang membungkus ke baris berikutnya, tidak dipotong. Uji dengan nama 100 karakter (batas kolom).
5. **Ketaatan privasi.** Ucapan dan nama tamu **tidak** ditampilkan di halaman publik (PRD 12.3). Hanya pemilik yang melihatnya di dashboard.
6. **Satu momen gerak.** Cover memuat sekali (nama muncul bergantian, maks. 600ms). Sisanya diam.
7. **Footer kecil**: "Dibuat dengan Invitara" (13px, Batu/warna muted template, tautan ke landing).

### 8.2 Urutan bagian

Mengikuti PRD 6.8, dengan bagian opsional hanya muncul bila ada data:

1. **Sampul**: judul acara kecil, nama pasangan, foto utama (bila ada), tanggal, tautan "Konfirmasi kehadiran" yang melompat ke `#rsvp`.
2. **Pembuka**: pesan pembuka (`description`).
3. **Acara**: tanggal, waktu + singkatan zona (WIB/WITA/WIT), hitung mundur (bila aktif).
4. **Lokasi**: nama tempat, alamat, tombol "Buka di Google Maps", tombol "Salin alamat".
5. **Cerita** (opsional).
6. **Galeri** (opsional).
7. **Kontak penyelenggara** (opsional).
8. **RSVP** (`id="rsvp"`).
9. **Penutup**: `closing_message` dan nama pasangan.
10. Footer.

Hitung mundur: kalimat biasa seperti "42 hari lagi", dihitung di server sesuai zona waktu acara dan diperbarui JavaScript bila perlu. Tidak ada kotak jam berdetik. Pada hari acara: "Hari ini". Setelah acara: "Acara telah berlangsung".

Tombol peta hanya muncul bila `maps_url` lolos validasi (https dan domain Google Maps yang diizinkan), dengan `target="_blank" rel="noopener noreferrer"`.

Format tanggal dan waktu: "Sabtu, 14 Februari 2026" dan "10.00 WIB". Pemetaan zona: `Asia/Jakarta` → WIB, `Asia/Makassar` → WITA, `Asia/Jayapura` → WIT.

### 8.3 Template 1: Minimalis Elegan (`view_key: minimal`)

**Karakter:** lapang, rata kiri, tipografi sebagai satu-satunya hiasan. Baris rincian bergaris tipis berfungsi sebagai tabel informasi.

**Font:** Libre Caslon Text 400/400-italik untuk nama dan pembuka (`@fontsource/libre-caslon-text`); Hanken Grotesk untuk rincian.

```
┌──────────────────────────┐
│  Pernikahan              │  Hanken 15px, muted
│                          │
│  Andi                    │  Caslon 400, clamp(2.5rem, 12vw, 4rem)
│  & Siti                  │  tanda & miring, lebih kecil
│                          │
│  ┌──────────────────┐    │
│  │  foto 4:5        │    │  radius 2px. Tanpa foto: tanggal besar
│  └──────────────────┘    │  dalam angka Caslon sebagai visual cover
│  Sabtu, 14 Februari 2026 │
│  Konfirmasi kehadiran    │  tautan ke #rsvp
├──────────────────────────┤
│  Pesan pembuka dalam     │  Caslon 19/1.6, maks. 34ch
│  Caslon, rata kiri.      │
│                          │
│  Rincian                 │
│  ────────────────────    │
│  Hari       Sabtu        │  garis 1px = pemisah baris data
│  Tanggal    14 Feb 2026  │
│  Waktu      10.00 WIB    │
│  Tempat     Gedung ...   │
│  ────────────────────    │
```

**Aturan:** tanpa ornamen gambar; garis hanya untuk memisahkan baris data. Jarak antar bagian 64px.

### 8.4 Template 2: Floral Klasik (`view_key: floral`)

**Karakter:** simetris, rata tengah, formal. Ornamen berasal dari melati dan janur.

**Font:** Sorts Mill Goudy 400/400-italik (`@fontsource/sorts-mill-goudy`); Hanken Grotesk untuk teks kecil. **Tidak memakai font script.** Kesan kaligrafi datang dari italik Goudy.

```
┌──────────────────────────┐
│        [ranting melati]  │  SVG garis 1,25px, di atas cover
│                          │
│       Pernikahan         │
│                          │
│        Andi              │  Goudy 400, clamp(2.5rem, 11vw, 3.75rem)
│        & Siti            │  "&" italik
│                          │
│     ╭──────────╮         │
│     │  foto    │         │  bingkai lengkung (janur melengkung),
│     │  3:4     │         │  radius atas 999px, bawah 2px
│     ╰──────────╯         │
│   Sabtu, 14 Februari 2026│
├──────────── ❦ ───────────┤  pemisah: satu ranting melati kecil
```

**Aturan ornamen:**
- Garis 1,25px, warna `--inv-aksen`, gambar sendiri sebagai SVG inline.
- Maksimal dua ornamen terlihat di satu layar. Tanpa foto, ornamen dipakai di cover dan pemisah, bukan menutupi layar.
- Tanpa pola latar ulang, tanpa watercolor, tanpa kelopak jatuh.

### 8.5 Template 3: Modern Sederhana (`view_key: modern`)

**Karakter:** poster tipografis, rata kiri, blok warna penuh, sudut siku (0px).

**Font:** Archivo variabel dengan sumbu lebar (`@fontsource-variable/archivo`, berkas sumbu `wdth`), dipakai untuk semuanya. Nama: berat 800, lebar sekitar 75 (semi-condensed). Badan teks: berat 400, lebar 100.

```
┌──────────────────────────┐
│ Pernikahan               │  Archivo 400, 15px
│                          │
│ Andi                     │  Archivo 800, wdth 75,
│ & Siti                   │  clamp(3rem, 16vw, 5.5rem), baris 0.95
│                          │
│ 14.02.2026               │  tanggal sebagai teks besar (isi sebenarnya)
│ ┌──────────────────────┐ │
│ │ foto 1:1, radius 0   │ │
│ └──────────────────────┘ │
├──────────────────────────┤ ← pergantian blok warna (aksen)
│ Acara           Lokasi   │  grid 2 kolom; label berat 700
```

**Aturan:** tidak ada bayangan, tidak ada outline dekoratif, pembagian bagian lewat blok warna. Foto tidak diberi efek.

### 8.6 Variasi warna (`theme_color`)

Warna disimpan sebagai **kunci tetap** dalam whitelist per template; server menolak nilai lain. CSS diterapkan lewat atribut `data-warna` pada elemen akar, bukan lewat gaya inline dari input pengguna.

| Template | Kunci | Nama | Latar | Tinta | Muted | Aksen | Kontras (tinta / muted / aksen) |
|---|---|---|---|---|---|---|---|
| minimal | `tinta` | Tinta | `#F7F8FA` | `#1F2A44` | `#5B6478` | `#3D5A99` | 13,42 / 5,58 / 6,33 |
| minimal | `pualam` | Pualam | `#F3F3F0` | `#262B28` | `#656B66` | `#5F6F64` | 12,95 / 4,91 / 4,79 |
| minimal | `malam` | Malam | `#16213A` | `#EEF1F7` | `#A9B3C7` | `#E3C77E` | 14,14 / 7,59 / 9,70 |
| floral | `melati` | Melati | `#F3F6F0` | `#1F4034` | `#5A6E64` | `#8A6A12` | 10,46 / 5,00 / 4,64 |
| floral | `sirih` | Sirih | `#1F3B31` | `#F1F4EC` | `#B6C4BA` | `#E3C16F` | 10,93 / 6,71 / 7,02 |
| floral | `mawar` | Mawar Tua | `#F8EFED` | `#46222C` | `#7A5A62` | `#8E3A50` | 12,17 / 5,35 / 6,46 |
| modern | `kobalt` | Kobalt | `#E8EDF7` | `#11264D` | `#4C5B7A` | `#2552C8` | 12,70 / 5,80 / 5,73 |
| modern | `kunyit` | Kunyit | `#F0C84B` | `#2B2200` | `#5C4B0F` | `#2B2200` | 9,81 / 5,29 / 9,81 |
| modern | `lumut` | Lumut | `#D6E0CC` | `#1B3626` | `#4A5E4F` | `#1B3626` | 9,61 / 5,12 / 9,61 |

Semua pasangan teks lolos 4,5:1. Menambah varian baru: hitung kontrasnya terlebih dahulu.

### 8.7 Formulir RSVP (di dalam template)

Memenuhi PRD 6.9. Gaya mengikuti variabel template, struktur sama di ketiganya.

```
Konfirmasi kehadiran

Nama Anda
[                                  ]

Apakah Anda akan hadir?
( ) Hadir        ( ) Tidak hadir          ← dua pilihan besar, tinggi ≥ 48px

Jumlah tamu yang hadir                     ← tampil hanya bila "Hadir"
[ − ]  2  [ + ]

Ucapan untuk mempelai (opsional)
[                                  ]
Hanya pemilik undangan yang bisa membacanya.

[Kirim konfirmasi]
```

**Aturan:**
- Pilihan hadir/tidak hadir berupa radio dalam `<fieldset>` + `<legend>`. Yang terpilih ditandai ikon centang + tebal, tidak hanya warna.
- Jumlah tamu: input numerik dengan tombol − dan +, minimal 1. Batas maksimum ditetapkan tim (Bagian 14).
- Bidang tersembunyi honeypot dan token CSRF (PRD 5.5 dan 18), pembatasan permintaan sederhana di server.
- Kirim: tombol berubah menjadi "Mengirim" dan tidak bisa ditekan dua kali.
- **Sukses:** formulir diganti pesan, fokus dipindah ke judulnya, `role="status"`:
  "Terima kasih, Budi. Konfirmasi Anda sudah kami terima: hadir, 2 orang."
  Tombol sekunder: "Isi untuk tamu lain" (karena satu pengiriman = satu entri, PRD 6.9).
- **Error:** di bawah field, ikon + teks, tanpa menghapus isian. Terlalu banyak kiriman: "Terlalu banyak pengiriman dari perangkat ini. Coba lagi beberapa menit lagi."
- Bila acara sudah lewat atau undangan tidak lagi terbit, ikuti aturan di Bagian 14.

### 8.8 Galeri, peta, kontak

- **Galeri:** grid 2 kolom, rasio seragam 4:5, `loading="lazy"`, `width` dan `height` ditetapkan untuk mencegah lompatan tata letak. Ketuk membuka `<dialog>` bawaan browser dengan sebelumnya/berikutnya, keterangan foto (`caption`), tombol Tutup, dan navigasi keyboard. Tanpa library lightbox.
- **Peta:** tombol "Buka di Google Maps" dan "Salin alamat". Tanpa iframe peta (integrasi API peta tidak wajib, PRD 19.7).
- **Kontak:** tautan `tel:` atau `wa.me` dengan label jelas, ditampilkan hanya bila diisi pemilik.

### 8.9 Pratinjau tautan di WhatsApp

Tautan undangan akan dibagikan lewat WhatsApp, jadi sertakan:

- `og:title`: "{judul} {Nama 1} & {Nama 2}"
- `og:description`: tanggal lengkap + nama tempat (bila ada)
- `og:image`: foto utama bila ada; bila tidak, gambar statis per template dan varian
- `<meta name="robots" content="noindex, nofollow">` (rekomendasi privasi; undangan memuat data pribadi)

### 8.10 Slug tidak tersedia

Slug yang tidak ada, berstatus draft, atau unpublished menampilkan **halaman yang sama**: "Undangan tidak tersedia." dengan satu paragraf "Tautan ini mungkin salah ketik atau undangannya sudah tidak diterbitkan." Status 404. Halaman tidak membedakan kasusnya (PRD 12.3: slug tidak boleh menjadi jalan membuka draf).

---

## 9. Komponen

### 9.1 Tombol

| Jenis | Tampilan | Pemakaian |
|---|---|---|
| Utama | Latar `--daun-tua`, teks `--melati`; hover latar `--daun` | Satu per tampilan: "Buat Undangan", "Simpan draf", "Terbitkan" |
| Sekunder | Border 1px `--daun-tua`, latar transparan | "Pratinjau", "Batal" |
| Tersier | Teks `--daun`, garis bawah | "Lihat template", tautan |
| Destruktif | Latar `--bata`, teks putih (6,31:1) | "Hapus undangan" |

- Tinggi 44px (36px pada baris tabel desktop dengan area ketuk diperluas).
- Fokus: `outline: 2px solid var(--daun); outline-offset: 2px` (di permukaan gelap: warna `--kunyit`).
- Hindari tombol `disabled` tanpa penjelasan; pakai `aria-disabled` dan alasan terbaca.
- Keadaan memuat: teks berubah ("Menyimpan"), lebar tetap, tanpa spinner dekoratif.

### 9.2 Field formulir

- Tinggi 44px, radius `--r-kontrol`, border 1px `--tepi-input`, latar putih, teks 16px.
- Fokus: border `--daun-tua` + outline fokus standar.
- Label di atas, 14px/600. Teks bantu di bawah, 14px Batu.
- Error: border `--bata`, ikon + pesan di bawah, `aria-invalid="true"` dan `aria-describedby`.
- Tanggal dan waktu memakai `type="date"` dan `type="time"` bawaan (tampilan menyesuaikan locale perangkat).

### 9.3 Chip status

Bentuk pil, tinggi 24px, ikon 14px + teks 13px/600. Warna mengikuti Bagian 3.1.

### 9.4 Toast dan alert

- **Toast** (aksi selesai): kiri-bawah desktop, bawah penuh di ponsel, hilang sendiri setelah 5 detik, `role="status"`. Error tidak hilang sendiri.
- **Alert inline:** ikon + teks di atas latar muda dengan border 1px, tanpa garis warna tebal di sisi kiri.
- Teks aksi sama dengan label tombol: tombol "Terbitkan" menghasilkan toast "Undangan diterbitkan."

### 9.5 Modal

Bootstrap/Tabler modal. Judul = aksi. Tombol kanan-bawah: sekunder dulu, lalu utama atau destruktif. Fokus dikembalikan ke pemicu saat ditutup. Tombol Esc dan klik di luar menutup, kecuali saat sedang menyimpan.

### 9.6 Keadaan kosong

Judul (Young Serif atau Hanken 600) + satu kalimat + satu aksi. Tanpa ilustrasi generik.

| Tempat | Teks |
|---|---|
| Dashboard, belum ada undangan | **Buat undangan pertama Anda.** Pilih template, isi formulir, lalu terbitkan. [Buat Undangan] |
| Daftar undangan, filter kosong | Tidak ada undangan berstatus Draf. [Lihat semua] |
| RSVP kosong | Belum ada respons. Bagikan tautan undangan agar tamu bisa mengisi RSVP. [Salin tautan] |

Keadaan kosong dashboard baru boleh menampilkan tiga langkah bernomor (pilih template, isi formulir, terbitkan) karena itu urutan nyata.

### 9.7 Tabel

Header latar `--cekung`, teks 14px/600, border bawah 1px `--garis`. Tanpa zebra. Angka rata kanan, tabular. Di ponsel berubah menjadi daftar baris bertumpuk.

### 9.8 Paginasi

Gaya Tabler, disesuaikan token. Tampilkan "Halaman 2 dari 5" sebagai teks, bukan hanya angka.

---

## 10. Copywriting

### 10.1 Suara

- Bahasa Indonesia, memakai **"Anda"** (sopan namun hangat; pengguna dari berbagai usia dan keluarga).
- Kalimat pendek, kata kerja aktif, huruf kapital biasa (kapital hanya di awal kalimat dan nama diri).
- Tidak ada seruan berlebihan, emoji, atau bahasa gaul.
- Setiap teks punya satu tugas.
- Tagline berbahasa Inggris hanya sebagai merek.

### 10.2 Daftar istilah (dipakai konsisten di seluruh produk)

| Konsep | Gunakan | Jangan campur dengan |
|---|---|---|
| Invitation | Undangan | Kartu, e-invite |
| Draft | Draf | Konsep, draft |
| Publish | Terbitkan / Terbit | Publikasikan, Rilis, Posting |
| Unpublish | Batalkan penerbitan / Tidak terbit | Nonaktifkan, Takedown |
| Preview | Pratinjau | Preview |
| Guest | Tamu | Undangan (untuk orang) |
| Bride/groom | Mempelai | Pengantin (campur), Partner |
| Password | Kata sandi | Password, Sandi |
| Login / Logout / Register | Masuk / Keluar / Daftar | Sign in |
| Color variant | Warna | Tema |
| RSVP | RSVP (di dashboard), Konfirmasi kehadiran (untuk tamu) | |
| Slug | Alamat undangan | Slug, URL |

### 10.3 Pesan kesalahan dan sistem

Rumus: **apa yang terjadi + cara memperbaikinya.** Tidak meminta maaf berlebihan, tidak samar.

| Situasi | Teks |
|---|---|
| Field wajib kosong | Isi nama mempelai kedua. |
| Email terdaftar | Email ini sudah terdaftar. Masuk, atau gunakan email lain. |
| Login gagal | Email atau kata sandi salah. |
| Kata sandi pendek | Kata sandi minimal {n} karakter. |
| Foto terlalu besar | Ukuran foto 7,2 MB, melebihi batas {n} MB. Kecilkan foto atau pilih foto lain. |
| Format foto salah | Format tidak didukung. Gunakan JPG, PNG, atau WebP. |
| Unggah gagal | Foto belum terunggah. Periksa koneksi Anda, lalu coba lagi. |
| Tautan peta tidak valid | Tautan harus berupa tautan Google Maps yang diawali https://. |
| Alamat undangan terpakai | Alamat 'andi-siti' sudah dipakai. Coba 'andi-siti-2'? |
| Alamat tidak valid | Gunakan huruf kecil, angka, dan tanda hubung. |
| Syarat terbit kurang | Lengkapi 2 isian sebelum menerbitkan. |
| Undangan milik pengguna lain | Undangan tidak ditemukan. (gunakan 404, bukan 403, agar keberadaan data tidak bocor) |
| Kesalahan server | Terjadi gangguan di server kami. Muat ulang halaman. Bila berlanjut, coba lagi nanti. |
| Perubahan belum disimpan | Perubahan belum disimpan. Simpan dulu sebelum keluar? [Simpan draf] [Keluar tanpa menyimpan] |

### 10.4 Format angka dan tanggal

- Tanggal panjang: "Sabtu, 14 Februari 2026". Tabel: "14 Feb 2026".
- Waktu: "10.00 WIB" (titik, bukan titik dua).
- Desimal koma, ribuan titik: "7,2 MB", "1.250".
- Waktu RSVP: "14 Feb 2026, 19.42 WIB".

Gunakan `IntlDateFormatter` dengan locale `id_ID` (atau ekstensi Intl Twig). Jangan memformat tanggal secara manual di view.

---

## 11. Implementasi dengan Vite, Twig, dan Tabler

### 11.1 Struktur berkas

```text
resources/
├── css/
│   ├── tokens.css           # variabel global (warna, tipe, radius, jarak)
│   ├── base.css             # reset ringan, tipografi dasar, fokus, reduced-motion
│   ├── dashboard.css        # override Tabler/Bootstrap
│   ├── site.css             # landing, autentikasi, galeri template publik
│   └── invitation/
│       ├── base.css         # struktur bersama (stack, RSVP, galeri, dialog)
│       ├── minimal.css
│       ├── floral.css
│       └── modern.css
└── js/
    ├── dashboard.js         # entry dashboard (Tabler/Bootstrap JS)
    ├── editor.js            # formulir, pratinjau, unggah, beforeunload
    ├── site.js              # hero demo landing
    └── invitation.js        # hitung mundur, galeri dialog, RSVP, salin alamat
```

### 11.2 Entry Vite per area

Setiap area memuat hanya yang dibutuhkan:

| Entry | Memuat |
|---|---|
| `dashboard.js` | tokens, base, Tabler, dashboard.css, Hanken Grotesk |
| `site.js` | tokens, base, Bootstrap (grid), site.css, Hanken + Young Serif |
| `invitation.js` + `invitation/{view_key}.css` | tokens (bagian yang perlu), invitation/base.css, **hanya font template terpilih** |

Jangan memuat versi CDN dan lokal dari library yang sama (PRD 11). Font dilayani lokal lewat Fontsource, impor hanya subset `latin`, `font-display: swap`, dan preload hanya berkas font utama halaman.

### 11.3 Token inti (`tokens.css`)

```css
:root {
  /* Warna merek */
  --melati: #F2F5EE;
  --kertas: #FFFFFF;
  --cekung: #E8EDE3;
  --daun-tua: #17382D;
  --daun: #2C6A52;
  --daun-muda: #DCE8D9;
  --kunyit: #E0A22A;
  --kunyit-muda: #F7E8BE;
  --batu: #566860;
  --garis: #D3DCD0;
  --tepi-input: #7C8C83;
  --bata: #A93A2C;
  --bata-muda: #F6DDD8;
  --netral-chip: #E7ECE4;

  /* Tipografi */
  --font-tampil: "Young Serif", Georgia, "Times New Roman", serif;
  --font-ui: "Hanken Grotesk Variable", "Hanken Grotesk", system-ui,
             -apple-system, "Segoe UI", Roboto, sans-serif;
  --t-xs: .8125rem;  --t-sm: .875rem;  --t-base: 1rem;  --t-lg: 1.125rem;
  --t-xl: 1.375rem;  --t-2xl: 1.75rem; --t-3xl: 2.25rem;
  --t-4xl: clamp(2.25rem, 1.2rem + 4.5vw, 3.75rem);

  /* Jarak (basis 4px) */
  --s-1: .25rem; --s-2: .5rem; --s-3: .75rem; --s-4: 1rem;
  --s-6: 1.5rem; --s-8: 2rem;  --s-12: 3rem;  --s-16: 4rem; --s-24: 6rem;

  /* Radius menurut peran */
  --r-kertas: 2px; --r-kontrol: 8px; --r-panel: 12px;
  --r-bingkai-hp: 32px; --r-pil: 999px;

  /* Elevasi */
  --bayang-kertas: 0 1px 0 rgb(23 56 45 / .08), 0 12px 24px -12px rgb(23 56 45 / .30);
  --bayang-melayang: 0 6px 20px -6px rgb(23 56 45 / .28);
  --bayang-modal: 0 20px 48px -12px rgb(23 56 45 / .35);

  /* Gerak */
  --dur-cepat: 120ms; --dur-sedang: 180ms; --ease: cubic-bezier(.2, .7, .2, 1);
}

:focus-visible { outline: 2px solid var(--daun); outline-offset: 2px; }
.on-gelap :focus-visible { outline-color: var(--kunyit); }

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: .01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: .01ms !important;
    scroll-behavior: auto !important;
  }
}
```

### 11.4 Override Tabler (`dashboard.css`)

Nama variabel Tabler bergantung pada versi. **Verifikasi nama variabel pada versi yang dikunci** sebelum menerapkan; pola di bawah mengikuti keluarga `--tblr-*`.

```css
:root {
  --tblr-primary: var(--daun-tua);
  --tblr-primary-rgb: 23, 56, 45;
  --tblr-body-bg: var(--melati);
  --tblr-body-color: var(--daun-tua);
  --tblr-secondary: var(--batu);
  --tblr-border-color: var(--garis);
  --tblr-link-color: var(--daun);
  --tblr-font-sans-serif: var(--font-ui);
  --tblr-border-radius: var(--r-kontrol);
  --tblr-border-radius-lg: var(--r-panel);
  --tblr-danger: var(--bata);
  --tblr-warning: var(--kunyit);
  --tblr-success: var(--daun);
}

/* Panel datar: tanpa bayangan bawaan */
.card { box-shadow: none; border: 1px solid var(--garis); border-radius: var(--r-panel); }

/* Input mengikuti kontras komponen */
.form-control, .form-select { border-color: var(--tepi-input); min-height: 44px; font-size: var(--t-base); }
```

### 11.5 Varian warna template (`invitation/base.css`)

```css
.inv[data-warna="tinta"]   { --inv-bg:#F7F8FA; --inv-latar:#E9ECF2; --inv-ink:#1F2A44; --inv-muted:#5B6478; --inv-aksen:#3D5A99; --inv-garis: rgb(31 42 68 / .25); }
.inv[data-warna="pualam"]  { --inv-bg:#F3F3F0; --inv-latar:#E6E6E2; --inv-ink:#262B28; --inv-muted:#656B66; --inv-aksen:#5F6F64; --inv-garis: rgb(38 43 40 / .25); }
.inv[data-warna="malam"]   { --inv-bg:#16213A; --inv-latar:#0F182B; --inv-ink:#EEF1F7; --inv-muted:#A9B3C7; --inv-aksen:#E3C77E; --inv-garis: rgb(238 241 247 / .22); }

.inv[data-warna="melati"]  { --inv-bg:#F3F6F0; --inv-latar:#E6ECE2; --inv-ink:#1F4034; --inv-muted:#5A6E64; --inv-aksen:#8A6A12; --inv-garis: rgb(31 64 52 / .25); }
.inv[data-warna="sirih"]   { --inv-bg:#1F3B31; --inv-latar:#172C24; --inv-ink:#F1F4EC; --inv-muted:#B6C4BA; --inv-aksen:#E3C16F; --inv-garis: rgb(241 244 236 / .22); }
.inv[data-warna="mawar"]   { --inv-bg:#F8EFED; --inv-latar:#F0E1DE; --inv-ink:#46222C; --inv-muted:#7A5A62; --inv-aksen:#8E3A50; --inv-garis: rgb(70 34 44 / .25); }

.inv[data-warna="kobalt"]  { --inv-bg:#E8EDF7; --inv-latar:#D8E0F0; --inv-ink:#11264D; --inv-muted:#4C5B7A; --inv-aksen:#2552C8; --inv-garis: rgb(17 38 77 / .25); }
.inv[data-warna="kunyit"]  { --inv-bg:#F0C84B; --inv-latar:#E2B73D; --inv-ink:#2B2200; --inv-muted:#5C4B0F; --inv-aksen:#2B2200; --inv-garis: rgb(43 34 0 / .30); }
.inv[data-warna="lumut"]   { --inv-bg:#D6E0CC; --inv-latar:#C7D3BB; --inv-ink:#1B3626; --inv-muted:#4A5E4F; --inv-aksen:#1B3626; --inv-garis: rgb(27 54 38 / .25); }
```

`--inv-latar` dan `--inv-garis` hanya untuk dekorasi dan tidak boleh dipakai sebagai warna teks.

### 11.6 Komponen Twig yang disepakati

Agar Anggota 1 dan 3 memakai nama yang sama (PRD 16.4):

| Komponen/macro | Fungsi |
|---|---|
| `icon(nama)` | SVG inline Tabler Icons |
| `status_chip(status)` | Chip untuk `draft`, `published`, `unpublished`, `attending`, `not_attending` |
| `form_field(nama, label, opsi)` | Label, input, teks bantu, pesan error |
| `empty_state(judul, teks, aksi)` | Keadaan kosong |
| `confirm_modal(id, judul, teks, aksi)` | Modal konfirmasi |
| `copy_button(teks, label)` | Tombol salin dengan konfirmasi `aria-live` |
| `rsvp_summary(data)` | Panel empat ringkasan RSVP |
| `invitation_section(nama)` | Pembungkus bagian undangan yang tidak dirender bila kosong |

Template undangan dipilih lewat pemetaan `view_key` ke view terdaftar. Jangan pernah menerima nama berkas view dari input pengguna (PRD 16.4).

### 11.7 Instruksi untuk AI coding assistant (Anggota 2)

Tambahkan ke prompt dasar Anggota 2:

```text
Ikuti Design.md untuk seluruh tampilan Invitara.

- Pakai token di tokens.css. Jangan menulis hex, radius, atau bayangan baru di luar token.
- Dashboard memakai Tabler dengan override token. Jangan biarkan tampilan bawaan Tabler.
- Undangan publik tidak memakai Tabler.
- Patuhi Bagian 2 (Aturan Anti-AI-Slop) dan jalankan tiga uji di Bagian 2.4.
- Semua teks dalam Bahasa Indonesia mengikuti Bagian 10. Gunakan "Anda".
- Jangan menanam data contoh (nama, tanggal, foto) di view. Data dari controller.
- Jangan menambahkan animasi, ikon dekoratif, gradien, atau bagian yang tidak ada dalam Design.md.
- Jika kebutuhan desain bertentangan dengan PRD atau belum tercakup, tanyakan sebelum membuat keputusan.
- Laporkan file yang dibuat/diubah dan cara menguji pada lebar 360px, 412px, 768px, dan 1280px.
```

---

## 12. Aksesibilitas, Responsif, dan Performa

### 12.1 Aksesibilitas (batas minimum)

- `lang="id"` pada `<html>`.
- Kontras teks minimal 4,5:1; komponen UI minimal 3:1 (sudah dihitung, Bagian 3.1 dan 8.6).
- Fokus terlihat pada semua elemen interaktif; urutan tab mengikuti urutan visual.
- Area ketuk minimal 44x44px di ponsel.
- Status tidak hanya diwakili warna.
- Semua gambar bermakna punya `alt`; foto dekoratif memakai `alt=""`.
- Formulir: label terhubung, error terhubung lewat `aria-describedby`, ringkasan error menerima fokus.
- Modal dan `<dialog>` mengembalikan fokus ke pemicu.
- Hormati `prefers-reduced-motion`.
- Pengujian keyboard: seluruh alur utama (daftar, masuk, buat undangan, terbitkan, kirim RSVP) bisa diselesaikan tanpa mouse.

### 12.2 Responsif

| Lebar uji | Alasan |
|---|---|
| 360px | Ponsel Android umum, batas bawah |
| 412px | Ponsel Android lebih besar |
| 768px | Tablet |
| 1280px | Laptop |

Breakpoint mengikuti Bootstrap 5 (576, 768, 992, 1200). Tidak ada gulir horizontal pada halaman mana pun. Tabel menjadi daftar bertumpuk di ponsel.

### 12.3 Performa (target awal, ukur ulang setelah build)

| Hal | Target |
|---|---|
| CSS halaman undangan | ≤ 40 KB terkompresi |
| JavaScript halaman undangan | ≤ 20 KB terkompresi |
| Font halaman undangan | 2 keluarga font, subset latin saja |
| Foto sampul setelah diproses | ≤ 200 KB |
| Gambar | Dua ukuran (misal 800px dan 1600px) dalam WebP, `srcset`, `loading="lazy"` kecuali cover, `width`/`height` selalu diisi |

Pemrosesan ukuran gambar berada di `UploadService` (koordinasi Anggota 1 dan 3). Karena halaman di-SSR, isi sudah ada saat HTML tiba; JavaScript hanya menambah interaksi.

---

## 13. Daftar Periksa Review UI (untuk setiap PR)

Tambahkan ke templat deskripsi PR yang menyentuh tampilan.

**Konsistensi**
- [ ] Hanya memakai token warna, radius, bayangan, dan jarak dari Design.md.
- [ ] Istilah mengikuti daftar istilah (Bagian 10.2); semua teks Bahasa Indonesia dan memakai "Anda".
- [ ] Tidak ada data contoh tertanam di view.

**Anti-AI-slop**
- [ ] Lolos uji tukar subjek, uji tutup logo, dan uji lepas satu hiasan (Bagian 2.4).
- [ ] Tidak ada eyebrow huruf kapital, tombol berpanah, penomoran palsu, gradien dekoratif, animasi masuk bagian, atau ikon dalam lingkaran pastel.
- [ ] Tidak ada klaim, statistik, atau testimoni yang tidak berdasar data nyata.

**Fungsi dan keamanan tampilan**
- [ ] Bagian opsional kosong tidak dirender dan tidak menyisakan jarak.
- [ ] Output teks di-escape Twig; tautan eksternal memakai `rel="noopener noreferrer"`.
- [ ] Warna template dari whitelist kunci, bukan gaya inline dari input.
- [ ] Draf/unpublished tidak terlihat publik; slug tidak valid menampilkan halaman yang sama.

**Kualitas**
- [ ] Diuji pada 360px, 412px, 768px, dan 1280px tanpa gulir horizontal.
- [ ] Diuji dengan keyboard saja; fokus terlihat.
- [ ] Nama sangat panjang (100 karakter) dan tanpa foto tidak merusak tata letak.
- [ ] `prefers-reduced-motion` diuji.
- [ ] Keadaan kosong, memuat, error, dan sukses sudah dirancang, bukan hanya keadaan berhasil.

---

## 14. Catatan dan Pertanyaan Terbuka untuk Tim

Hal-hal berikut ditemukan saat membaca PRD dan memerlukan keputusan. Desain di atas memakai usulan yang tertera sampai ada keputusan.

| # | Temuan | Usulan | Pemilik |
|---|---|---|---|
| 1 | PRD 10.3 menamai menu dalam bahasa Inggris (Overview, My Invitations, ...), tetapi PRD 12.1 mewajibkan label Bahasa Indonesia | Pakai pemetaan di Bagian 7.1 | Anggota 2 |
| 2 | Fitur 6.5 dan 6.8 memuat **cerita singkat**, **kontak penyelenggara**, dan **hitung mundur aktif/nonaktif**, tetapi tabel `invitations` (PRD 9.4) tidak punya kolomnya | Tambah kolom lewat migration (misalnya `story`, `contact`, `show_countdown`) | Anggota 1 |
| 3 | `theme_color` hanya "pilihan warna yang diizinkan", tanpa daftar | Pakai kunci whitelist di Bagian 8.6, divalidasi di server | Anggota 1 dan 3 |
| 4 | "Validasi minimum" untuk menerbitkan belum didefinisikan (PRD 6.7) | Nama kedua mempelai, judul, tanggal, zona waktu, template aktif | Anggota 3 |
| 5 | Batas ukuran foto, jumlah foto galeri, jumlah tamu maksimum per RSVP, dan panjang ucapan belum ditetapkan | Tetapkan angka, lalu tampilkan di teks bantu dan pesan error | Anggota 1 dan 3 |
| 6 | RSVP setelah tanggal acara lewat: tetap dibuka atau ditutup? | Tetap dibuka sampai pemilik membatalkan penerbitan; atau tutup otomatis. Perlu diputuskan | Tim |
| 7 | Bolehkah mengganti template setelah undangan dibuat? | Boleh sebelum terbit; pilihan warna direset ke varian pertama template baru | Anggota 3 |
| 8 | PRD 10.4 memiliki `layouts/public.twig`, tetapi tidak jelas untuk landing atau undangan | Usul: `public.twig` untuk landing/galeri, tambah `invitation.twig` polos untuk undangan | Anggota 2 |
| 9 | Perilaku RSVP saat undangan dibatalkan penerbitannya atau dihapus | Unpublish: RSVP tetap tersimpan. Hapus: RSVP ikut terhapus (sesuai PRD 8.11), dengan peringatan jelas di modal | Anggota 1 dan 3 |
| 10 | Nama tamu personal pada tautan (misal `?to=Budi`, "Kepada Yth.") lazim di undangan digital Indonesia, tetapi tidak ada di PRD | Opsional setelah MVP. Bila dibuat, hanya ditampilkan (di-escape), tidak disimpan | Tim |
| 11 | Harga, paket, dan klaim "gratis" belum diputuskan | Jangan tampilkan apa pun soal harga sampai diputuskan | Tim |
| 12 | Tagline PRD berbahasa Inggris | Dipakai sebagai tagline merek di footer dan meta saja | Anggota 2 |

---

## Lampiran A. Aset Pemasaran (PRD Tahap 6)

Agar konten media sosial konsisten dengan produk:

- Palet dan font sama dengan Bagian 3 (Melati, Daun Tua, Kunyit; Young Serif dan Hanken Grotesk).
- Gunakan **tangkapan layar nyata** dari undangan dan dashboard, bukan mockup generik. Jangan memakai testimoni, angka pengguna, atau hasil yang belum terjadi (PRD 14.3).
- Format kiriman 4:5, dengan satu pesan per kiriman dan nama produk di pojok.
- Hindari frasa terlarang di Bagian 2.3.

---

## Lampiran B. Ringkasan Keputusan Desain

| Aspek | Keputusan |
|---|---|
| Konsep | Dashboard tenang dan datar; undangan sebagai benda kertas yang hangat |
| Hal yang diingat | Hero demo hidup: ketik nama, kertas undangan terisi |
| Palet merek | Melati, Daun Tua, Daun, Kunyit, Batu (hijau-kehijauan; tanpa krem/terakota, tanpa neon) |
| Font | Young Serif (tampil), Hanken Grotesk (UI); template: Libre Caslon Text, Sorts Mill Goudy, Archivo |
| Radius | Menurut peran: kertas 2px, kontrol 8px, panel 12px |
| Bayangan | Panel tanpa bayangan; hanya benda kertas dan lapisan melayang, berwarna hijau tua |
| Gerak | Hero, cover undangan, dan respons aksi pengguna; sisanya diam |
| Dashboard | Tabler dengan override token; label Bahasa Indonesia |
| Undangan publik | Tanpa Tabler; satu kolom ≤ 560px; tanpa gerbang, tanpa musik otomatis |
| Template | Minimalis Elegan, Floral Klasik, Modern Sederhana; masing-masing 3 warna dari whitelist |
| Copy | Bahasa Indonesia, "Anda", kata kerja + objek, tanpa frasa klise |
