<?php

namespace App\Http\Controllers;

use App\Enums\JenisLayanan;
use App\Enums\StatusPengajuan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PengajuanController extends Controller
{
    private const ATURAN_FILE = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];

    public function index(Request $request): Response
    {
        $pengajuan = $request->user()->pengajuan()
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Pengajuan/Index', [
            'pengajuan' => $pengajuan,
            'filter' => $request->only('status'),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Pengajuan/Create', [
            'jenisAwal' => JenisLayanan::tryFrom((string) $request->query('jenis'))?->value,
        ]);
    }

    public function store(Request $request)
    {
        $jenis = JenisLayanan::tryFrom((string) $request->input('jenis'));

        $rules = [
            'jenis' => ['required', Rule::enum(JenisLayanan::class)],
            'nama_pasangan' => ['required', 'string', 'max:100'],
            'nik_pasangan' => ['required', 'digits:16'],
            'telepon_pasangan' => ['nullable', 'string', 'max:20'],
            'alamat_pasangan' => ['required', 'string', 'max:255'],
            'tanggal_rencana' => [
                $jenis === JenisLayanan::Nikah ? 'required' : 'nullable',
                'date',
                'after_or_equal:'.now()->addDays($jenis?->minimalHari() ?? 1)->toDateString(),
            ],
            'lokasi_akad' => [
                Rule::requiredIf($jenis === JenisLayanan::Nikah),
                'nullable',
                Rule::in(['kua', 'luar_kua']),
            ],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'dokumen' => ['array'],
        ];

        $attributes = ['tanggal_rencana' => $jenis?->labelTanggal() ?? 'tanggal'];

        foreach ($jenis?->persyaratan() ?? [] as $syarat) {
            $rules["dokumen.{$syarat['key']}"] = [$syarat['wajib'] ? 'required' : 'nullable', ...self::ATURAN_FILE];
            $attributes["dokumen.{$syarat['key']}"] = $syarat['label'];
        }

        $data = $request->validate($rules, [], $attributes);

        $pengajuan = DB::transaction(function () use ($request, $data, $jenis) {
            $pengajuan = $request->user()->pengajuan()->create([
                ...collect($data)->except('dokumen')->all(),
                'lokasi_akad' => $jenis === JenisLayanan::Nikah ? $data['lokasi_akad'] : null,
                'status' => StatusPengajuan::Diajukan,
            ]);

            foreach ($jenis->persyaratan() as $syarat) {
                $file = $request->file("dokumen.{$syarat['key']}");

                if ($file instanceof UploadedFile) {
                    $this->simpanDokumen($pengajuan, $syarat['label'], $file);
                }
            }

            $pengajuan->catatStatus(StatusPengajuan::Diajukan, 'Pengajuan dikirim oleh pemohon.', $request->user());

            return $pengajuan;
        });

        return redirect()
            ->route('pengajuan.show', $pengajuan)
            ->with('success', "Pengajuan berhasil dikirim. Simpan kode Anda: {$pengajuan->kode}");
    }

    public function show(Request $request, Pengajuan $pengajuan): Response
    {
        abort_unless($pengajuan->user_id === $request->user()->id, 403);

        $pengajuan->load(['dokumen', 'riwayat.user:id,name,role', 'petugas:id,name']);

        return Inertia::render('Pengajuan/Show', [
            'pengajuan' => $pengajuan,
            'bisaUnggah' => $pengajuan->status->bisaUnggah(),
        ]);
    }

    /** Pemohon menambah/memperbaiki dokumen. */
    public function unggahDokumen(Request $request, Pengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === $request->user()->id, 403);
        abort_unless($pengajuan->status->bisaUnggah(), 422, 'Dokumen tidak dapat diubah pada status ini.');

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'file' => ['required', ...self::ATURAN_FILE],
        ], [], ['nama' => 'nama dokumen', 'file' => 'berkas']);

        DB::transaction(function () use ($request, $pengajuan, $data) {
            $this->simpanDokumen($pengajuan, $data['nama'], $request->file('file'));

            if ($pengajuan->status === StatusPengajuan::PerluPerbaikan) {
                $pengajuan->update(['status' => StatusPengajuan::Diajukan]);
                $pengajuan->catatStatus(StatusPengajuan::Diajukan, "Pemohon mengunggah perbaikan: {$data['nama']}.", $request->user());
            }
        });

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    private function simpanDokumen(Pengajuan $pengajuan, string $nama, UploadedFile $file): void
    {
        $pengajuan->dokumen()->create([
            'nama' => $nama,
            'path' => $file->store("dokumen/{$pengajuan->id}", 'local'),
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
