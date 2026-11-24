# SPPC — Application Discovery & Findings Report
**Sistem Presensi & Pengambilan Catering — Pesantren Walisongo**
**Tanggal Discovery:** 2026-11-24 | **Status:** Completed (Source Code Analysis)

---

## 1. PROFIL APLIKASI

| Atribut | Detail |
|---|---|
| **Nama Aplikasi** | SPPC — Sistem Presensi & Pengambilan Catering |
| **Tipe Aplikasi** | Custom Web Application — Pesantren Management System |
| **Framework** | Laravel 13.x (PHP ^8.3) |
| **Database** | MySQL / MariaDB (`sppc_walisongo`) |
| **Frontend** | Blade + Bootstrap + Font Awesome |
| **QR Scanner** | html5-qrcode v2.3.8 (CDN) |
| **PDF Export** | barryvdh/laravel-dompdf ^3.1 |
| **API Auth** | Laravel Sanctum ^4.0 |
| **Deployment** | Laragon (lokal), MySQL port 3306 |
| **Tujuan Utama** | Mencatat pengambilan lauk santri melalui scan QR code per sesi (Pagi/Siang/Malam) |
| **Target Pengguna** | Staf Pesantren (Admin, Catering, Pengasuh) |

---

## 2. ARSITEKTUR & STRUKTUR SISTEM

### 2.1 Modul Aplikasi

| Modul | Deskripsi | Status |
|---|---|---|
| **Authentication** | Login/logout berbasis session | ✅ Aktif |
| **Manajemen Santri** | CRUD data santri + auto-generate QR | ✅ Aktif |
| **Manajemen Catering** | CRUD jadwal catering (tanggal, sesi, menu) | ✅ Aktif |
| **Scan QR** | Scan/input manual kode QR → catat presensi & pengambilan | ✅ Aktif |
| **Laporan** | Rekap presensi + export PDF | ✅ Aktif |
| **API REST** | Endpoint santri, lauk, pengambilan-lauk | ⚠️ Tidak ada auth guard |
| **Manajemen Lauk** | CRUD stok lauk (API only, tidak ada UI) | ⚠️ Tersembunyi |
| **Peserta Catering** | Tabel periode peserta (tidak digunakan di UI) | ⚠️ Orphaned |

### 2.2 Role Pengguna

| Role | Akses | Redirect Setelah Login |
|---|---|---|
| `admin` | Dashboard admin, semua fitur CRUD | `/dashboard` |
| `catering` | Halaman scan QR | `/scan` |
| `pengasuh` | Halaman laporan | `/laporan` |

> **Catatan:** Role default ketika akun baru dibuat adalah `admin` (dari migration `add_role_to_users_table.php`).

### 2.3 Diagram Alur Proses Utama

```
[Santri Daftar]
     ↓ (Admin)
[Data Santri Dibuat] → [QR Code Auto-Generated (UUID)]
     ↓
[Jadwal Catering Dibuat] ← (Admin, per tanggal & sesi)
     ↓
[Waktu Makan Tiba]
     ↓
[Petugas Catering Scan QR Santri]
     ↓
[Sistem Validasi]:
  ├─ QR terdaftar? → Tidak → Tolak
  ├─ Santri ada? → Tidak → Tolak
  ├─ Catering hari ini & sesi tersedia? → Tidak → Tolak
  └─ Sudah ambil sesi ini hari ini? → Ya → Tolak
     ↓ (semua valid)
[Simpan Presensi] + [Simpan PengambilanLauk (jumlah=1)]
     ↓
[Pengasuh Lihat Laporan] → [Export PDF]
```

---

## 3. INVENTARIS HALAMAN & FITUR

### PAGE-01: Login (`/login`)
- **Role:** Guest
- **Komponen:** Form email + password, submit button
- **Validasi:** required, format email
- **Business Rule:** Redirect berbasis role setelah login berhasil

### PAGE-02: Dashboard Admin (`/dashboard`)
- **Role:** admin
- **Komponen:** Kartu statistik (Total Santri, Presensi Hari Ini, QR Terdaftar)
- **Data Real-time:** Ya (via query live)

### PAGE-03: Dashboard Catering (`/dashboard`)
- **Role:** catering
- **Komponen:** View info (tidak ada statistik spesifik)

### PAGE-04: Dashboard Pengasuh (`/dashboard`)
- **Role:** pengasuh
- **Komponen:** Presensi hari ini

### PAGE-05: Data Santri (`/santri`)
- **Role:** auth (semua)
- **Fitur:** List santri + QR code, tambah, edit, hapus
- **Business Rule:** Santri dengan riwayat presensi tidak dapat dihapus

### PAGE-06: Jadwal Catering (`/catering`)
- **Role:** auth (semua)
- **Fitur:** List jadwal, tambah (tanggal+sesi+menu), edit, hapus
- **Business Rule:** Jadwal dengan riwayat presensi tidak dapat dihapus

### PAGE-07: Scan QR (`/scan`)
- **Role:** auth (semua, terutama catering)
- **Fitur:** Kamera scanner (html5-qrcode) + input manual + riwayat hari ini
- **Business Rule:** 1 santri = 1 pengambilan per sesi per hari

### PAGE-08: Laporan (`/laporan`)
- **Role:** auth (semua, terutama pengasuh)
- **Fitur:** Tabel rekap semua presensi, export PDF
- **Keterbatasan:** Tidak ada filter tanggal/rentang waktu

---

## 4. INVENTARIS API ENDPOINT

| Method | Endpoint | Controller | Auth Guard |
|---|---|---|---|
| GET/POST/PUT/DELETE | `/api/santri` | SantriController | ❌ Tidak ada |
| GET/POST/PUT/DELETE | `/api/lauk` | LaukController | ❌ Tidak ada |
| GET/POST/PUT/DELETE | `/api/pengambilan-lauk` | PengambilanLaukApiController | ❌ Tidak ada |
| GET | `/api/user` | Closure | ✅ auth:sanctum |

---

## 5. TEMUAN MASALAH (FINDINGS)

### 🔴 CRITICAL

#### F-001: Inkonsistensi Skema Database vs Model (Schema Mismatch)
- **Lokasi:** Seluruh codebase
- **Deskripsi:** Migration mendefinisikan nama kolom/tabel yang **berbeda** dengan yang digunakan di Model Eloquent.

| Entitas | Nama di Migration | Nama di Model | Dampak |
|---|---|---|---|
| Tabel Santri | `santris` (kolom: `idSantri`, `nis`, `nama_santri`, `kamar`) | `santri` (kolom: `id_santri`, `nama`, `kelas`, `asrama`) | Query gagal di production |
| Tabel Catering | `caterings` (kolom: `idCatering`, `sesi`, `user_id`) | `catering` (kolom: `id_catering`, `waktu`, tanpa `user_id`) | Column not found |
| Tabel Presensi | `presensis` (kolom: `idPresensi`, `santri_id`, `catering_id`, `tanggal`, `waktu`) | `presensi` (kolom: `id_presensi`, `id_santri`, `id_catering`, `waktu_scan`) | Column not found |
| Tabel QrCode | `qr_codes` (kolom: `idQR`, `santri_id`) | `qrcode` (kolom: `id_qrcode`, `id_santri`) | Table not found |
| Tabel Laporan | `laporans` (kolom: `idLaporan`, `jenis`) | `laporan` (kolom: `id_laporan`, `total_hadir`, `total_tidak_hadir`) | Column not found |
| Tabel PengambilanLauk | `pengambilan_lauks` (kolom: `idPengambilan`, `santri_id`, `waktu_ambil`) | `pengambilan_lauk` (kolom: `id_pengambilan`, `id_presensi`, `jumlah_lauk`) | Column not found |

- **Kesimpulan:** Terdapat **dua versi schema** — migration adalah versi lama (revisi 1) sedangkan model adalah versi baru (revisi 2). Database aktual kemungkinan menggunakan schema dari model, bukan migration.
- **Risiko:** Sangat Tinggi — `php artisan migrate:fresh` akan membuat database yang tidak kompatibel dengan kode.

#### F-002: API Endpoint Tanpa Autentikasi
- **Lokasi:** `routes/api.php`
- **Deskripsi:** Tiga resource API (`/api/santri`, `/api/lauk`, `/api/pengambilan-lauk`) tidak dilindungi oleh middleware auth.
- **Dampak:** Siapapun yang mengetahui URL dapat membaca, mengubah, menambah, dan menghapus data santri serta data pengambilan lauk tanpa perlu login.
- **Risiko:** Sangat Tinggi — data privacy & integritas data terancam.

### 🟠 HIGH

#### F-003: Tidak Ada Middleware Authorization pada Web Routes
- **Lokasi:** `routes/web.php`
- **Deskripsi:** Semua route di dalam grup `middleware('auth')` dapat diakses oleh **semua role**. Tidak ada pemisahan akses per role di route level.
- **Contoh:** Role `catering` bisa mengakses `/santri`, `/catering` (CRUD) yang seharusnya hanya untuk `admin`.
- **Dampak:** Broken Access Control — petugas catering bisa menghapus data santri.

#### F-004: Model `PengambilanLaukApiController` Mereferensikan Relasi yang Tidak Ada
- **Lokasi:** `app/Http/Controllers/PengambilanLaukApiController.php` baris 14-17
- **Deskripsi:** Controller memanggil `->with(['santri', 'catering', 'user'])` pada `PengambilanLauk`, namun model `PengambilanLauk` hanya mendefinisikan relasi `presensi`. Relasi `santri`, `catering`, dan `user` tidak ada.
- **Dampak:** API endpoint `/api/pengambilan-lauk` akan error dengan `Call to undefined relationship`.

#### F-005: Model `PesertaCatering` Menggunakan Foreign Key yang Tidak Sesuai
- **Lokasi:** `app/Models/PesertaCatering.php` baris 24
- **Deskripsi:** Relasi menggunakan `'santri_id', 'idSantri'` padahal model `Santri` menggunakan primary key `id_santri`.
- **Dampak:** Relasi `peserta->santri` selalu mengembalikan `null`.

#### F-006: `LaporanController` Tidak Ada Filter — Load All Data
- **Lokasi:** `app/Http/Controllers/LaporanController.php`
- **Deskripsi:** Laporan mengambil **semua data presensi** tanpa filter tanggal, paginating, atau batasan. Dengan data bertumbuh, ini akan menyebabkan timeout dan memory exhaustion.
- **Dampak:** Performance degradation signifikan di production.

#### F-007: Laporan (`Laporan` model) Tidak Terintegrasi dengan Sistem Aktual
- **Lokasi:** `app/Models/Laporan.php`, tabel `laporan`
- **Deskripsi:** Model `Laporan` dengan kolom `total_hadir`, `total_tidak_hadir` tidak pernah diisi oleh sistem manapun. `LaporanController` justru membaca dari tabel `presensi`, bukan dari `laporan`.
- **Dampak:** Tabel `laporan` adalah dead code — tabel ada tapi tidak terpakai.

### 🟡 MEDIUM

#### F-008: `Lauk` Model Tidak Memiliki `HasFactory`
- **Lokasi:** `app/Models/Lauk.php`
- **Deskripsi:** Model `Lauk` tidak menggunakan trait `HasFactory` tidak seperti model lainnya.
- **Dampak:** Factory untuk testing tidak dapat digunakan.

#### F-009: Tidak Ada Validasi Duplikat pada Jadwal Catering
- **Lokasi:** `app/Http/Controllers/CateringController.php`
- **Deskripsi:** Tidak ada validasi `unique` pada kombinasi `tanggal + waktu`. Petugas admin bisa membuat dua jadwal "Pagi, 2026-11-24" sekaligus.
- **Dampak:** Data ganda akan menyebabkan logika scan QR berperilaku tidak terduga (hanya memilih `->first()`).

#### F-010: Halaman Laporan Tidak Ada Filter Tanggal/Rentang
- **Lokasi:** `resources/views/laporan/index.blade.php`
- **Deskripsi:** Pengguna tidak dapat memfilter laporan berdasarkan tanggal, sesi, atau asrama.
- **Dampak:** Usability terbatas — pengasuh harus scroll semua data.

#### F-011: Tidak Ada Fitur Cetak/Tampilan QR Code untuk Santri
- **Lokasi:** `resources/views/santri/`
- **Deskripsi:** QR code di-generate (string UUID) tetapi tidak ada tampilan atau cetak QR code aktual. Tidak jelas bagaimana santri mendapatkan kartu fisik QR.
- **Dampak:** Core workflow tidak lengkap — QR ada di database tapi tidak bisa dicetak/ditampilkan.

#### F-012: CDN Dependency untuk QR Scanner
- **Lokasi:** `resources/views/scan/index.blade.php` baris 317
- **Deskripsi:** Library `html5-qrcode` dimuat dari CDN. Jika koneksi internet tidak tersedia, scanner tidak berfungsi.
- **Dampak:** Aplikasi gagal di environment tanpa internet (pesantren bisa offline).

### 🔵 LOW

#### F-013: `APP_DEBUG=true` di `.env.example`
- **Lokasi:** `.env.example`
- **Deskripsi:** Template `.env.example` memiliki `APP_DEBUG=true`. Developer baru akan menggunakannya langsung tanpa sadar.
- **Dampak:** Stack trace dan data sensitif bisa terekspos di production.

#### F-014: Tidak Ada Seeder / Test Data
- **Lokasi:** `database/seeders/`
- **Deskripsi:** Tidak ada seeder yang dapat digunakan untuk membuat data awal (user admin, data santri sample, jadwal catering).
- **Dampak:** Setup environment baru membutuhkan input data manual.

#### F-015: `Catering` Model Tidak Memiliki Relasi ke `PengambilanLauk`
- **Lokasi:** `app/Models/Catering.php`
- **Deskripsi:** Relasi `catering->pengambilanLauk` tidak didefinisikan, hanya ada `presensis`. Ini membatasi query statistik.

#### F-016: Kolom `waktu` di `Catering` vs `sesi` di Migration
- **Lokasi:** Model vs Migration
- **Deskripsi:** Migration menggunakan kolom `sesi`, model menggunakan `waktu` di fillable. Ini bagian dari F-001 namun perlu dicatat secara eksplisit karena berdampak langsung pada scan QR (cek `where('waktu', $validated['waktu'])`).

---

## 6. RINGKASAN TEMUAN

| Prioritas | Jumlah | Item |
|---|---|---|
| 🔴 Critical | 2 | F-001, F-002 |
| 🟠 High | 4 | F-003, F-004, F-005, F-006 |
| 🟡 Medium | 5 | F-007, F-008, F-009, F-010, F-011, F-012 |
| 🔵 Low | 4 | F-013, F-014, F-015, F-016 |
| **Total** | **15** | |

---

## 7. DISCOVERY CONFIDENCE

| Informasi | Status | Keterangan |
|---|---|---|
| Struktur Route | ✅ Verified | Dari `routes/web.php` dan `api.php` |
| Model & Relasi | ✅ Verified | Dari semua file di `app/Models/` |
| Controller Logic | ✅ Verified | Dari semua Controller |
| Database Schema (Migration) | ✅ Verified | Dari semua Migration |
| Database Schema (Aktual) | ⚠️ Inferred | Diasumsikan sesuai Model, bukan Migration |
| View / UI | ✅ Observed | Dari semua Blade files |
| Business Rules | ✅ Verified | Dari kode & komentar |
| Data Produksi | ❌ Unknown | Tidak ada akses ke database live |
| Performance Aktual | ❌ Unknown | Tidak ada akses live |

---

*Dokumen ini dibuat berdasarkan analisis source code pada 2026-11-24.*
*Discovery Method: Static Code Analysis — tidak ada akses ke runtime/database live.*
