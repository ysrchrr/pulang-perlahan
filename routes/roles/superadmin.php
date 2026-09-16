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

    // Manajemen User
    // Route::get('manajemen-user', [SuperadminUserController::class, 'index'])->name('manajemen-user');
    // Route::get('manajemen-user-data', [SuperadminUserController::class, 'getData'])->name('manajemen-user-data');
    // Route::post('manajemen-user-detail', [SuperadminUserController::class, 'getDetail'])->name('manajemen-user-detail');
    // Route::post('manajemen-user-store', [SuperadminUserController::class, 'store'])->name('manajemen-user-store');
    // Route::post('manajemen-user-delete', [SuperadminUserController::class, 'delete'])->name('manajemen-user-delete');
    // Route::post('manajemen-user-reset-password', [SuperadminUserController::class, 'resetPassword'])->name('manajemen-user-reset-password');
    // Route::post('impersonate', [SuperadminUserController::class, 'impersonate'])->name('manajemen-user-impersonate');
    // Route::post('send-account', [SuperadminUserController::class, 'sendAccount'])->name('manajemen-user-send-account');

    // Manajemen Menu
    Route::get('manajemen-menu', [SuperadminMenuController::class, 'index'])->name('manajemen-menu');
    Route::get('manajemen-menu-data', [SuperadminMenuController::class, 'getData'])->name('manajemen-menu-data');
    Route::post('manajemen-menu-store', [SuperadminMenuController::class, 'storeMenu'])->name('manajemen-menu-store');
    Route::post('manajemen-menu-detail', [SuperadminMenuController::class, 'detailMenu'])->name('manajemen-menu-detail');
    Route::post('manajemen-menu-set-status', [SuperadminMenuController::class, 'setIsActive'])->name('manajemen-menu-set-status');
    Route::post('manajemen-menu-delete', [SuperadminMenuController::class, 'deleteMenu'])->name('manajemen-menu-delete');
});
