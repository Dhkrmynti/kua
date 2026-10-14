<?php

namespace App\Http\Controllers;

use App\Enums\KategoriPengaduan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PengaduanController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Pengaduan/Index', [
            'pengaduan' => $request->user()->pengaduan()->latest()->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Pengaduan/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori' => ['required', Rule::enum(KategoriPengaduan::class)],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string', 'min:20', 'max:5000'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [], ['isi' => 'isi pengaduan']);

        $lampiran = $request->file('lampiran');

        $pengaduan = $request->user()->pengaduan()->create([
            'kategori' => $data['kategori'],
            'judul' => $data['judul'],
            'isi' => $data['isi'],
            'lampiran_path' => $lampiran?->store('pengaduan', 'local'),
            'lampiran_nama' => $lampiran?->getClientOriginalName(),
        ]);

        return redirect()
            ->route('pengaduan.show', $pengaduan)
            ->with('success', "Pengaduan terkirim. Kode pengaduan: {$pengaduan->kode}");
    }

    public function show(Request $request, Pengaduan $pengaduan): Response
    {
        abort_unless($pengaduan->user_id === $request->user()->id, 403);

        return Inertia::render('Pengaduan/Show', [
            'pengaduan' => $pengaduan->load('penanggap:id,name'),
        ]);
    }
}
