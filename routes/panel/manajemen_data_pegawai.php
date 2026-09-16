<?php

use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\ManajemenTimKerjaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/data-pegawai', [DataPegawaiController::class, 'index'])->name('data-pegawai');
    Route::get('/data-pegawai-template', [DataPegawaiController::class, 'downloadTemplate'])->name('data-pegawai-template');
    Route::get('/data-pegawai-get-data', [DataPegawaiController::class, 'getData'])->name('data-pegawai-get-data');
    Route::post('/data-pegawai-import', [DataPegawaiController::class, 'import'])->name('data-pegawai-import');
    Route::get('/data-pegawai-create', [DataPegawaiController::class, 'create'])->name('data-pegawai-create');
    Route::get('/data-pegawai-edit/{id}', [DataPegawaiController::class, 'edit'])->name('data-pegawai-edit');
    Route::post('/data-pegawai-store', [DataPegawaiController::class, 'store'])->name('data-pegawai-store');
    Route::post('/data-pegawai-update', [DataPegawaiController::class, 'update'])->name('data-pegawai-update');
    Route::post('/data-pegawai-update-keaktifan', [DataPegawaiController::class, 'updateKeaktifan'])->name('data-pegawai-update-keaktifan');
    Route::post('/data-pegawai-detail', [DataPegawaiController::class, 'detail'])->name('data-pegawai-detail');
    Route::post('/data-pegawai-delete', [DataPegawaiController::class, 'delete'])->name('data-pegawai-delete');

    Route::post('/data-pegawai-create-account', [DataPegawaiController::class, 'createAccount'])->name('data-pegawai-create-account');
});
