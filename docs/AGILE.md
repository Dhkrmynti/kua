# Dokumen Pengembangan Agile (Scrum)

**Proyek:** Sistem Informasi Pengajuan & Pengaduan Layanan Nikah, Talak, Cerai, dan Rujuk pada KUA
**Metode:** Agile – kerangka kerja Scrum
**Stack:** Laravel 12 · Inertia.js 2 · Vue 3 · Tailwind CSS 4 · SQLite/MySQL

---

## 1. Mengapa Agile?

Kebutuhan KUA (alur berkas, persyaratan, format catatan petugas) sering baru jelas setelah petugas mencoba sistemnya. Scrum cocok karena:

- Fitur dikirim bertahap per **sprint** (2 minggu), sehingga KUA bisa mencoba lebih awal.
- Masukan petugas/kepala KUA ditampung di **Sprint Review** lalu masuk ke backlog.
- Prioritas bisa berubah tanpa merombak keseluruhan rencana.

## 2. Peran Scrum

| Peran | Diisi oleh | Tanggung jawab |
|---|---|---|
| Product Owner | Kepala KUA / perwakilan | Menentukan prioritas backlog, menerima hasil sprint |
| Scrum Master | Ketua tim / mahasiswa peneliti | Memfasilitasi event Scrum, menghilangkan hambatan |
| Development Team | Pengembang (1–3 orang) | Analisis, desain, coding, pengujian |
| Stakeholder | Petugas KUA, penghulu, masyarakat | Memberi masukan saat Sprint Review |

## 3. Event Scrum

| Event | Waktu | Keluaran |
|---|---|---|
| Sprint Planning | Awal sprint, ±2 jam | Sprint Backlog & Sprint Goal |
| Daily Scrum | Setiap hari, 15 menit | Apa yang dikerjakan kemarin / hari ini / hambatan |
| Sprint Review | Akhir sprint, ±1 jam | Demo ke Product Owner, masukan baru |
| Sprint Retrospective | Setelah review, ±45 menit | Perbaikan cara kerja tim |

## 4. Aktor Sistem

- **Masyarakat (Pemohon):** mendaftar, mengajukan layanan, mengunggah dokumen, memantau status, membuat pengaduan.
- **Petugas KUA:** memverifikasi berkas, meminta perbaikan, menjadwalkan, menyelesaikan/menolak, menanggapi pengaduan.
- **Admin:** semua hak petugas + mengelola akun petugas.
- **Pengunjung (tanpa login):** melihat informasi layanan & persyaratan, melacak status lewat kode.

## 5. Product Backlog

Prioritas memakai **MoSCoW** (M = Must, S = Should, C = Could). Estimasi memakai **Story Point** (deret Fibonacci).

| ID | User Story | Prioritas | SP | Sprint |
|---|---|---|---|---|
| PB-01 | Sebagai **pengunjung**, saya ingin melihat informasi dan persyaratan tiap layanan agar dapat menyiapkan berkas. | M | 3 | 1 |
| PB-02 | Sebagai **masyarakat**, saya ingin mendaftar akun dengan NIK agar dapat mengajukan layanan. | M | 3 | 1 |
| PB-03 | Sebagai **pengguna**, saya ingin masuk dan keluar dari sistem dengan aman. | M | 2 | 1 |
| PB-04 | Sebagai **sistem**, saya perlu membedakan hak akses admin, petugas, dan masyarakat. | M | 3 | 1 |
| PB-05 | Sebagai **masyarakat**, saya ingin mengajukan layanan nikah/talak/cerai/rujuk beserta data pasangan. | M | 8 | 2 |
| PB-06 | Sebagai **masyarakat**, saya ingin mengunggah dokumen persyaratan sesuai jenis layanan. | M | 5 | 2 |
| PB-07 | Sebagai **masyarakat**, saya ingin mendapat kode pengajuan unik untuk melacak berkas. | M | 2 | 2 |
| PB-08 | Sebagai **masyarakat**, saya ingin melihat daftar dan detail pengajuan saya. | M | 3 | 2 |
| PB-09 | Sebagai **petugas**, saya ingin melihat, mencari, dan memfilter semua pengajuan. | M | 5 | 3 |
| PB-10 | Sebagai **petugas**, saya ingin mengubah status pengajuan (verifikasi, perlu perbaikan, jadwal, selesai, tolak) beserta catatan. | M | 5 | 3 |
| PB-11 | Sebagai **petugas**, saya ingin menetapkan jadwal akad/konsultasi beserta lokasinya. | M | 3 | 3 |
| PB-12 | Sebagai **masyarakat**, saya ingin melihat riwayat (timeline) perubahan status. | S | 3 | 3 |
| PB-13 | Sebagai **masyarakat**, saya ingin mengunggah ulang dokumen saat diminta perbaikan. | S | 3 | 3 |
| PB-14 | Sebagai **masyarakat**, saya ingin menyampaikan pengaduan disertai lampiran bukti. | M | 5 | 4 |
| PB-15 | Sebagai **petugas**, saya ingin menanggapi dan mengubah status pengaduan. | M | 3 | 4 |
| PB-16 | Sebagai **pengunjung**, saya ingin melacak status berkas memakai kode tanpa login. | S | 3 | 4 |
| PB-17 | Sebagai **petugas**, saya ingin dashboard ringkasan (jumlah per status/layanan, jadwal mendatang). | S | 5 | 5 |
| PB-18 | Sebagai **admin**, saya ingin menambah dan menghapus akun petugas. | S | 3 | 5 |
| PB-19 | Sebagai **pengguna**, saya ingin memperbarui profil dan kata sandi. | C | 2 | 5 |
| PB-20 | Sebagai **masyarakat**, saya ingin menerima notifikasi email/WhatsApp saat status berubah. | C | 5 | Backlog |
| PB-21 | Sebagai **petugas**, saya ingin mencetak/ekspor laporan bulanan (PDF/Excel). | C | 5 | Backlog |
| PB-22 | Sebagai **petugas**, saya ingin melihat kalender jadwal akad nikah. | C | 5 | Backlog |

**Total SP terencana (Sprint 1–5):** 69 SP · **Velocity rata-rata:** ±14 SP/sprint

## 6. Rencana Sprint

### Sprint 1 — Fondasi & Autentikasi (13 SP)
**Sprint Goal:** Pengunjung dapat melihat layanan, mendaftar, dan masuk sesuai perannya.
PB-01, PB-02, PB-03, PB-04

| Task | Berkas terkait |
|---|---|
| Setup Laravel 12 + Inertia + Vue 3 + Tailwind 4 | `composer.json`, `vite.config.js`, `resources/js/app.js` |
| Migrasi tabel `users` (role, NIK, telepon, alamat) | `database/migrations/0001_01_01_000000_create_users_table.php` |
| Enum peran & middleware `role:` | `app/Enums/Role.php`, `app/Http/Middleware/EnsureRole.php` |
| Halaman beranda + daftar persyaratan | `resources/js/Pages/Home.vue`, `app/Enums/JenisLayanan.php` |
| Login, daftar, keluar (dengan rate limit) | `app/Http/Controllers/Auth/AuthController.php`, `Pages/Auth/*` |

### Sprint 2 — Pengajuan Layanan (18 SP)
**Sprint Goal:** Masyarakat dapat mengirim pengajuan lengkap dengan dokumen.
PB-05, PB-06, PB-07, PB-08

| Task | Berkas terkait |
|---|---|
| Migrasi `pengajuan`, `pengajuan_dokumen`, `pengajuan_riwayat` | `database/migrations/2026_10_10_000001_create_pengajuan_tables.php` |
| Formulir wizard 4 langkah | `resources/js/Pages/Pengajuan/Create.vue` |
| Validasi dinamis per jenis layanan (dokumen wajib, H-10 akad nikah) | `app/Http/Controllers/PengajuanController.php` |
| Penyimpanan berkas di disk privat + unduh dengan otorisasi | `app/Http/Controllers/DokumenController.php` |
| Generator kode unik (NKH/TLK/CRI/RJK-yymmdd-XXXX) | `app/Models/Pengajuan.php` |

### Sprint 3 — Verifikasi oleh Petugas (19 SP)
**Sprint Goal:** Petugas dapat memproses pengajuan hingga selesai dan pemohon dapat memantaunya.
PB-09, PB-10, PB-11, PB-12, PB-13

| Task | Berkas terkait |
|---|---|
| Daftar pengajuan + pencarian & filter | `app/Http/Controllers/Admin/PengajuanController.php`, `Pages/Admin/Pengajuan/Index.vue` |
| Form perbarui status, jadwal, catatan (dengan template) | `Pages/Admin/Pengajuan/Show.vue` |
| Timeline riwayat status | `resources/js/Components/Timeline.vue` |
| Unggah perbaikan dokumen (status kembali ke "Diajukan") | `PengajuanController@unggahDokumen` |

### Sprint 4 — Pengaduan & Pelacakan (11 SP)
**Sprint Goal:** Masyarakat dapat menyampaikan pengaduan dan siapa pun dapat melacak berkas.
PB-14, PB-15, PB-16

| Task | Berkas terkait |
|---|---|
| Migrasi `pengaduan` | `database/migrations/2026_10_10_000002_create_pengaduan_table.php` |
| Form & daftar pengaduan masyarakat | `Pages/Pengaduan/*` |
| Tanggapan petugas | `Pages/Admin/Pengaduan/*` |
| Lacak publik dengan penyamaran nama | `HomeController@lacak`, `Pages/Lacak.vue` |

### Sprint 5 — Dashboard & Administrasi (10 SP)
**Sprint Goal:** Pengelola memiliki gambaran kinerja layanan dan dapat mengelola petugas.
PB-17, PB-18, PB-19

| Task | Berkas terkait |
|---|---|
| Dashboard petugas & masyarakat | `DashboardController`, `Pages/Dashboard/*` |
| Manajemen akun petugas | `Admin/UserController`, `Pages/Admin/Users/Index.vue` |
| Profil & ganti kata sandi | `ProfilController`, `Pages/Profil.vue` |

## 7. Definition of Done (DoD)

Sebuah user story dinyatakan **selesai** bila:

1. Kode sudah di-merge ke branch utama dan lolos `npm run build`.
2. Lolos *feature test* terkait (`php artisan test`).
3. Validasi input dan otorisasi akses sudah diterapkan.
4. Tampilan responsif (diuji di lebar ponsel 360 px dan desktop).
5. Pesan dan label memakai Bahasa Indonesia.
6. Sudah didemokan dan diterima Product Owner pada Sprint Review.

## 8. Kriteria Penerimaan (contoh)

**PB-05/06 – Pengajuan nikah**
- *Given* pemohon sudah login, *when* memilih layanan Nikah dan mengisi tanggal akad kurang dari 10 hari dari hari ini, *then* sistem menolak dengan pesan validasi.
- *Given* dokumen wajib belum diunggah, *when* pemohon menekan Kirim, *then* sistem menandai dokumen yang kurang.
- *Given* data lengkap, *when* dikirim, *then* sistem membuat kode `NKH-…`, status **Diajukan**, dan entri riwayat pertama.

**PB-10 – Perbarui status**
- *Given* petugas memilih status **Perlu Perbaikan** atau **Ditolak**, *then* catatan wajib diisi.
- *Given* petugas memilih **Dijadwalkan**, *then* tanggal & jam wajib diisi.
- *Given* pengguna berperan masyarakat, *when* mengakses endpoint petugas, *then* sistem mengembalikan 403.

Kriteria di atas diotomatisasi di `tests/Feature/LayananTest.php`.

## 9. Alur Status Pengajuan

```
Diajukan ──► Berkas Terverifikasi ──► Dijadwalkan ──► Selesai
   │  ▲               │
   │  └── (pemohon unggah perbaikan)
   ▼                  ▼
Perlu Perbaikan     Ditolak
```

## 10. Burndown (template)

| Hari | Sprint 1 | Sprint 2 | Sprint 3 | Sprint 4 | Sprint 5 |
|---|---|---|---|---|---|
| 0 | 13 | 18 | 19 | 11 | 10 |
| 5 | | | | | |
| 10 | 0 | 0 | 0 | 0 | 0 |

Isi kolom hari ke-5 dengan sisa story point aktual saat sprint berjalan.

## 11. Catatan Domain

- Pendaftaran kehendak nikah paling lambat **10 hari kerja** sebelum akad (PMA No. 20 Tahun 2019). Sistem memakai batas 10 hari kalender sebagai validasi minimal; petugas tetap memeriksa hari kerja.
- Nikah di KUA pada jam kerja **Rp0**; di luar KUA/di luar jam kerja dikenai PNBP **Rp600.000** (PP No. 48 Tahun 2014 jo. PP No. 59 Tahun 2018).
- **Talak & cerai** diputus oleh **Pengadilan Agama**. Di sistem ini, layanan talak/cerai di KUA berupa konsultasi/mediasi BP4 dan surat pengantar.
- **Rujuk** dicatat KUA bila masih dalam masa iddah talak raj'i.
