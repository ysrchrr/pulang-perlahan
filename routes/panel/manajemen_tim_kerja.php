<?php

use App\Http\Controllers\ManajemenJenisKegiatanController;
use App\Http\Controllers\ManajemenTimKerjaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/manajemen-tim-kerja', [ManajemenTimKerjaController::class, 'index'])->name('manajemen-tim-kerja');
    Route::get('/manajemen-tim-kerja-get-data', [ManajemenTimKerjaController::class, 'getData'])->name('manajemen-tim-kerja-get-data');
    Route::post('/manajemen-tim-kerja-store', [ManajemenTimKerjaController::class, 'store'])->name('manajemen-tim-kerja-store');
    Route::post('/manajemen-tim-kerja-detail', [ManajemenTimKerjaController::class, 'detail'])->name('manajemen-tim-kerja-detail');
    Route::post('/manajemen-tim-kerja-delete', [ManajemenTimKerjaController::class, 'delete'])->name('manajemen-tim-kerja-delete');
});
