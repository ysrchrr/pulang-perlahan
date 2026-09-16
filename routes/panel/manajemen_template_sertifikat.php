<?php

use App\Http\Controllers\ManajemenPenandatanganController;
use App\Http\Controllers\ManajemenTemplateSertifikatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/template-sertifikat', [ManajemenTemplateSertifikatController::class, 'index'])->name('template-sertifikat');
    Route::get('/template-sertifikat-get-data', [ManajemenTemplateSertifikatController::class, 'getData'])->name('template-sertifikat-get-data');
    Route::get('/template-sertifikat-preview/{id}', [ManajemenTemplateSertifikatController::class, 'preview'])->name('template-sertifikat-preview');
    Route::post('/template-sertifikat-store', [ManajemenTemplateSertifikatController::class, 'store'])->name('template-sertifikat-store');
    Route::post('/template-sertifikat-detail', [ManajemenTemplateSertifikatController::class, 'detail'])->name('template-sertifikat-detail');
    Route::post('/template-sertifikat-delete', [ManajemenTemplateSertifikatController::class, 'delete'])->name('template-sertifikat-delete');
});
