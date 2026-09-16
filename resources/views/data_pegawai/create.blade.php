@extends('layouts.app')
@push('styles')
@endpush
@section('contents')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <a class="btn btn-primary btn-sm" href="{{ route('data-pegawai') }}"> <i class="fas fa-arrow-left"></i>
                            Kembali</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST"
                                    action="{{ $is_edit ? route('data-pegawai-update') : route('data-pegawai-store') }}">
                                    @csrf
                                    @if ($is_edit)
                                        <input type="hidden" name="id" value="{{ $enc_id }}">
                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="nama">Nama</label>
                                            <input type="text" name="nama" id="nama" class="form-control"
                                                placeholder="Masukkan nama pegawai..."
                                                value="{{ old('nama', $pegawai->nama ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="nip">NIP</label>
                                            <input type="text" name="nip" id="nip" class="form-control"
                                                placeholder="Masukkan NIP pegawai..."
                                                value="{{ old('nip', $pegawai->nip ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nik">NIK</label>
                                            <input type="text" name="nik" id="nik" class="form-control"
                                                placeholder="Masukkan NIK pegawai..."
                                                value="{{ old('nik', $pegawai->nik ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="tempat_lahir">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                                placeholder="Masukkan tempat lahir pegawai..."
                                                value="{{ old('tempat_lahir', $pegawai->tempat_lahir ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                class="form-control"
                                                value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jenis Kelamin</label>
                                            <select name="jk" id="jk" class="form-select">
                                                <option value="">Pilih jenis kelamin</option>
                                                <option value="L"
                                                    {{ old('jk', $pegawai->jk ?? '') == 'L' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="P"
                                                    {{ old('jk', $pegawai->jk ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="Masukkan email pegawai..."
                                                value="{{ old('email', $pegawai->email ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="no_hp">No. HP</label>
                                            <input type="text" name="no_hp" id="no_hp" class="form-control"
                                                placeholder="Masukkan no. HP pegawai..."
                                                value="{{ old('no_hp', $pegawai->no_hp ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Jabatan</label>
                                            <select name="id_jabatan" id="id_jabatan" class="form-select">
                                                <option value="">Pilih jabatan</option>
                                                @foreach ($ref_jabatan as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_jabatan', $pegawai->id_jabatan ?? '') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Golongan</label>
                                            <select name="id_golongan" id="id_golongan" class="form-select">
                                                <option value="">Pilih golongan</option>
                                                @foreach ($ref_golongan as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_golongan', $pegawai->id_golongan ?? '') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->golongan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="instansi_pendidikan">Instansi Pendidikan</label>
                                            <input type="text" name="instansi_pendidikan" id="instansi_pendidikan"
                                                class="form-control" placeholder="Masukkan instansi pendidikan pegawai..."
                                                value="{{ old('instansi_pendidikan', $pegawai->instansi_pendidikan ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tahun_lulus">Tahun Lulus</label>
                                            <input type="text" name="tahun_lulus" id="tahun_lulus"
                                                class="form-control" placeholder="Masukkan tahun lulus pegawai..."
                                                value="{{ old('tahun_lulus', $pegawai->tahun_lulus ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="jenjang">Jenjang</label>
                                            <select name="jenjang" id="jenjang" class="form-select">
                                                <option value="">Pilih jenjang pendidikan</option>
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
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit"> <i class="fas fa-save"></i>
                                                Simpan</button>
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
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
