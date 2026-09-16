<?php

use App\Http\Controllers\ManajemenEvalNarasumberController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/eval-narasumber', [ManajemenEvalNarasumberController::class, 'index'])->name('eval-narasumber');
    Route::get('/eval-narasumber-get-data', [ManajemenEvalNarasumberController::class, 'getData'])->name('eval-narasumber-get-data');
    Route::get('/eval-narasumber-create', [ManajemenEvalNarasumberController::class, 'create'])->name('eval-narasumber-create');
    Route::post('/eval-narasumber-store', [ManajemenEvalNarasumberController::class, 'store'])->name('eval-narasumber-store');
    Route::post('/eval-narasumber-detail', [ManajemenEvalNarasumberController::class, 'detail'])->name('eval-narasumber-detail');
    Route::post('/eval-narasumber-delete', [ManajemenEvalNarasumberController::class, 'delete'])->name('eval-narasumber-delete');

    Route::get('/eval-narasumber-soal/{id_template}', [ManajemenEvalNarasumberController::class, 'soal'])->name('eval-narasumber-soal');
    Route::get('/eval-narasumber-soal-get-data/{id_template}', [ManajemenEvalNarasumberController::class, 'getSoalData'])->name('eval-narasumber-soal-get-data');
    Route::post('/eval-narasumber-soal-store', [ManajemenEvalNarasumberController::class, 'storeSoal'])->name('eval-narasumber-soal-store');
    Route::post('/eval-narasumber-soal-detail', [ManajemenEvalNarasumberController::class, 'detailSoal'])->name('eval-narasumber-soal-detail');
    Route::post('/eval-narasumber-soal-delete', [ManajemenEvalNarasumberController::class, 'deleteSoal'])->name('eval-narasumber-soal-delete');
});
