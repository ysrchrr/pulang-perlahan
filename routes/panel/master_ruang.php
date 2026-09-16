<?php

use App\Http\Controllers\MasterRuangController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac', 'role:admin_prodi'])->group(function () {
    Route::get('/master-ruang', [MasterRuangController::class, 'index'])->name('master-ruang');
    Route::get('/master-ruang-get-data', [MasterRuangController::class, 'getData'])->name('master-ruang-get-data');
    Route::post('/master-ruang-store', [MasterRuangController::class, 'storeMasterRuang'])->name('master-ruang-store');
    Route::post('/master-ruang-get-detail', [MasterRuangController::class, 'getDetailRuang'])->name('master-ruang-get-detail');
    Route::post('/master-ruang-delete', [MasterRuangController::class, 'delete'])->name('master-ruang-delete');
    Route::get('/master-ruang-get-gedung', [MasterRuangController::class, 'getGedungList'])->name('master-ruang-get-gedung');
    Route::get('/master-ruang-export', [MasterRuangController::class, 'export'])->name('master-ruang-export');
    Route::get('/master-ruang-template', [MasterRuangController::class, 'template'])->name('master-ruang-template');
    Route::post('/master-ruang-import', [MasterRuangController::class, 'import'])->name('master-ruang-import');
});