<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('jenis', 20)->index();
            $table->string('nama_pasangan');
            $table->string('nik_pasangan', 16);
            $table->string('telepon_pasangan', 20)->nullable();
            $table->string('alamat_pasangan');
            $table->date('tanggal_rencana')->nullable();
            $table->string('lokasi_akad', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status', 20)->default('diajukan')->index();
            $table->text('catatan_petugas')->nullable();
            $table->dateTime('jadwal')->nullable();
            $table->string('lokasi_jadwal')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pengajuan_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('nama');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 100)->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('pengajuan_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('status', 20);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_riwayat');
        Schema::dropIfExists('pengajuan_dokumen');
        Schema::dropIfExists('pengajuan');
    }
};
