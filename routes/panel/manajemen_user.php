<?php

use App\Http\Controllers\ManajemenUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    Route::get('manajemen-user', [ManajemenUserController::class, 'index'])->name('manajemen-user');
    Route::get('manajemen-user-data', [ManajemenUserController::class, 'getData'])->name('manajemen-user-data');
    Route::post('manajemen-user-detail', [ManajemenUserController::class, 'getDetail'])->name('manajemen-user-detail');
    Route::post('manajemen-user-store', [ManajemenUserController::class, 'store'])->name('manajemen-user-store');
    Route::post('manajemen-user-delete', [ManajemenUserController::class, 'delete'])->name('manajemen-user-delete');
    Route::post('manajemen-user-reset-password', [ManajemenUserController::class, 'resetPassword'])->name('manajemen-user-reset-password');
    Route::post('impersonate', [ManajemenUserController::class, 'impersonate'])->name('manajemen-user-impersonate');
    Route::post('send-account', [ManajemenUserController::class, 'sendAccount'])->name('manajemen-user-send-account');
});
