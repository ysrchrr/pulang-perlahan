<?php

use App\Http\Controllers\DashboardPetakomController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:petakom', 'rbac'])->group(function () {
    Route::get('/petakom-dashboard', [DashboardPetakomController::class, 'index'])->name('petakom-dashboard');
});
