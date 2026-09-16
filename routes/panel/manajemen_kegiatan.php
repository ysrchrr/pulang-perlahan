<?php

use App\Http\Controllers\FotoKegiatanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ManajemenKegiatanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SettingSertifikatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rbac'])->group(function () {
    #Admin Tim Kerja & Panitia (ga semua)
    Route::get('/kegiatan', [ManajemenKegiatanController::class, 'index'])->name('kegiatan');
    Route::get('/kegiatan-get-data', [ManajemenKegiatanController::class, 'getData'])->name('kegiatan-get-data');
    Route::get('/kegiatan-create', [ManajemenKegiatanController::class, 'create'])->name('kegiatan-create');
    Route::post('/kegiatan-store', [ManajemenKegiatanController::class, 'store'])->name('kegiatan-store');
    Route::post('/kegiatan-detail', [ManajemenKegiatanController::class, 'detail'])->name('kegiatan-detail');
    Route::post('/kegiatan-delete', [ManajemenKegiatanController::class, 'delete'])->name('kegiatan-delete');
    Route::post('/kegiatan-kirim-kepegawaian', [ManajemenKegiatanController::class, 'kirimKepegawaian'])->name('kegiatan-kirim-kepegawaian');
    Route::post('/kegiatan-start-kegiatan', [ManajemenKegiatanController::class, 'startKegiatan'])->name('kegiatan-start-kegiatan');
    Route::post('/kegiatan-detail-evaluasi', [ManajemenKegiatanController::class, 'detailEvaluasi'])->name('kegiatan-detail-evaluasi');
    Route::post('/kegiatan-store-evaluasi', [ManajemenKegiatanController::class, 'storeSettingEvaluasi'])->name('kegiatan-store-evaluasi');

    #Penugasan
    Route::get('/kegiatan-peran/{id_kegiatan}', [ManajemenKegiatanController::class, 'kegiatanPeran'])->name('kegiatan-peran');
    Route::get('/kegiatan-peran-get-data/{id_kegiatan}', [ManajemenKegiatanController::class, 'kegiatanPeranGetData'])->name('kegiatan-peran-get-data');
    Route::post('/kegiatan-peran-store', [ManajemenKegiatanController::class, 'kegiatanPeranStore'])->name('kegiatan-peran-store');
    Route::post('/kegiatan-peran-detail', [ManajemenKegiatanController::class, 'kegiatanPeranDetail'])->name('kegiatan-peran-detail');
    Route::post('/kegiatan-peran-delete', [ManajemenKegiatanController::class, 'kegiatanPeranDelete'])->name('kegiatan-peran-delete');

    #Plotting Pegawai di Peran Kegiatan
    Route::get('/kegiatan-peran-pegawai/{id_kegiatan}/{id_peran}', [ManajemenKegiatanController::class, 'kegiatanPeranPegawai'])->name('kegiatan-peran-pegawai');
    Route::get('/kegiatan-peran-pegawai-pilih/{id_kegiatan}/{id_peran}', [ManajemenKegiatanController::class, 'kegiatanPeranPegawaiPilih'])->name('kegiatan-peran-pegawai-pilih');
    Route::get('/kegiatan-peran-pegawai-get-data/{id_kegiatan}/{id_peran}', [ManajemenKegiatanController::class, 'kegiatanPeranPegawaiGetData'])->name('kegiatan-peran-pegawai-get-data');
    Route::get('/kegiatan-peran-pegawai-pilih-get-data/{id_kegiatan}/{id_peran}', [ManajemenKegiatanController::class, 'kegiatanPeranPegawaiPilihGetData'])->name('kegiatan-peran-pegawai-pilih-get-data');
    Route::post('/kegiatan-peran-pegawai-store', [ManajemenKegiatanController::class, 'kegiatanPeranPegawaiStore'])->name('kegiatan-peran-pegawai-store');
    Route::post('/kegiatan-peran-pegawai-delete', [ManajemenKegiatanController::class, 'kegiatanPeranPegawaiDelete'])->name('kegiatan-peran-pegawai-delete');

    #Panitia - Kelas List
    Route::get('/kegiatan-kelas-list/{id_kegiatan}', [KelasController::class, 'listKelas'])->name('kegiatan-kelas-list');
    Route::get('/kegiatan-kelas-form-presensi/{id_kegiatan}', [KelasController::class, 'exportFormPresensi'])->name('kegiatan-kelas-form-presensi');
    Route::get('/kegiatan-kelas-get-data/{id_kegiatan}', [KelasController::class, 'getDataKelas'])->name('kegiatan-kelas-get-data');
    Route::post('/kegiatan-kelas-store', [KelasController::class, 'store'])->name('kegiatan-kelas-store');
    Route::post('/kegiatan-kelas-detail', [KelasController::class, 'detail'])->name('kegiatan-kelas-detail');
    Route::post('/kegiatan-kelas-delete', [KelasController::class, 'delete'])->name('kegiatan-kelas-delete');
    Route::get('/kegiatan-kelas-peserta/{id_kelas}', [KelasController::class, 'kelasPeserta'])->name('kegiatan-kelas-peserta');
    Route::get('/kegiatan-kelas-get-peserta/{id_kelas}', [KelasController::class, 'getDataPesertaKelas'])->name('kegiatan-kelas-get-peserta');
    Route::get('/kegiatan-kelas-get-peserta-tersedia/{id_kelas}', [KelasController::class, 'getPesertaTersedia'])->name('kegiatan-kelas-get-peserta-tersedia');
    Route::post('/kegiatan-kelas-store-peserta', [KelasController::class, 'storePeserta'])->name('kegiatan-kelas-store-peserta');
    Route::post('/kegiatan-kelas-delete-peserta', [KelasController::class, 'deletePeserta'])->name('kegiatan-kelas-delete-peserta');
    Route::get('/kegiatan-kelas-generate-biodata/{id_kegiatan}/{id_peserta}', [KelasController::class, 'generateBiodata'])->name('kegiatan-kelas-generate-biodata');

    #Panitia - Presensi Peserta
    Route::get('/kegiatan-kelas-presensi/{id_kelas}', [PresensiController::class, 'index'])->name('kegiatan-kelas-presensi');
    Route::get('/kegiatan-kelas-presensi-export/{id_kelas}', [PresensiController::class, 'exportAbsensi'])->name('kegiatan-kelas-presensi-export');
    Route::post('/kegiatan-kelas-presensi-generate/{id_kelas}', [PresensiController::class, 'generateAbsensi'])->name('kegiatan-kelas-presensi-generate');
    Route::post('/kegiatan-kelas-presensi-update', [PresensiController::class, 'updateAbsensi'])->name('kegiatan-kelas-presensi-update');

    #Panitia - Buka Tutup Pendaftaran
    Route::post('/kegiatan-get-status-pendaftaran', [ManajemenKegiatanController::class, 'getStatusPendaftaran'])->name('kegiatan-get-status-pendaftaran');
    Route::post('/kegiatan-store-status-pendaftaran', [ManajemenKegiatanController::class, 'storeStatusPendaftaran'])->name('kegiatan-store-status-pendaftaran');

    #Panitia - Verifikasi Pendaftaran
    Route::get('/kegiatan-verifikasi-pendaftaran/{kode_kegiatan}', [ManajemenKegiatanController::class, 'verifikasiPendaftaran'])->name('kegiatan-verifikasi-pendaftaran');
    Route::get('/kegiatan-verifikasi-pendaftaran-get-data/{kode_kegiatan}', [ManajemenKegiatanController::class, 'getDataPendaftar'])->name('kegiatan-verifikasi-pendaftaran-get-data');
    Route::get('/kegiatan-verifikasi-pendaftaran-get-berkas/{id_peserta_terpilih}', [ManajemenKegiatanController::class, 'getBerkasPendaftar'])->name('kegiatan-verifikasi-pendaftaran-get-berkas');
    Route::post('/kegiatan-verifikasi-pendaftaran-store', [ManajemenKegiatanController::class, 'storeVerifikasiPendaftaran'])->name('kegiatan-verifikasi-pendaftaran-store');

    #Panitia - Setting Sertifikat
    Route::get('/kegiatan-setting-sertifikat/{id_kegiatan}', [SettingSertifikatController::class, 'settingSertifikat'])->name('kegiatan-setting-sertifikat');
    Route::post('/kegiatan-setting-sertifikat-store', [SettingSertifikatController::class, 'store'])->name('kegiatan-setting-sertifikat-store');
    Route::post('/kegiatan-generate-no-sertifikat', [SettingSertifikatController::class, 'generateNomorSertifikat'])->name('kegiatan-generate-no-sertifikat');

    #Panitia - Sertifikat
    Route::get('/kegiatan-sertifikat/{id_kegiatan}', [SertifikatController::class, 'index'])->name('kegiatan-sertifikat');
    Route::get('/kegiatan-sertifikat-preview/{id_kegiatan}', [SertifikatController::class, 'preview'])->name('kegiatan-sertifikat-preview');
    Route::post('/kegiatan-sertifikat-generate', [SertifikatController::class, 'generate'])->name('kegiatan-sertifikat-generate');

    #Paniitia - Foto Kegiatan
    Route::get('/kegiatan-foto/{id_kegiatan}', [FotoKegiatanController::class, 'index'])->name('kegiatan-foto');
    Route::post('/kegiatan-foto-store', [FotoKegiatanController::class, 'store'])->name('kegiatan-foto-store');
    Route::post('/kegiatan-foto-delete', [FotoKegiatanController::class, 'delete'])->name('kegiatan-foto-delete');
});
