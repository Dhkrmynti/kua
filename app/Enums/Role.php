<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Petugas = 'petugas';
    case Masyarakat = 'masyarakat';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Petugas => 'Petugas KUA',
            self::Masyarakat => 'Masyarakat',
        };
    }
}
