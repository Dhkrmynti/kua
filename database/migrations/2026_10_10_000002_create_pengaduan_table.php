<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kategori', 20)->index();
            $table->string('judul');
            $table->text('isi');
            $table->string('lampiran_path')->nullable();
            $table->string('lampiran_nama')->nullable();
            $table->string('status', 20)->default('baru')->index();
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditanggapi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditanggapi_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
