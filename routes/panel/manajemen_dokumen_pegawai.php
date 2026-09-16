<?php

use App\Http\Controllers\DokumenPegawaiController;
use App\Http\Controllers\ManajemenJenisKegiatanController;
use App\Http\Controllers\ManajemenTimKerjaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/manajemen-dokumen-pegawai', [DokumenPegawaiController::class, 'index'])->name('manajemen-dokumen-pegawai');
    Route::get('/manajemen-dokumen-pegawai-get-data', [DokumenPegawaiController::class, 'getData'])->name('manajemen-dokumen-pegawai-get-data');
    Route::post('/manajemen-dokumen-pegawai-store', [DokumenPegawaiController::class, 'store'])->name('manajemen-dokumen-pegawai-store');
    Route::post('/manajemen-dokumen-pegawai-detail', [DokumenPegawaiController::class, 'detail'])->name('manajemen-dokumen-pegawai-detail');
    Route::post('/manajemen-dokumen-pegawai-delete', [DokumenPegawaiController::class, 'delete'])->name('manajemen-dokumen-pegawai-delete');
});
