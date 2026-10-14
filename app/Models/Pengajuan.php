<?php

namespace App\Models;

use App\Enums\JenisLayanan;
use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = [
        'kode',
        'user_id',
        'jenis',
        'nama_pasangan',
        'nik_pasangan',
        'telepon_pasangan',
        'alamat_pasangan',
        'tanggal_rencana',
        'lokasi_akad',
        'keterangan',
        'status',
        'catatan_petugas',
        'jadwal',
        'lokasi_jadwal',
        'petugas_id',
    ];

    protected $attributes = [
        'status' => 'diajukan',
    ];

    protected function casts(): array
    {
        return [
            'jenis' => JenisLayanan::class,
            'status' => StatusPengajuan::class,
            'tanggal_rencana' => 'date:Y-m-d',
            'jadwal' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Pengajuan $pengajuan) {
            $pengajuan->kode ??= static::buatKode($pengajuan->jenis->prefix());
        });
    }

    public static function buatKode(string $prefix): string
    {
        do {
            $kode = $prefix.'-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(PengajuanDokumen::class);
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(PengajuanRiwayat::class)->latest('id');
    }

    public function catatStatus(StatusPengajuan $status, ?string $catatan = null, ?User $oleh = null): void
    {
        $this->riwayat()->create([
            'status' => $status,
            'catatan' => $catatan,
            'user_id' => $oleh?->id,
        ]);
    }
}
