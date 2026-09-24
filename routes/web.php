<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/sess', function () {
    return app('session')->all();
});

Route::get('/dev/enc_id/{id}', function ($id) {
    return enc_id($id);
})->whereNumber('id');

Route::get('/dev/dec_id/{value}', function ($value) {
    $decrypted = dec_id($value);
    if ($decrypted === false || $decrypted === null) {
        return response('Invalid encrypted value', 400);
    }
    return $decrypted;
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('doLogin', [AuthController::class, 'doLogin'])->name('doLogin');
Route::get('auth/google', [AuthController::class, 'authGoogle'])->name('auth-google');
Route::get('auth/google/callback', [AuthController::class, 'authGoogleCallback'])->name('auth-google-callback');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('stop-impersonate', [App\Http\Controllers\superadmin\SuperadminUserController::class, 'stopImpersonate'])->name('stop-impersonate');
Route::get('choose-role', [AuthController::class, 'chooseRole'])->name('choose-role');
Route::post('choose-role-set', [AuthController::class, 'setRole'])->name('choose-role-set');

Route::middleware(['rbac:superadmin'])->group(function () {
    require __DIR__ . '/roles/superadmin.php';
});

Route::middleware(['auth'])->group(function () {
    //User
    require __DIR__ . '/panel/dashboard.php';
    require __DIR__ . '/panel/manajemen_user.php';
});
