<?php

namespace Database\Seeders;

use App\Enums\JenisLayanan;
use App\Enums\Role;
use App\Enums\StatusPengajuan as S;
use App\Models\Pengaduan;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Data demo yang "terlihat hidup" untuk presentasi.
 * PERINGATAN: menghapus folder berkas dokumen & pengaduan. Jangan jalankan di data asli.
 */
class DemoSeeder extends Seeder
{
    private const DESA = ['Sukamaju', 'Sukasari', 'Mekarjaya', 'Margahayu', 'Tanjungsari', 'Karangmulya', 'Sindangsari', 'Cibening'];

    private const NAMA_PRIA = ['Muhammad', 'Dimas', 'Fajar', 'Rizal', 'Andi', 'Bayu', 'Hendra', 'Agus', 'Ilham', 'Yusuf', 'Arif', 'Fikri', 'Reza', 'Teguh', 'Wahyu', 'Irfan', 'Taufik', 'Rendi', 'Galih', 'Hafiz', 'Zaki', 'Ridho', 'Fauzan', 'Akbar', 'Iqbal', 'Rafi', 'Daffa', 'Hasan', 'Lukman', 'Nanda', 'Sandi', 'Yoga'];

    private const NAMA_WANITA = ['Nur', 'Dewi', 'Fitri', 'Rina', 'Ayu', 'Intan', 'Nabila', 'Zahra', 'Annisa', 'Laila', 'Putri', 'Wulan', 'Indah', 'Mega', 'Sri', 'Yuni', 'Aulia', 'Salma', 'Kartika', 'Hana', 'Rahma', 'Lina', 'Dina', 'Tika', 'Nisa', 'Alya', 'Citra', 'Eka', 'Ratna', 'Vina', 'Melati', 'Syifa'];

    private const BELAKANG_PRIA = ['Pratama', 'Nugroho', 'Hidayat', 'Saputra', 'Setiawan', 'Gunawan', 'Ramadhan', 'Maulana', 'Rahman', 'Firmansyah', 'Santoso', 'Kurniawan', 'Hakim', 'Kusuma', 'Permana', 'Mubarak'];

    private const BELAKANG_WANITA = ['Aminah', 'Lestari', 'Handayani', 'Marlina', 'Wulandari', 'Permatasari', 'Salsabila', 'Rahmawati', 'Fitriani', 'Maharani', 'Sari', 'Purnamasari', 'Kartini', 'Wahyuni', 'Astuti', 'Ningsih'];

    private const CATATAN = [
        'diverifikasi' => [
            'Berkas telah diperiksa dan dinyatakan lengkap.',
            'Dokumen lengkap dan sesuai. Menunggu penjadwalan.',
        ],
        'perlu_perbaikan' => [
            'Foto KTP calon istri buram, mohon unggah ulang dengan pencahayaan yang cukup.',
            'Surat pengantar dari desa belum ditandatangani kepala desa. Mohon unggah versi yang sudah ditandatangani.',
            'Pas foto belum berlatar biru. Mohon unggah ulang sesuai ketentuan.',
        ],
        'dijadwalkan' => [
            'Harap hadir 30 menit sebelum jadwal dengan membawa dokumen asli, wali, dan 2 orang saksi.',
            'Mohon hadir tepat waktu bersama kedua belah pihak untuk sesi konsultasi BP4.',
        ],
        'selesai' => [
            'Akad nikah telah dilaksanakan. Buku nikah dapat diambil di kantor KUA.',
            'Layanan telah selesai dilaksanakan.',
        ],
        'ditolak' => [
            'Pengajuan tidak dapat diproses karena calon mempelai berdomisili di luar wilayah kecamatan. Silakan mengajukan ke KUA sesuai domisili.',
            'Pengajuan ditutup atas permintaan pemohon.',
        ],
    ];

    private int $urut = 0;

    public function run(): void
    {
        mt_srand(20261010);
        Storage::disk('local')->deleteDirectory('dokumen');
        Storage::disk('local')->deleteDirectory('pengaduan');

        Model::unguarded(fn () => $this->isi());
    }

    private function isi(): void
    {
        // Hash sekali saja; cast 'hashed' tidak meng-hash ulang nilai yang sudah ter-hash.
        $password = Hash::make(config('kua.demo_password'));

        $petugas = User::where('email', 'petugas@kua.test')->first();
        $penghulu = User::create([
            'name' => 'H. Muhammad Ridwan, S.Ag.',
            'email' => 'penghulu@kua.test',
            'password' => $password,
            'role' => Role::Petugas,
        ]);
        $staf = [$petugas, $penghulu];

        // [jenis, status, dibuat N hari lalu]
        $rencana = [
            ['nikah', 'diajukan', 0], ['nikah', 'diajukan', 1], ['cerai', 'diajukan', 1], ['nikah', 'diajukan', 2],
            ['talak', 'diajukan', 3], ['rujuk', 'diajukan', 2], ['nikah', 'diajukan', 4],
            ['nikah', 'diverifikasi', 5], ['nikah', 'diverifikasi', 6], ['cerai', 'diverifikasi', 4],
            ['talak', 'diverifikasi', 7], ['nikah', 'diverifikasi', 8],
            ['nikah', 'perlu_perbaikan', 6], ['rujuk', 'perlu_perbaikan', 9], ['nikah', 'perlu_perbaikan', 10],
            ['nikah', 'dijadwalkan', 14], ['nikah', 'dijadwalkan', 16], ['nikah', 'dijadwalkan', 18], ['cerai', 'dijadwalkan', 9],
            ['talak', 'dijadwalkan', 11], ['nikah', 'dijadwalkan', 20], ['rujuk', 'dijadwalkan', 12],
            ['nikah', 'selesai', 30], ['nikah', 'selesai', 35], ['nikah', 'selesai', 41], ['talak', 'selesai', 25],
            ['cerai', 'selesai', 28], ['nikah', 'selesai', 47], ['rujuk', 'selesai', 38], ['nikah', 'selesai', 55],
            ['nikah', 'ditolak', 20], ['cerai', 'ditolak', 15],
        ];

        // Jadwal yang akan datang: [+hari, jam]
        $jadwalMendatang = [[1, 8], [2, 9], [3, 10], [3, 13], [5, 9], [6, 10], [8, 8]];

        $pemohonList = [];

        foreach ($rencana as $i => [$jenis, $status, $hariLalu]) {
            $jenis = JenisLayanan::from($jenis);
            $status = S::from($status);
            $pemohonWanita = $jenis === JenisLayanan::Cerai; // cerai gugat diajukan istri

            $pemohon = $this->buatWarga($pemohonWanita ? $this->namaWanita($i) : $this->namaPria($i), $pemohonWanita, $password);
            $pemohonList[] = $pemohon;
            $namaPasangan = $pemohonWanita ? $this->namaPria($i + 7) : $this->namaWanita($i + 3);

            $dibuat = now()->subDays($hariLalu)->setTime(mt_rand(8, 15), mt_rand(0, 59));

            $jadwal = null;
            if ($status === S::Dijadwalkan) {
                [$plus, $jam] = array_shift($jadwalMendatang);
                $jadwal = now()->addDays($plus)->setTime($jam, 0);
            } elseif ($status === S::Selesai) {
                $jadwal = $dibuat->copy()->addDays(mt_rand(11, 14))->setTime(mt_rand(8, 10), 0);
            }

            $this->buatPengajuan($pemohon, $jenis, $status, $dibuat, $jadwal, [
                'nama' => $namaPasangan,
                'nik' => $this->nik(! $pemohonWanita, $i + 100),
                'telepon' => mt_rand(0, 2) ? $this->telepon($i + 50) : null,
                'alamat' => $this->alamat($i + 3),
            ], $staf[$i % 2]);
        }

        // Pengajuan milik akun demo warga@kua.test
        $ahmad = User::where('email', 'warga@kua.test')->first();
        $this->buatPengajuan($ahmad, JenisLayanan::Nikah, S::Dijadwalkan, now()->subDays(8)->setTime(9, 12), now()->addDays(4)->setTime(9, 0), [
            'nama' => 'Siti Aminah',
            'nik' => '3201014101950002',
            'telepon' => '081298765432',
            'alamat' => 'Jl. Kenanga No. 12, Desa Sukamaju',
        ], $penghulu, 'Mohon dijadwalkan pagi hari.');

        $this->buatPengaduan($ahmad, $petugas, $pemohonList);
    }

    private function buatPengajuan(User $pemohon, JenisLayanan $jenis, S $status, Carbon $dibuat, ?Carbon $jadwal, array $pasangan, User $petugas, ?string $keterangan = null): Pengajuan
    {
        $nikah = $jenis === JenisLayanan::Nikah;
        $luarKua = $nikah && mt_rand(1, 3) === 1;
        $catatan = isset(self::CATATAN[$status->value]) ? $this->pilih(self::CATATAN[$status->value]) : null;

        if ($status === S::Selesai && ! $nikah) {
            $catatan = 'Layanan telah selesai dilaksanakan.';
        }

        $lokasiJadwal = match (true) {
            $jadwal === null => null,
            $nikah && $luarKua => 'Kediaman mempelai wanita, '.$pasangan['alamat'],
            $nikah => 'Balai Nikah KUA',
            default => 'Ruang Konsultasi BP4 KUA',
        };

        $tanggalRencana = match (true) {
            $jadwal !== null && in_array($jenis, [JenisLayanan::Nikah, JenisLayanan::Rujuk], true) => $jadwal->toDateString(),
            $nikah => $dibuat->copy()->addDays(mt_rand(14, 30))->toDateString(),
            $jenis === JenisLayanan::Rujuk => $dibuat->copy()->addDays(mt_rand(7, 14))->toDateString(),
            default => null,
        };

        $pengajuan = Pengajuan::create([
            'kode' => $jenis->prefix().'-'.$dibuat->format('ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => $pemohon->id,
            'jenis' => $jenis,
            'nama_pasangan' => $pasangan['nama'],
            'nik_pasangan' => $pasangan['nik'],
            'telepon_pasangan' => $pasangan['telepon'],
            'alamat_pasangan' => $pasangan['alamat'],
            'tanggal_rencana' => $tanggalRencana,
            'lokasi_akad' => $nikah ? ($luarKua ? 'luar_kua' : 'kua') : null,
            'keterangan' => $keterangan,
            'status' => $status,
            'catatan_petugas' => $status === S::Diajukan ? null : $catatan,
            'jadwal' => $jadwal,
            'lokasi_jadwal' => $lokasiJadwal,
            'petugas_id' => $status === S::Diajukan ? null : $petugas->id,
            'created_at' => $dibuat,
            'updated_at' => $dibuat,
        ]);

        // Berkas contoh untuk setiap persyaratan wajib
        foreach ($jenis->persyaratan() as $syarat) {
            if (! $syarat['wajib']) {
                continue;
            }
            $path = "dokumen/{$pengajuan->id}/{$syarat['key']}.pdf";
            $isi = $this->pdf($syarat['label'], $pemohon->name, $pengajuan->kode);
            Storage::disk('local')->put($path, $isi);
            $pengajuan->dokumen()->create([
                'nama' => $syarat['label'],
                'path' => $path,
                'original_name' => $syarat['key'].'.pdf',
                'mime' => 'application/pdf',
                'size' => strlen($isi),
                'created_at' => $dibuat,
                'updated_at' => $dibuat,
            ]);
        }

        // Riwayat status yang konsisten dengan status akhir
        $langkah = match ($status) {
            S::Diajukan => [S::Diajukan],
            S::Diverifikasi => [S::Diajukan, S::Diverifikasi],
            S::PerluPerbaikan => [S::Diajukan, S::PerluPerbaikan],
            S::Dijadwalkan => [S::Diajukan, S::Diverifikasi, S::Dijadwalkan],
            S::Selesai => [S::Diajukan, S::Diverifikasi, S::Dijadwalkan, S::Selesai],
            S::Ditolak => [S::Diajukan, S::Ditolak],
        };

        foreach ($langkah as $n => $s) {
            $waktu = match (true) {
                $n === 0 => $dibuat,
                $s === S::Selesai => $jadwal->copy()->addHours(1),
                default => $dibuat->copy()->addDays($n)->setTime(mt_rand(9, 15), mt_rand(0, 59)),
            };
            $waktu = $waktu->greaterThan(now()) ? now()->subMinutes(30 - $n) : $waktu;

            $pengajuan->riwayat()->create([
                'status' => $s,
                'catatan' => match (true) {
                    $n === 0 => 'Pengajuan dikirim oleh pemohon.',
                    $s === $status => $catatan,
                    default => self::CATATAN[$s->value][0] ?? null,
                },
                'user_id' => $n === 0 ? $pemohon->id : $petugas->id,
                'created_at' => $waktu,
                'updated_at' => $waktu,
            ]);
        }

        return $pengajuan;
    }

    private function buatPengaduan(User $ahmad, User $petugas, array $warga): void
    {
        $data = [
            [$ahmad, 'pelayanan', 'Antrean pelayanan terlalu lama', 'Pada hari Senin kemarin saya menunggu hampir dua jam untuk konsultasi berkas nikah. Mohon ditambah petugas di loket pendaftaran pada jam sibuk.', 'baru', null, 1],
            [$ahmad, 'nikah', 'Informasi biaya akad di luar KUA', 'Saya ingin memastikan berapa biaya resmi jika akad nikah dilaksanakan di rumah, dan bagaimana cara pembayarannya agar tidak salah.', 'selesai', 'Biaya resmi akad nikah di luar KUA atau di luar jam kerja adalah PNBP Rp600.000 yang dibayarkan melalui bank persepsi/kode billing, bukan kepada petugas. Akad di Balai Nikah KUA pada jam kerja tidak dipungut biaya.', 12],
            [$warga[22], 'pelayanan', 'Terima kasih atas pelayanan yang ramah', 'Saya ingin menyampaikan apresiasi kepada petugas yang membantu proses berkas kami sampai akad. Sangat sabar menjelaskan persyaratan.', 'selesai', 'Terima kasih atas apresiasinya. Semoga menjadi keluarga yang sakinah, mawaddah, wa rahmah.', 20],
            [$warga[4], 'lainnya', 'Sulit mengunggah dokumen dari HP', 'Saat mengunggah foto KTP dari HP, berkas ditolak karena terlalu besar. Mohon diberi petunjuk cara mengecilkan ukuran foto.', 'diproses', 'Terima kasih masukannya. Sementara ini foto dapat dikompres menggunakan fitur "kirim sebagai dokumen" di WhatsApp atau aplikasi kompres foto hingga di bawah 2 MB. Kami sedang menyiapkan panduan bergambar.', 3],
            [$warga[13], 'rujuk', 'Verifikasi berkas rujuk belum ada kabar', 'Sudah lebih dari seminggu berkas rujuk saya belum diverifikasi, sementara masa iddah tinggal sebentar lagi. Mohon segera ditindaklanjuti.', 'diproses', 'Mohon maaf atas keterlambatannya. Berkas Anda sudah kami periksa dan ada dokumen yang perlu diperbaiki, silakan cek catatan petugas pada halaman pengajuan.', 2],
            [$warga[2], 'cerai', 'Mohon jadwal mediasi secepatnya', 'Kami membutuhkan jadwal konsultasi BP4 secepatnya karena perkara akan segera didaftarkan ke Pengadilan Agama.', 'baru', null, 0],
            [$warga[26], 'pelayanan', 'Informasi di papan pengumuman berbeda dengan website', 'Daftar persyaratan nikah di papan pengumuman kantor berbeda dengan yang ada di website, sehingga saya bingung harus mengikuti yang mana.', 'selesai', 'Terima kasih atas temuannya. Papan pengumuman sudah kami perbarui dan kini sama dengan persyaratan di website.', 26],
            [$warga[24], 'lainnya', 'Ruang tunggu panas', 'Ruang tunggu kantor cukup panas saat siang hari dan kipas angin tidak menyala. Kasihan pengunjung yang membawa anak kecil.', 'selesai', 'Terima kasih masukannya. Kipas angin sudah diperbaiki dan kami sedang mengajukan pemasangan pendingin ruangan.', 33],
            [$warga[23], 'nikah', 'Salah penulisan nama ayah di buku nikah', 'Terdapat salah ketik pada nama ayah mempelai wanita di buku nikah kami. Bagaimana prosedur perbaikannya?', 'diproses', 'Silakan datang ke kantor KUA dengan membawa buku nikah asli, KTP, KK, dan akta kelahiran sebagai dasar perbaikan. Proses perbaikan dapat diselesaikan dalam 1 hari kerja.', 4],
        ];

        foreach ($data as [$user, $kategori, $judul, $isi, $status, $tanggapan, $hariLalu]) {
            $dibuat = now()->subDays($hariLalu)->setTime(mt_rand(8, 20), mt_rand(0, 59));
            $ditanggapi = $tanggapan ? min($dibuat->copy()->addHours(mt_rand(3, 30)), now()->subMinutes(10)) : null;

            Pengaduan::create([
                'kode' => 'ADU-'.$dibuat->format('ymd').'-'.strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'kategori' => $kategori,
                'judul' => $judul,
                'isi' => $isi,
                'status' => $status,
                'tanggapan' => $tanggapan,
                'ditanggapi_oleh' => $tanggapan ? $petugas->id : null,
                'ditanggapi_at' => $ditanggapi,
                'created_at' => $dibuat,
                'updated_at' => $ditanggapi ?? $dibuat,
            ]);
        }
    }

    private function buatWarga(string $nama, bool $wanita, string $password): User
    {
        $this->urut++;
        $email = Str::of($nama)->lower()->replace(' ', '.')->ascii().'@contoh.id';

        return User::create([
            'name' => $nama,
            'email' => $email,
            'password' => $password,
            'role' => Role::Masyarakat,
            'nik' => $this->nik(! $wanita, $this->urut),
            'telepon' => $this->telepon($this->urut),
            'alamat' => $this->alamat($this->urut),
        ]);
    }

    private function namaPria(int $i): string
    {
        return self::NAMA_PRIA[$i % 32].' '.self::BELAKANG_PRIA[$i % 16];
    }

    private function namaWanita(int $i): string
    {
        return self::NAMA_WANITA[$i % 32].' '.self::BELAKANG_WANITA[($i * 5) % 16];
    }

    /** Format NIK: 3201 01 + ddmmyy (wanita: dd+40) + 4 digit urut. */
    private function nik(bool $pria, int $urut): string
    {
        $hari = ($urut * 7) % 28 + 1;

        return sprintf('320101%02d%02d%02d%04d', $pria ? $hari : $hari + 40, $urut % 12 + 1, 85 + $urut % 15, $urut);
    }

    private function telepon(int $urut): string
    {
        return '08'.['12', '13', '21', '52', '57', '77'][$urut % 6].sprintf('%08d', ($urut * 7919 + 12345678) % 100000000);
    }

    private function alamat(int $urut): string
    {
        $jalan = ['Melati', 'Mawar', 'Kenanga', 'Anggrek', 'Flamboyan', 'Cempaka', 'Dahlia', 'Teratai'];

        return sprintf('Jl. %s No. %d, RT %02d/RW %02d, Desa %s', $jalan[$urut % 8], $urut % 40 + 1, $urut % 9 + 1, $urut % 5 + 1, self::DESA[($urut * 3) % 8]);
    }

    private function pilih(array $pilihan): string
    {
        return $pilihan[mt_rand(0, count($pilihan) - 1)];
    }

    /** PDF satu halaman sederhana sebagai pengganti dokumen asli. */
    private function pdf(string $judul, string $nama, string $kode): string
    {
        $esc = fn (string $s) => str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], Str::ascii($s));

        $isi = "0.016 0.47 0.34 rg 0 782 595 60 re f\n"
            ."1 g BT /F1 18 Tf 50 806 Td (".$esc(config('kua.nama')).") Tj ET\n"
            ."0.6 g 50 560 495 190 re S\n"
            ."0 g BT /F1 22 Tf 70 715 Td (CONTOH DOKUMEN) Tj ET\n"
            ."BT /F1 14 Tf 70 680 Td (".$esc($judul).") Tj ET\n"
            ."BT /F1 12 Tf 70 645 Td (Atas nama     : ".$esc($nama).") Tj ET\n"
            ."BT /F1 12 Tf 70 625 Td (Kode pengajuan: ".$esc($kode).") Tj ET\n"
            ."0.45 g BT /F1 10 Tf 70 585 Td (Berkas ini dibuat otomatis untuk keperluan demonstrasi aplikasi.) Tj ET";

        $objek = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length '.strlen($isi)." >>\nstream\n{$isi}\nendstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offset = [];
        foreach ($objek as $n => $o) {
            $offset[] = strlen($pdf);
            $pdf .= ($n + 1)." 0 obj\n{$o}\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objek) + 1)."\n0000000000 65535 f \n";
        foreach ($offset as $o) {
            $pdf .= sprintf("%010d 00000 n \n", $o);
        }

        return $pdf.'trailer << /Size '.(count($objek) + 1)." /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
    }
}
