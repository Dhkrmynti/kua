<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lacak', [HomeController::class, 'lacak'])->name('lacak');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Unduh berkas (dicek hak aksesnya di controller)
    Route::get('/dokumen/{dokumen}', [DokumenController::class, 'pengajuan'])->name('dokumen.show');
    Route::get('/pengaduan/{pengaduan}/lampiran', [DokumenController::class, 'pengaduan'])->name('pengaduan.lampiran');

    // Masyarakat
    Route::middleware('role:masyarakat')->group(function () {
        Route::resource('pengajuan', PengajuanController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('/pengajuan/{pengajuan}/dokumen', [PengajuanController::class, 'unggahDokumen'])->name('pengajuan.dokumen');
        Route::resource('pengaduan', PengaduanController::class)->only(['index', 'create', 'store', 'show']);
    });

    // Petugas & Admin
    Route::middleware('role:admin,petugas')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pengajuan', [Admin\PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [Admin\PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::patch('/pengajuan/{pengajuan}/status', [Admin\PengajuanController::class, 'updateStatus'])->name('pengajuan.status');

        Route::get('/pengaduan', [Admin\PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}', [Admin\PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::patch('/pengaduan/{pengaduan}', [Admin\PengaduanController::class, 'tanggapi'])->name('pengaduan.tanggapi');

        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
            Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
            Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
        });
    });
});
