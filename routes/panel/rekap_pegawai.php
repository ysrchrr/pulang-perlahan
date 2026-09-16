<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapPegawaiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('/rekap-pegawai', [RekapPegawaiController::class, 'index'])->name('rekap-pegawai');
});
