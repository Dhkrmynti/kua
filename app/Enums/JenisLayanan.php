<?php

namespace App\Enums;

enum JenisLayanan: string
{
    case Nikah = 'nikah';
    case Talak = 'talak';
    case Cerai = 'cerai';
    case Rujuk = 'rujuk';

    public function label(): string
    {
        return match ($this) {
            self::Nikah => 'Nikah',
            self::Talak => 'Talak',
            self::Cerai => 'Cerai',
            self::Rujuk => 'Rujuk',
        };
    }

    /** Prefix kode pengajuan, mis. NKH-261010-A7K2. */
    public function prefix(): string
    {
        return match ($this) {
            self::Nikah => 'NKH',
            self::Talak => 'TLK',
            self::Cerai => 'CRI',
            self::Rujuk => 'RJK',
        };
    }

    public function deskripsi(): string
    {
        return match ($this) {
            self::Nikah => 'Pendaftaran kehendak nikah, pemeriksaan berkas, dan penjadwalan akad nikah di KUA maupun di luar KUA.',
            self::Talak => 'Konsultasi & mediasi (BP4) bagi suami yang hendak mengikrarkan talak, serta surat pengantar ke Pengadilan Agama.',
            self::Cerai => 'Konsultasi & mediasi (BP4) bagi istri yang hendak mengajukan gugatan cerai, serta surat pengantar ke Pengadilan Agama.',
            self::Rujuk => "Pencatatan rujuk bagi pasangan yang bercerai dengan talak raj'i dan masih dalam masa iddah.",
        };
    }

    public function labelPasangan(): string
    {
        return $this === self::Nikah ? 'Calon Pasangan' : 'Pasangan (Suami/Istri)';
    }

    public function labelTanggal(): string
    {
        return match ($this) {
            self::Nikah => 'Rencana tanggal akad nikah',
            self::Rujuk => 'Rencana tanggal rujuk',
            default => 'Tanggal konsultasi yang diinginkan',
        };
    }

    /** Minimal jarak (hari) antara tanggal pengajuan dan tanggal rencana. */
    public function minimalHari(): int
    {
        // Pendaftaran kehendak nikah paling lambat 10 hari kerja sebelum akad (PMA 20/2019).
        return $this === self::Nikah ? 10 : 1;
    }

    /**
     * Dokumen persyaratan per layanan.
     *
     * @return list<array{key: string, label: string, wajib: bool}>
     */
    public function persyaratan(): array
    {
        return match ($this) {
            self::Nikah => [
                ['key' => 'ktp_suami', 'label' => 'KTP calon suami', 'wajib' => true],
                ['key' => 'ktp_istri', 'label' => 'KTP calon istri', 'wajib' => true],
                ['key' => 'kk', 'label' => 'Kartu Keluarga kedua calon', 'wajib' => true],
                ['key' => 'akta_kelahiran', 'label' => 'Akta kelahiran / ijazah terakhir', 'wajib' => true],
                ['key' => 'pengantar_n1', 'label' => 'Surat pengantar nikah (N1–N4) dari desa/kelurahan', 'wajib' => true],
                ['key' => 'pas_foto', 'label' => 'Pas foto 2x3 & 4x6 latar biru', 'wajib' => true],
                ['key' => 'izin_ortu', 'label' => 'Surat izin orang tua (N5) — bila belum 21 tahun', 'wajib' => false],
                ['key' => 'dispensasi', 'label' => 'Dispensasi kawin dari Pengadilan Agama — bila belum 19 tahun', 'wajib' => false],
                ['key' => 'akta_cerai', 'label' => 'Akta cerai / surat kematian pasangan — bagi duda/janda', 'wajib' => false],
                ['key' => 'rekomendasi', 'label' => 'Rekomendasi nikah dari KUA asal — bila beda kecamatan', 'wajib' => false],
            ],
            self::Rujuk => [
                ['key' => 'ktp_suami', 'label' => 'KTP suami', 'wajib' => true],
                ['key' => 'ktp_istri', 'label' => 'KTP istri', 'wajib' => true],
                ['key' => 'kk', 'label' => 'Kartu Keluarga', 'wajib' => true],
                ['key' => 'akta_cerai', 'label' => 'Kutipan akta cerai dari Pengadilan Agama', 'wajib' => true],
                ['key' => 'pengantar_desa', 'label' => 'Surat keterangan/pengantar dari desa/kelurahan', 'wajib' => true],
                ['key' => 'pas_foto', 'label' => 'Pas foto 2x3 latar biru', 'wajib' => true],
            ],
            self::Talak, self::Cerai => [
                ['key' => 'ktp', 'label' => 'KTP pemohon', 'wajib' => true],
                ['key' => 'buku_nikah', 'label' => 'Kutipan akta nikah (buku nikah)', 'wajib' => true],
                ['key' => 'kk', 'label' => 'Kartu Keluarga', 'wajib' => true],
                ['key' => 'ktp_pasangan', 'label' => 'KTP pasangan', 'wajib' => false],
                ['key' => 'pengantar_desa', 'label' => 'Surat pengantar dari desa/kelurahan', 'wajib' => false],
            ],
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $j) => [
            'value' => $j->value,
            'label' => $j->label(),
            'deskripsi' => $j->deskripsi(),
            'labelPasangan' => $j->labelPasangan(),
            'labelTanggal' => $j->labelTanggal(),
            'minimalHari' => $j->minimalHari(),
            'persyaratan' => $j->persyaratan(),
        ], self::cases());
    }
}
