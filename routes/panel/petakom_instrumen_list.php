<?php

use App\Http\Controllers\PetakomInstrumenListController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:petakom', 'rbac'])->group(function () {
    Route::get('/petakom-instrumen-list', [PetakomInstrumenListController::class, 'index'])->name('petakom-instrumen-list');
    Route::post('/petakom-instrumen-list-enrollment', [PetakomInstrumenListController::class, 'enrollment'])->name('petakom-instrumen-list-enrollment');
});
