<?php

namespace App\Models;

use App\Enums\KategoriPengaduan;
use App\Enums\StatusPengaduan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'kode',
        'user_id',
        'kategori',
        'judul',
        'isi',
        'lampiran_path',
        'lampiran_nama',
        'status',
        'tanggapan',
        'ditanggapi_oleh',
        'ditanggapi_at',
    ];

    protected $attributes = [
        'status' => 'baru',
    ];

    protected $hidden = ['lampiran_path'];

    protected $appends = ['lampiran_url'];

    protected function casts(): array
    {
        return [
            'kategori' => KategoriPengaduan::class,
            'status' => StatusPengaduan::class,
            'ditanggapi_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Pengaduan $pengaduan) {
            if (! $pengaduan->kode) {
                do {
                    $kode = 'ADU-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
                } while (static::where('kode', $kode)->exists());

                $pengaduan->kode = $kode;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penanggap(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditanggapi_oleh');
    }

    public function getLampiranUrlAttribute(): ?string
    {
        return $this->lampiran_path ? route('pengaduan.lampiran', $this) : null;
    }
}
