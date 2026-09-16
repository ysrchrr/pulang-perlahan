<?php

use App\Http\Controllers\DiklatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/diklat', [DiklatController::class, 'index'])->name('diklat');
    Route::get('/diklat-data', [DiklatController::class, 'getData'])->name('diklat-data');
    Route::get('/diklat-enrollment', [DiklatController::class, 'diklatEnrollment'])->name('diklat-enrollment');
    Route::get('/diklat-enrollment/search', [DiklatController::class, 'searchKegiatan'])->name('diklat-enrollment.search');
    Route::post('/diklat-do-enroll', [DiklatController::class, 'doEnroll'])->name('diklat-do-enroll');

    Route::get('/diklat-berkas/{id_kegiatan}', [DiklatController::class, 'berkasPendaftaran'])->name('diklat-berkas');
    Route::post('/diklat-berkas-upload/{id_kegiatan}', [DiklatController::class, 'uploadBerkasPendaftaran'])->name('diklat-berkas-upload');
});
