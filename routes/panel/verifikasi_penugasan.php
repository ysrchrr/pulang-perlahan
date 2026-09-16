<?php

use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\ManajemenTimKerjaController;
use App\Http\Controllers\VerifikasiPenugasanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/verifikasi-penugasan', [VerifikasiPenugasanController::class, 'index'])->name('verifikasi-penugasan');
    Route::get('/verifikasi-penugasan-get-data', [VerifikasiPenugasanController::class, 'getData'])->name('verifikasi-penugasan-get-data');
    Route::get('/verifikasi-penugasan-process/{id}', [VerifikasiPenugasanController::class, 'verifikasiProses'])->name('verifikasi-penugasan-process');
    Route::post('/verifikasi-penugasan-store', [VerifikasiPenugasanController::class, 'store'])->name('verifikasi-penugasan-store');
    Route::post('/verifikasi-penugasan-delete', [VerifikasiPenugasanController::class, 'delete'])->name('verifikasi-penugasan-delete');

    Route::get('/verifikasi-penugasan-generate-st/{id}', [VerifikasiPenugasanController::class, 'generateSuratTugas'])->name('verifikasi-penugasan-generate-st');
});
