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
                                <form action="{{ route('profile.pegawai.update') }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Nama Pegawai <span class="text-danger">*</span> </label>
                                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                                value="{{ old('nama_lengkap', $pegawai->nama) }}"
                                                placeholder="Masukkan nama lengkap">
                                        </div>
                                        <div class="col-md-6">
                                            <label>NIK <span class="text-danger">*</span> </label>
                                            <input type="text" id="nik" name="nik" class="form-control"
                                                value="{{ old('nik', $pegawai->nik) }}" placeholder="Masukkan NIK">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>NIP</label>
                                            <input type="text" id="nip" name="nip" class="form-control"
                                                value="{{ old('nip', $pegawai->nip) }}" placeholder="Masukkan NIP">
                                            <small class="text-muted">Isikan dengan '-' jika tidak memiliki</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Jenis Kelamin <span class="text-danger">*</span> </label>
                                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-select">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L"
                                                    {{ old('jenis_kelamin', $pegawai->jk) == 'L' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="P"
                                                    {{ old('jenis_kelamin', $pegawai->jk) == 'P' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Tempat Lahir <span class="text-danger">*</span> </label>
                                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                                value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}"
                                                placeholder="Masukkan tempat lahir">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tanggal Lahir <span class="text-danger">*</span> </label>
                                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                                class="form-control"
                                                value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Email <span class="text-danger">*</span> </label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="{{ old('email', $pegawai->email) }}" placeholder="Masukkan email">
                                        </div>
                                        <div class="col-md-6">
                                            <label>No. Telepon <span class="text-danger">*</span> </label>
                                            <input type="text" id="no_hp" name="no_hp" class="form-control"
                                                value="{{ old('no_hp', $pegawai->no_hp) }}"
                                                placeholder="Masukkan no. telepon">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label>Alamat <span class="text-danger">*</span> </label>
                                            <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $pegawai->alamat) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Jabatan</label>
                                            <select name="id_jabatan" id="id_jabatan" class="form-select">
                                                <option value="">Jabatan</option>
                                                @foreach ($ref_jabatan as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_jabatan', $pegawai->id_jabatan) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Pangkat/Golongan</label>
                                            <select name="id_golongan" id="id_golongan" class="form-select">
                                                <option value="">Pilih Pangkat/Golongan</option>
                                                @foreach ($ref_pagol as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_golongan', $pegawai->id_golongan) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->golongan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Instansi Pendidikan</label>
                                            <input type="text" id="instansi_pendidikan" name="instansi_pendidikan"
                                                class="form-control"
                                                value="{{ old('instansi_pendidikan', $pegawai->instansi_pendidikan) }}"
                                                placeholder="Masukkan instansi pendidikan terakhir">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jenjang</label>
                                            <select name="jenjang" id="jenjang" class="form-select">
                                                <option value="">Pilih Jenjang</option>
                                                <option value="SD"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'SD' ? 'selected' : '' }}>
                                                    SD</option>
                                                <option value="SLTP"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'SLTP' ? 'selected' : '' }}>
                                                    SLTP</option>
                                                <option value="SLTA"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'SLTA' ? 'selected' : '' }}>
                                                    SLTA</option>
                                                <option value="D3"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'D3' ? 'selected' : '' }}>
                                                    D3</option>
                                                <option value="D4"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'D4' ? 'selected' : '' }}>
                                                    D4</option>
                                                <option value="S1"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'S1' ? 'selected' : '' }}>
                                                    S1</option>
                                                <option value="S2"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'S2' ? 'selected' : '' }}>
                                                    S2</option>
                                                <option value="S3"
                                                    {{ old('jenjang', $pegawai->jenjang ?? '') == 'S3' ? 'selected' : '' }}>
                                                    S3</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tahun Lulus</label>
                                            <input type="text" name="tahun_lulus" id="tahun_lulus"
                                                class="form-control"
                                                value="{{ old('tahun_lulus', $pegawai->tahun_lulus) }}"
                                                placeholder="Masukkan tahun lulus pendidikan terakhir">
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
