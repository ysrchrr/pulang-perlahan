@extends('layouts.app')
@push('styles')
@endpush
@section('contents')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                                <form action="{{ route('profile.peserta.update') }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Nama Lengkap <span class="text-danger">*</span> </label>
                                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                                value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}"
                                                placeholder="Masukkan nama lengkap">
                                            <small class="text-muted">Untuk keperluan sertifikat</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label>NIK <span class="text-danger">*</span> </label>
                                            <input type="text" id="nik" name="nik" class="form-control"
                                                value="{{ old('nik', $peserta->nik) }}" placeholder="Masukkan NIK">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>NIP</label>
                                            <input type="text" id="nip" name="nip" class="form-control"
                                                value="{{ old('nip', $peserta->nip) }}" placeholder="Masukkan NIP">
                                            <small class="text-muted">Isikan dengan '-' jika tidak memiliki</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Jenis Kelamin <span class="text-danger">*</span> </label>
                                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-select">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L"
                                                    {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="P"
                                                    {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Tempat Lahir <span class="text-danger">*</span> </label>
                                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                                value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}"
                                                placeholder="Masukkan tempat lahir">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tanggal Lahir <span class="text-danger">*</span> </label>
                                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                                class="form-control"
                                                value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Email <span class="text-danger">*</span> </label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="{{ old('email', $peserta->email) }}" placeholder="Masukkan email">
                                        </div>
                                        <div class="col-md-6">
                                            <label>No. Telepon <span class="text-danger">*</span> </label>
                                            <input type="text" id="no_hp" name="no_hp" class="form-control"
                                                value="{{ old('no_hp', $peserta->no_hp) }}"
                                                placeholder="Masukkan no. telepon">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label>Alamat <span class="text-danger">*</span> </label>
                                            <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $peserta->alamat) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Unit Kerja</label>
                                            <input type="text" id="unit_kerja" name="unit_kerja" class="form-control"
                                                value="{{ old('unit_kerja', $peserta->unit_kerja) }}"
                                                placeholder="Masukkan unit kerja">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Pangkat/Golongan</label>
                                            <select name="id_golongan" id="id_golongan" class="form-select">
                                                <option value="">Pilih Pangkat/Golongan</option>
                                                @foreach ($ref_pagol as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_golongan', $peserta->id_golongan) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->golongan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>NPWP</label>
                                            <input type="text" name="npwp" id="npwp" class="form-control"
                                                value="{{ old('npwp', $peserta->npwp) }}" placeholder="Masukkan NPWP">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" type="submit"> <i class="fas fa-save"></i>
                                                Simpan Profil</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Ubah Password</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.password.update') }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Password Lama <span class="text-danger">*</span></label>
                                            <input type="password" name="password_lama" class="form-control"
                                                placeholder="Masukkan password lama">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Password Baru <span class="text-danger">*</span></label>
                                            <input type="password" name="password_baru" class="form-control"
                                                placeholder="Masukkan password baru">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                            <input type="password" name="password_baru_confirmation" class="form-control"
                                                placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-lock"></i> Ubah Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
