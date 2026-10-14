# Layanan KUA — Pengajuan & Pengaduan Nikah, Talak, Cerai, Rujuk

Aplikasi web untuk Kantor Urusan Agama (KUA). Masyarakat dapat mengajukan layanan dan menyampaikan pengaduan secara daring, sedangkan petugas memverifikasi dan menjadwalkan.

**Stack:** Laravel 12 · Inertia.js 2 · Vue 3 (Composition API) · Tailwind CSS 4 · SQLite (bisa MySQL)
**Metode pengembangan:** Agile/Scrum — lihat [`docs/AGILE.md`](docs/AGILE.md) (product backlog, user story, sprint, DoD).

## Fitur

| Pengunjung | Masyarakat | Petugas | Admin |
|---|---|---|---|
| Info layanan & persyaratan | Daftar dengan NIK | Dashboard statistik | Semua fitur petugas |
| Lacak status via kode | Pengajuan wizard 4 langkah | Cari & filter pengajuan | Kelola akun petugas |
| | Unggah dokumen per persyaratan | Verifikasi / minta perbaikan / tolak | |
| | Timeline status & jadwal | Tetapkan jadwal + lokasi | |
| | Unggah perbaikan dokumen | Tanggapi pengaduan | |
| | Pengaduan + lampiran | | |

- Dokumen disimpan di **disk privat** (`storage/app/private`) dan hanya bisa dibuka pemilik atau petugas.
- Persyaratan dokumen tiap layanan diatur di satu tempat: `app/Enums/JenisLayanan.php`.

## Instalasi

Prasyarat: **PHP ≥ 8.2** (ekstensi `pdo_sqlite`/`pdo_mysql`, `mbstring`, `openssl`, `fileinfo`), **Composer**, **Node.js ≥ 18**.
Di Windows paling mudah memakai [Laragon](https://laragon.org) atau [Laravel Herd](https://herd.laravel.com).

```bash
composer install
npm install

cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed    # SQLite: file database/database.sqlite sudah disediakan

npm run build                 # atau `npm run dev` saat pengembangan
php artisan serve
```

Buka http://localhost:8000

### Akun demo (dari seeder)

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | admin@kua.test | password |
| Petugas | petugas@kua.test | password |
| Masyarakat | warga@kua.test | password |

### Memakai MySQL

Ubah di `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kua
DB_USERNAME=root
DB_PASSWORD=
```

Lalu `php artisan migrate:fresh --seed`.

### Identitas KUA

Atur nama, alamat, telepon, dan jam layanan di `.env` (`KUA_NAMA`, `KUA_ALAMAT`, dst.).

## Pengujian

```bash
php artisan test
```

## Struktur penting

```
app/Enums/                  Jenis layanan, persyaratan, status, peran
app/Http/Controllers/       Controller masyarakat
app/Http/Controllers/Admin/ Controller petugas & admin
app/Http/Middleware/        EnsureRole (role:admin,petugas), HandleInertiaRequests
database/migrations/        users, pengajuan(+dokumen, riwayat), pengaduan
resources/js/Pages/         Halaman Vue (Inertia)
resources/js/Components/    StatusBadge, Timeline, Pagination, dll.
docs/AGILE.md               Dokumen Scrum
```
