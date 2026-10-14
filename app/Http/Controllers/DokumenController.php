<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\PengajuanDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Berkas disimpan di disk privat; hanya pemilik dan petugas yang boleh membuka. */
class DokumenController extends Controller
{
    public function pengajuan(Request $request, PengajuanDokumen $dokumen)
    {
        $user = $request->user();
        abort_unless($user->isStaff() || $dokumen->pengajuan->user_id === $user->id, 403);
        abort_unless(Storage::disk('local')->exists($dokumen->path), 404, 'Berkas tidak ditemukan.');

        return Storage::disk('local')->response($dokumen->path, $dokumen->original_name);
    }

    public function pengaduan(Request $request, Pengaduan $pengaduan)
    {
        $user = $request->user();
        abort_unless($user->isStaff() || $pengaduan->user_id === $user->id, 403);
        abort_unless($pengaduan->lampiran_path && Storage::disk('local')->exists($pengaduan->lampiran_path), 404);

        return Storage::disk('local')->response($pengaduan->lampiran_path, $pengaduan->lampiran_nama);
    }
}
