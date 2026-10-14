<?php

namespace App\Enums;

enum KategoriPengaduan: string
{
    case Pelayanan = 'pelayanan';
    case Nikah = 'nikah';
    case Talak = 'talak';
    case Cerai = 'cerai';
    case Rujuk = 'rujuk';
    case Lainnya = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::Pelayanan => 'Pelayanan Petugas',
            self::Nikah => 'Layanan Nikah',
            self::Talak => 'Layanan Talak',
            self::Cerai => 'Layanan Cerai',
            self::Rujuk => 'Layanan Rujuk',
            self::Lainnya => 'Lainnya',
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $k) => ['value' => $k->value, 'label' => $k->label()], self::cases());
    }
}
