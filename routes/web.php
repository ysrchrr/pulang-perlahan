<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePetakomController;
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
Route::get('signup', [AuthController::class, 'signup'])->name('signup');
Route::post('doSignup', [AuthController::class, 'doSignup'])->name('doSignup');
Route::get('auth/google', [AuthController::class, 'authGoogle'])->name('auth-google');
Route::get('auth/google/callback', [AuthController::class, 'authGoogleCallback'])->name('auth-google-callback');
Route::get('auth/google/petakom', [AuthController::class, 'authGooglePetakom'])->name('auth-google-petakom');
Route::get('auth/google/petakom/callback', [AuthController::class, 'authGooglePetakomCallback'])->name('auth-google-petakom-callback');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('stop-impersonate', [App\Http\Controllers\superadmin\SuperadminUserController::class, 'stopImpersonate'])->name('stop-impersonate');
Route::get('choose-role', [AuthController::class, 'chooseRole'])->name('choose-role');
Route::post('choose-role-set', [AuthController::class, 'setRole'])->name('choose-role-set');
Route::get('choose-role-petakom', [AuthController::class, 'chooseRolePetakom'])->name('choose-role-petakom');
Route::post('choose-role-petakom-set', [AuthController::class, 'setRolePetakom'])->name('choose-role-petakom-set');
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/peserta', [ProfileController::class, 'updatePeserta'])->name('profile.peserta.update');
Route::post('profile/pegawai', [ProfileController::class, 'updatePegawai'])->name('profile.pegawai.update');
Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
Route::get('profile-petakom', [ProfilePetakomController::class, 'index'])->name('profile-petakom');
Route::get('profile-petakom-check-nuptk', [ProfilePetakomController::class, 'checkNuptk'])->name('profile-petakom-check-nuptk');
Route::post('profile-petakom-store', [ProfilePetakomController::class, 'storePeserta'])->name('profile-petakom-store');

Route::middleware(['rbac:superadmin'])->group(function () {
    require __DIR__ . '/roles/superadmin.php';
});

Route::middleware(['auth'])->group(function () {
    //Simdiklat
    require __DIR__ . '/panel/dashboard.php';
    require __DIR__ . '/panel/manajemen_user.php';
    require __DIR__ . '/panel/manajemen_jenis_kegiatan.php';
    require __DIR__ . '/panel/manajemen_penandatangan.php';
    require __DIR__ . '/panel/manajemen_template_sertifikat.php';
    require __DIR__ . '/panel/manajemen_tim_kerja.php';
    require __DIR__ . '/panel/manajemen_data_pegawai.php';
    require __DIR__ . '/panel/manajemen_dokumen_pegawai.php';
    require __DIR__ . '/panel/manajemen_kegiatan.php';
    require __DIR__ . '/panel/verifikasi_penugasan.php';
    require __DIR__ . '/panel/diklat.php';
    require __DIR__ . '/panel/manajemen_eval_penyelenggaraan.php';
    require __DIR__ . '/panel/manajemen_eval_narasumber.php';
    require __DIR__ . '/panel/rekap_pegawai.php';
});

Route::middleware(['auth:petakom'])->group(function () {
    require __DIR__ . '/panel/dashboard_petakom.php';
    require __DIR__ . '/panel/petakom_instrumen_list.php';
    require __DIR__ . '/panel/petakom_manajemen_master_instrumen.php';
    require __DIR__ . '/panel/petakom_instrumen.php';
});

Route::get('auth/petakom/assign-admin', [AuthController::class, 'assignPetakomAdminByEmail'])->name('auth-petakom-assign-admin');
