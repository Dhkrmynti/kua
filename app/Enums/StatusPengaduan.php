<?php

namespace App\Enums;

enum StatusPengaduan: string
{
    case Baru = 'baru';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Baru => 'Baru',
            self::Diproses => 'Diproses',
            self::Selesai => 'Selesai',
            self::Ditolak => 'Ditolak',
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $s) => ['value' => $s->value, 'label' => $s->label()], self::cases());
    }
}
