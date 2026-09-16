<?php

use App\Http\Controllers\PetakomMasterInstrumenController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:petakom', 'rbac'])->group(function () {
    //master instrumen
    Route::get('/petakom-master-instrumen', [PetakomMasterInstrumenController::class, 'index'])->name('petakom-master-instrumen');
    Route::get('/petakom-master-instrumen-get-data', [PetakomMasterInstrumenController::class, 'getData'])->name('petakom-master-instrumen-get-data');
    Route::get('/petakom-master-instrumen-create', [PetakomMasterInstrumenController::class, 'create'])->name('petakom-master-instrumen-create');
    Route::post('/petakom-master-instrumen-store', [PetakomMasterInstrumenController::class, 'store'])->name('petakom-master-instrumen-store');
    Route::post('/petakom-master-instrumen-detail', [PetakomMasterInstrumenController::class, 'detail'])->name('petakom-master-instrumen-detail');
    Route::post('/petakom-master-instrumen-delete', [PetakomMasterInstrumenController::class, 'delete'])->name('petakom-master-instrumen-delete');

    //soal instrumen
    Route::get('/petakom-master-instrumen-soal-template', [PetakomMasterInstrumenController::class, 'downloadTemplateSoal'])->name('petakom-master-instrumen-soal-template');
    Route::post('/petakom-master-instrumen-soal-import/{id_template}', [PetakomMasterInstrumenController::class, 'importSoal'])->name('petakom-master-instrumen-soal-import');
    Route::get('/petakom-master-instrumen-soal/{id_template}', [PetakomMasterInstrumenController::class, 'soal'])->name('petakom-master-instrumen-soal');
    Route::get('/petakom-master-instrumen-soal-get-data/{id_template}', [PetakomMasterInstrumenController::class, 'getSoalData'])->name('petakom-master-instrumen-soal-get-data');
    Route::post('/petakom-master-instrumen-soal-store', [PetakomMasterInstrumenController::class, 'storeSoal'])->name('petakom-master-instrumen-soal-store');
    Route::post('/petakom-master-instrumen-soal-detail', [PetakomMasterInstrumenController::class, 'detailSoal'])->name('petakom-master-instrumen-soal-detail');
    Route::post('/petakom-master-instrumen-soal-delete', [PetakomMasterInstrumenController::class, 'deleteSoal'])->name('petakom-master-instrumen-soal-delete');
});
