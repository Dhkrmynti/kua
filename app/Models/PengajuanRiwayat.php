<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanRiwayat extends Model
{
    protected $table = 'pengajuan_riwayat';

    protected $fillable = [
        'pengajuan_id',
        'status',
        'catatan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusPengajuan::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
