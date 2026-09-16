<?php

use App\Http\Controllers\ManajemenJenisKegiatanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/jenis-kegiatan', [ManajemenJenisKegiatanController::class, 'index'])->name('jenis-kegiatan');
    Route::get('/jenis-kegiatan-get-data', [ManajemenJenisKegiatanController::class, 'getData'])->name('jenis-kegiatan-get-data');
    Route::post('/jenis-kegiatan-store', [ManajemenJenisKegiatanController::class, 'store'])->name('jenis-kegiatan-store');
    Route::post('/jenis-kegiatan-detail', [ManajemenJenisKegiatanController::class, 'detail'])->name('jenis-kegiatan-detail');
    Route::post('/jenis-kegiatan-delete', [ManajemenJenisKegiatanController::class, 'delete'])->name('jenis-kegiatan-delete');
});
