<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\StatusPengajuan;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    private function user(Role $role = Role::Masyarakat, array $extra = []): User
    {
        static $n = 0;
        $n++;

        return User::create([
            'name' => "User {$n}",
            'email' => "user{$n}@test.id",
            'password' => 'password',
            'role' => $role,
            ...$extra,
        ]);
    }

    public function test_halaman_publik_dapat_dibuka(): void
    {
        $this->withoutVite();

        $this->get('/')->assertOk();
        $this->get('/lacak?kode=TIDAK-ADA')->assertOk();
        $this->get('/login')->assertOk();
    }

    public function test_masyarakat_dapat_mendaftar(): void
    {
        $this->post('/register', [
            'name' => 'Budi',
            'nik' => '3201010101010001',
            'email' => 'budi@test.id',
            'telepon' => '0812',
            'alamat' => 'Desa A',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'budi@test.id', 'role' => 'masyarakat']);
    }

    public function test_pengajuan_nikah_wajib_dokumen_dan_tersimpan(): void
    {
        Storage::fake('local');
        $warga = $this->user();

        $payload = [
            'jenis' => 'nikah',
            'nama_pasangan' => 'Siti',
            'nik_pasangan' => '3201010101010002',
            'alamat_pasangan' => 'Desa B',
            'tanggal_rencana' => now()->addDays(20)->toDateString(),
            'lokasi_akad' => 'kua',
        ];

        // Tanpa dokumen wajib -> gagal.
        $this->actingAs($warga)->post('/pengajuan', $payload)
            ->assertSessionHasErrors(['dokumen.ktp_suami', 'dokumen.kk']);

        $dokumen = [];
        foreach (['ktp_suami', 'ktp_istri', 'kk', 'akta_kelahiran', 'pengantar_n1', 'pas_foto'] as $key) {
            $dokumen[$key] = UploadedFile::fake()->create("{$key}.pdf", 100, 'application/pdf');
        }

        $this->actingAs($warga)->post('/pengajuan', [...$payload, 'dokumen' => $dokumen])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $pengajuan = Pengajuan::first();
        $this->assertStringStartsWith('NKH-', $pengajuan->kode);
        $this->assertSame(StatusPengajuan::Diajukan, $pengajuan->status);
        $this->assertCount(6, $pengajuan->dokumen);
        $this->assertCount(1, $pengajuan->riwayat);
    }

    public function test_tanggal_akad_minimal_sepuluh_hari(): void
    {
        $this->actingAs($this->user())->post('/pengajuan', [
            'jenis' => 'nikah',
            'tanggal_rencana' => now()->addDays(3)->toDateString(),
        ])->assertSessionHasErrors('tanggal_rencana');
    }

    public function test_petugas_dapat_mengubah_status_dan_masyarakat_tidak(): void
    {
        $warga = $this->user();
        $petugas = $this->user(Role::Petugas);

        $pengajuan = $warga->pengajuan()->create([
            'jenis' => 'rujuk',
            'nama_pasangan' => 'Siti',
            'nik_pasangan' => '3201010101010002',
            'alamat_pasangan' => 'Desa B',
        ]);

        $this->actingAs($warga)
            ->patch("/admin/pengajuan/{$pengajuan->id}/status", ['status' => 'selesai'])
            ->assertForbidden();

        $this->actingAs($petugas)
            ->patch("/admin/pengajuan/{$pengajuan->id}/status", ['status' => 'dijadwalkan'])
            ->assertSessionHasErrors('jadwal');

        $this->actingAs($petugas)
            ->patch("/admin/pengajuan/{$pengajuan->id}/status", [
                'status' => 'dijadwalkan',
                'jadwal' => now()->addDays(5)->format('Y-m-d\TH:i'),
                'lokasi_jadwal' => 'Balai Nikah',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(StatusPengajuan::Dijadwalkan, $pengajuan->fresh()->status);
    }

    public function test_masyarakat_tidak_bisa_melihat_pengajuan_orang_lain(): void
    {
        $pemilik = $this->user();
        $lain = $this->user();

        $pengajuan = $pemilik->pengajuan()->create([
            'jenis' => 'talak',
            'nama_pasangan' => 'X',
            'nik_pasangan' => '3201010101010002',
            'alamat_pasangan' => 'Desa B',
        ]);

        $this->actingAs($lain)->get("/pengajuan/{$pengajuan->id}")->assertForbidden();
    }

    public function test_semua_halaman_dapat_dibuka_sesuai_peran(): void
    {
        $this->withoutVite();
        Storage::fake('local');
        $this->seed();

        $this->assertGreaterThanOrEqual(30, Pengajuan::count());
        $this->assertTrue(Storage::disk('local')->exists(Pengajuan::first()->dokumen()->first()->path));

        $warga = User::where('email', 'warga@kua.test')->first();
        $admin = User::where('email', 'admin@kua.test')->first();
        $pengajuan = $warga->pengajuan()->first();
        $pengaduan = $warga->pengaduan()->first();

        foreach (['/dashboard', '/pengajuan', '/pengajuan/create?jenis=nikah', "/pengajuan/{$pengajuan->id}", '/pengaduan', '/pengaduan/create', "/pengaduan/{$pengaduan->id}", '/profil'] as $url) {
            $this->actingAs($warga)->get($url)->assertOk();
        }

        foreach (['/dashboard', '/admin/pengajuan', '/admin/pengajuan?status=diajukan&q=Ahmad', "/admin/pengajuan/{$pengajuan->id}", '/admin/pengaduan', "/admin/pengaduan/{$pengaduan->id}", '/admin/users', '/profil'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $this->get("/lacak?kode={$pengajuan->kode}")->assertOk()->assertSee($pengajuan->kode);
        $this->actingAs($warga)->get('/admin/pengajuan')->assertForbidden();
    }

    public function test_pengaduan_dan_tanggapan(): void
    {
        $warga = $this->user();
        $admin = $this->user(Role::Admin);

        $this->actingAs($warga)->post('/pengaduan', [
            'kategori' => 'pelayanan',
            'judul' => 'Pelayanan lambat',
            'isi' => 'Saya menunggu lama sekali di loket pendaftaran nikah.',
        ])->assertSessionHasNoErrors();

        $pengaduan = $warga->pengaduan()->first();
        $this->assertStringStartsWith('ADU-', $pengaduan->kode);

        $this->actingAs($admin)->patch("/admin/pengaduan/{$pengaduan->id}", [
            'status' => 'selesai',
            'tanggapan' => 'Terima kasih, loket akan kami tambah.',
        ])->assertSessionHasNoErrors();

        $this->assertSame('selesai', $pengaduan->fresh()->status->value);
    }
}
