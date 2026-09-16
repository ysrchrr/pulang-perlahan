<?php

use App\Http\Controllers\ManajamenEvalPenyelenggaraanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/eval-penyelenggaraan', [ManajamenEvalPenyelenggaraanController::class, 'index'])->name('eval-penyelenggaraan');
    Route::get('/eval-penyelenggaraan-get-data', [ManajamenEvalPenyelenggaraanController::class, 'getData'])->name('eval-penyelenggaraan-get-data');
    Route::get('/eval-penyelenggaraan-create', [ManajamenEvalPenyelenggaraanController::class, 'create'])->name('eval-penyelenggaraan-create');
    Route::post('/eval-penyelenggaraan-store', [ManajamenEvalPenyelenggaraanController::class, 'store'])->name('eval-penyelenggaraan-store');
    Route::post('/eval-penyelenggaraan-detail', [ManajamenEvalPenyelenggaraanController::class, 'detail'])->name('eval-penyelenggaraan-detail');
    Route::post('/eval-penyelenggaraan-delete', [ManajamenEvalPenyelenggaraanController::class, 'delete'])->name('eval-penyelenggaraan-delete');

    Route::get('/eval-penyelenggaraan-soal/{id_template}', [ManajamenEvalPenyelenggaraanController::class, 'soal'])->name('eval-penyelenggaraan-soal');
    Route::get('/eval-penyelenggaraan-soal-get-data/{id_template}', [ManajamenEvalPenyelenggaraanController::class, 'getSoalData'])->name('eval-penyelenggaraan-soal-get-data');
    Route::post('/eval-penyelenggaraan-soal-store', [ManajamenEvalPenyelenggaraanController::class, 'storeSoal'])->name('eval-penyelenggaraan-soal-store');
    Route::post('/eval-penyelenggaraan-soal-detail', [ManajamenEvalPenyelenggaraanController::class, 'detailSoal'])->name('eval-penyelenggaraan-soal-detail');
    Route::post('/eval-penyelenggaraan-soal-delete', [ManajamenEvalPenyelenggaraanController::class, 'deleteSoal'])->name('eval-penyelenggaraan-soal-delete');
});
