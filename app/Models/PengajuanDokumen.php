<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanDokumen extends Model
{
    protected $table = 'pengajuan_dokumen';

    protected $fillable = [
        'pengajuan_id',
        'nama',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    protected $hidden = ['path'];

    protected $appends = ['url'];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function getUrlAttribute(): string
    {
        return route('dokumen.show', $this);
    }
}
