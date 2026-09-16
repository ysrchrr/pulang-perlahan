<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\superadmin\SuperadminDashboardController;
use App\Http\Controllers\superadmin\SuperadminMenuController;
use App\Http\Controllers\superadmin\SuperadminRoleController;
use App\Http\Controllers\superadmin\SuperadminUserController;

Route::prefix('superadmin')->name('superadmin-')->group(function () {

    //Dashboard
    Route::get('dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Role
    Route::get('manajemen-role', [SuperadminRoleController::class, 'index'])->name('manajemen-role');
    Route::get('manajemen-role-data', [SuperadminRoleController::class, 'getData'])->name('manajemen-role-data');
    Route::post('manajemen-role-detail', [SuperadminRoleController::class, 'getdetail'])->name('manajemen-role-detail');
    Route::post('manajemen-role-store', [SuperadminRoleController::class, 'store'])->name('manajemen-role-store');
    Route::post('manajemen-role-set-status', [SuperadminRoleController::class, 'setIsActive'])->name('manajemen-role-set-status');
    Route::post('manajemen-role-delete', [SuperadminRoleController::class, 'delete'])->name('manajemen-role-delete');

    // Manajemen Menu
    Route::get('manajemen-menu', [SuperadminMenuController::class, 'index'])->name('manajemen-menu');
    Route::get('manajemen-menu-data', [SuperadminMenuController::class, 'getData'])->name('manajemen-menu-data');
    Route::post('manajemen-menu-store', [SuperadminMenuController::class, 'storeMenu'])->name('manajemen-menu-store');
    Route::post('manajemen-menu-detail', [SuperadminMenuController::class, 'detailMenu'])->name('manajemen-menu-detail');
    Route::post('manajemen-menu-set-status', [SuperadminMenuController::class, 'setIsActive'])->name('manajemen-menu-set-status');
    Route::post('manajemen-menu-delete', [SuperadminMenuController::class, 'deleteMenu'])->name('manajemen-menu-delete');
});
