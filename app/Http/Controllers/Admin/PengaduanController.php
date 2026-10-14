<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPengaduan;
use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PengaduanController extends Controller
{
    public function index(Request $request): Response
    {
        $filter = $request->only('q', 'kategori', 'status');

        $pengaduan = Pengaduan::with('user:id,name')
            ->when($filter['q'] ?? null, function ($query, $q) {
                $query->where(fn ($w) => $w->where('kode', 'like', "%{$q}%")->orWhere('judul', 'like', "%{$q}%"));
            })
            ->when($filter['kategori'] ?? null, fn ($q, $kategori) => $q->where('kategori', $kategori))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Pengaduan/Index', [
            'pengaduan' => $pengaduan,
            'filter' => $filter,
        ]);
    }

    public function show(Pengaduan $pengaduan): Response
    {
        return Inertia::render('Admin/Pengaduan/Show', [
            'pengaduan' => $pengaduan->load(['user:id,name,email,telepon', 'penanggap:id,name']),
        ]);
    }

    public function tanggapi(Request $request, Pengaduan $pengaduan)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(StatusPengaduan::class)],
            'tanggapan' => ['required', 'string', 'max:5000'],
        ]);

        $pengaduan->update([
            ...$data,
            'ditanggapi_oleh' => $request->user()->id,
            'ditanggapi_at' => now(),
        ]);

        return back()->with('success', 'Tanggapan berhasil disimpan.');
    }
}
