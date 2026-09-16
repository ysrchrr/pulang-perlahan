<?php

use App\Http\Controllers\MasterGedungController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac', 'role:admin_prodi'])->group(function () {
    Route::get('/master-gedung', [MasterGedungController::class, 'index'])->name('master-gedung');
    Route::get('/master-gedung-get-data', [MasterGedungController::class, 'getData'])->name('master-gedung-get-data');
    Route::post('/master-gedung-store', [MasterGedungController::class, 'storeMasterGedung'])->name('master-gedung-store');
    Route::post('/master-gedung-get-detail', [MasterGedungController::class, 'getDetailGedung'])->name('master-gedung-get-detail');
    Route::post('/master-gedung-delete', [MasterGedungController::class, 'delete'])->name('master-gedung-delete');
    
    // Import/Export
    Route::get('/master-gedung-export', [MasterGedungController::class, 'export'])->name('master-gedung-export');
    Route::get('/master-gedung-template', [MasterGedungController::class, 'template'])->name('master-gedung-template');
    Route::post('/master-gedung-import', [MasterGedungController::class, 'import'])->name('master-gedung-import');
});