<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'statistik' => [
                'pengajuan' => Pengajuan::count(),
                'selesai' => Pengajuan::where('status', 'selesai')->count(),
                'pengaduan' => Pengaduan::count(),
            ],
        ]);
    }

    /** Lacak status pengajuan/pengaduan berdasarkan kode, tanpa login. */
    public function lacak(Request $request): Response
    {
        $kode = strtoupper(trim((string) $request->query('kode', '')));
        $hasil = null;

        if ($kode !== '') {
            if (str_starts_with($kode, 'ADU-')) {
                $pengaduan = Pengaduan::with('user:id,name')->where('kode', $kode)->first();

                if ($pengaduan) {
                    $hasil = [
                        'tipe' => 'pengaduan',
                        'kode' => $pengaduan->kode,
                        'nama' => $this->samarkan($pengaduan->user->name),
                        'kategori' => $pengaduan->kategori->label(),
                        'judul' => $pengaduan->judul,
                        'status' => $pengaduan->status->value,
                        'dibuat' => $pengaduan->created_at,
                        'ditanggapi_at' => $pengaduan->ditanggapi_at,
                    ];
                }
            } else {
                $pengajuan = Pengajuan::with(['user:id,name', 'riwayat'])->where('kode', $kode)->first();

                if ($pengajuan) {
                    $hasil = [
                        'tipe' => 'pengajuan',
                        'kode' => $pengajuan->kode,
                        'nama' => $this->samarkan($pengajuan->user->name),
                        'jenis' => $pengajuan->jenis->value,
                        'status' => $pengajuan->status->value,
                        'jadwal' => $pengajuan->jadwal,
                        'dibuat' => $pengajuan->created_at,
                        'riwayat' => $pengajuan->riwayat->map(fn ($r) => [
                            'status' => $r->status->value,
                            'waktu' => $r->created_at,
                        ]),
                    ];
                }
            }
        }

        return Inertia::render('Lacak', [
            'kode' => $kode,
            'hasil' => $hasil,
        ]);
    }

    /** "Ahmad Fauzi" -> "Ah*** Fa***" */
    private function samarkan(string $nama): string
    {
        return collect(explode(' ', $nama))
            ->map(fn ($kata) => Str::substr($kata, 0, 2).str_repeat('*', max(Str::length($kata) - 2, 1)))
            ->implode(' ');
    }
}
