<?php

use App\Http\Controllers\KategoriPengaduanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac', 'role:bagian_umum'])->group(function () {
    Route::get('/kategori-pengaduan', [KategoriPengaduanController::class, 'index'])->name('kategori-pengaduan');
    Route::get('/kategori-pengaduan-get-data', [KategoriPengaduanController::class, 'getData'])->name('kategori-pengaduan-get-data');
    Route::post('/kategori-pengaduan-store', [KategoriPengaduanController::class, 'storeKategoriPengaduan'])->name('kategori-pengaduan-store');
    Route::post('/kategori-pengaduan-get-detail', [KategoriPengaduanController::class, 'getDetailKategoriPengaduan'])->name('kategori-pengaduan-get-detail');
    Route::post('/kategori-pengaduan-delete', [KategoriPengaduanController::class, 'delete'])->name('kategori-pengaduan-delete');
});