<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case Diajukan = 'diajukan';
    case Diverifikasi = 'diverifikasi';
    case PerluPerbaikan = 'perlu_perbaikan';
    case Dijadwalkan = 'dijadwalkan';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Diajukan => 'Diajukan',
            self::Diverifikasi => 'Berkas Terverifikasi',
            self::PerluPerbaikan => 'Perlu Perbaikan',
            self::Dijadwalkan => 'Dijadwalkan',
            self::Selesai => 'Selesai',
            self::Ditolak => 'Ditolak',
        };
    }

    /** Status yang masih menunggu tindakan petugas. */
    public static function menunggu(): array
    {
        return [self::Diajukan, self::Diverifikasi];
    }

    /** Pemohon boleh menambah dokumen pada status ini. */
    public function bisaUnggah(): bool
    {
        return in_array($this, [self::Diajukan, self::PerluPerbaikan], true);
    }

    public static function options(): array
    {
        return array_map(fn (self $s) => ['value' => $s->value, 'label' => $s->label()], self::cases());
    }
}
