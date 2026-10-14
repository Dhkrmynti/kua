<?php

namespace App\Http\Middleware;

use App\Enums\JenisLayanan;
use App\Enums\KategoriPengaduan;
use App\Enums\StatusPengaduan;
use App\Enums\StatusPengajuan;
use App\Models\Pengaduan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->value,
                    'roleLabel' => $user->role->label(),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'kua' => collect(config('kua'))->only(['nama', 'kabupaten', 'alamat', 'telepon', 'email', 'jam_layanan']),
            'enums' => fn () => [
                'jenis' => JenisLayanan::options(),
                'statusPengajuan' => StatusPengajuan::options(),
                'statusPengaduan' => StatusPengaduan::options(),
                'kategoriPengaduan' => KategoriPengaduan::options(),
            ],
            // Angka notifikasi di sidebar petugas.
            'badges' => fn () => $user?->isStaff() ? [
                'pengajuan' => Pengajuan::where('status', StatusPengajuan::Diajukan)->count(),
                'pengaduan' => Pengaduan::where('status', StatusPengaduan::Baru)->count(),
            ] : null,
        ];
    }
}
