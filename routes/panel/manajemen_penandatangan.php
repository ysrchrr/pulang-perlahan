<?php

use App\Http\Controllers\ManajemenPenandatanganController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/penandatangan-sertifikat', [ManajemenPenandatanganController::class, 'index'])->name('penandatangan-sertifikat');
    Route::get('/penandatangan-sertifikat-get-data', [ManajemenPenandatanganController::class, 'getData'])->name('penandatangan-sertifikat-get-data');
    Route::post('/penandatangan-sertifikat-store', [ManajemenPenandatanganController::class, 'store'])->name('penandatangan-sertifikat-store');
    Route::post('/penandatangan-sertifikat-detail', [ManajemenPenandatanganController::class, 'detail'])->name('penandatangan-sertifikat-detail');
    Route::post('/penandatangan-sertifikat-delete', [ManajemenPenandatanganController::class, 'delete'])->name('penandatangan-sertifikat-delete');
});
