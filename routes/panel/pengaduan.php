<?php

use App\Http\Controllers\PengaduanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac', 'role:admin_prodi,bagian_umum'])->group(function () {
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan');
    Route::get('/pengaduan/data', [PengaduanController::class, 'getData'])->name('pengaduan-data');
    Route::get('/pengaduan/ruang-by-gedung', [PengaduanController::class, 'getRuangByGedung'])->name('pengaduan-ruang-by-gedung');
    Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan-store')->middleware('permission:pengaduan,create');
    Route::post('/pengaduan/delete', [PengaduanController::class, 'destroy'])->name('pengaduan-destroy')->middleware('permission:pengaduan,delete');

    // Verifikasi (Bagian Umum)
    Route::get('/pengaduan/verifikasi/{id}', [PengaduanController::class, 'verifikasi'])->name('pengaduan-verifikasi');
    Route::post('/pengaduan/verifikasi/{id}', [PengaduanController::class, 'storeVerifikasi'])->name('pengaduan-store-verifikasi');
});