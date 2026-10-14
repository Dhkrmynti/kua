<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPengajuan;
use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PengajuanController extends Controller
{
    public function index(Request $request): Response
    {
        $filter = $request->only('q', 'jenis', 'status');

        $pengajuan = Pengajuan::with('user:id,name,nik')
            ->when($filter['q'] ?? null, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('kode', 'like', "%{$q}%")
                        ->orWhere('nama_pasangan', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('nik', 'like', "%{$q}%"));
                });
            })
            ->when($filter['jenis'] ?? null, fn ($q, $jenis) => $q->where('jenis', $jenis))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Pengajuan/Index', [
            'pengajuan' => $pengajuan,
            'filter' => $filter,
        ]);
    }

    public function show(Pengajuan $pengajuan): Response
    {
        $pengajuan->load(['user', 'dokumen', 'riwayat.user:id,name,role', 'petugas:id,name']);

        return Inertia::render('Admin/Pengajuan/Show', [
            'pengajuan' => $pengajuan,
        ]);
    }

    public function updateStatus(Request $request, Pengajuan $pengajuan)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(StatusPengajuan::class)],
            'catatan' => [
                Rule::requiredIf(in_array($request->input('status'), ['perlu_perbaikan', 'ditolak'], true)),
                'nullable', 'string', 'max:2000',
            ],
            'jadwal' => [Rule::requiredIf($request->input('status') === 'dijadwalkan'), 'nullable', 'date'],
            'lokasi_jadwal' => ['nullable', 'string', 'max:255'],
        ], [], ['jadwal' => 'jadwal', 'lokasi_jadwal' => 'lokasi']);

        $status = StatusPengajuan::from($data['status']);

        DB::transaction(function () use ($request, $pengajuan, $data, $status) {
            $pengajuan->update([
                'status' => $status,
                'catatan_petugas' => $data['catatan'] ?? $pengajuan->catatan_petugas,
                'jadwal' => $status === StatusPengajuan::Dijadwalkan ? $data['jadwal'] : $pengajuan->jadwal,
                'lokasi_jadwal' => $status === StatusPengajuan::Dijadwalkan ? ($data['lokasi_jadwal'] ?? null) : $pengajuan->lokasi_jadwal,
                'petugas_id' => $request->user()->id,
            ]);

            $pengajuan->catatStatus($status, $data['catatan'] ?? null, $request->user());
        });

        return back()->with('success', "Status pengajuan diperbarui menjadi \"{$status->label()}\".");
    }
}
