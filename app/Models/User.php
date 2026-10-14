<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'telepon',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [Role::Admin, Role::Petugas], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class);
    }
}
