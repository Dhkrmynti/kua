<?php

namespace App\Http\Controllers;

use App\Enums\JenisLayanan;
use App\Enums\StatusPengaduan;
use App\Enums\StatusPengajuan;
use App\Models\Pengaduan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        return $user->isStaff() ? $this->staff() : $this->masyarakat($user);
    }

    private function masyarakat($user): Response
    {
        $perStatus = $user->pengajuan()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return Inertia::render('Dashboard/Masyarakat', [
            'ringkasan' => [
                'total' => $perStatus->sum(),
                'proses' => $perStatus->only(['diajukan', 'diverifikasi', 'perlu_perbaikan', 'dijadwalkan'])->sum(),
                'selesai' => $perStatus->get('selesai', 0),
                'pengaduan' => $user->pengaduan()->count(),
            ],
            'pengajuan' => $user->pengajuan()->latest()->limit(5)->get(),
            'pengaduan' => $user->pengaduan()->latest()->limit(5)->get(),
            'jadwal' => $user->pengajuan()
                ->where('status', StatusPengajuan::Dijadwalkan)
                ->where('jadwal', '>=', now()->startOfDay())
                ->orderBy('jadwal')
                ->first(),
        ]);
    }

    private function staff(): Response
    {
        $perStatus = Pengajuan::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $perJenis = Pengajuan::selectRaw('jenis, count(*) as total')->groupBy('jenis')->pluck('total', 'jenis');

        return Inertia::render('Dashboard/Staff', [
            'ringkasan' => [
                'total' => $perStatus->sum(),
                'menunggu' => $perStatus->only(array_map(fn ($s) => $s->value, StatusPengajuan::menunggu()))->sum(),
                'dijadwalkan' => $perStatus->get('dijadwalkan', 0),
                'selesai' => $perStatus->get('selesai', 0),
                'pengaduanBaru' => Pengaduan::where('status', StatusPengaduan::Baru)->count(),
            ],
            'perStatus' => collect(StatusPengajuan::cases())->map(fn ($s) => [
                'value' => $s->value,
                'total' => (int) $perStatus->get($s->value, 0),
            ]),
            'perJenis' => collect(JenisLayanan::cases())->map(fn ($j) => [
                'value' => $j->value,
                'total' => (int) $perJenis->get($j->value, 0),
            ]),
            'terbaru' => Pengajuan::with('user:id,name')
                ->whereIn('status', StatusPengajuan::menunggu())
                ->latest()
                ->limit(6)
                ->get(),
            'jadwal' => Pengajuan::with('user:id,name')
                ->where('status', StatusPengajuan::Dijadwalkan)
                ->where('jadwal', '>=', now()->startOfDay())
                ->orderBy('jadwal')
                ->limit(6)
                ->get(),
        ]);
    }
}
