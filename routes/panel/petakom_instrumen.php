<?php

use App\Http\Controllers\PetakomInstrumen;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:petakom', 'rbac'])->group(function () {
    Route::get('/petakom-instrumen/{id_instrumen}', [PetakomInstrumen::class, 'index'])->name('petakom-instrumen');
    Route::post('/petakom-instrumen/{id_instrumen}/autosave', [PetakomInstrumen::class, 'autosave'])->name('petakom-instrumen-autosave');
    Route::post('/petakom-instrumen/{id_instrumen}/selesai', [PetakomInstrumen::class, 'selesai'])->name('petakom-instrumen-selesai');
});
