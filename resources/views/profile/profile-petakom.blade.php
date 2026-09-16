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
                                <form action="{{ route('profile-petakom-store') }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Nama Lengkap <span class="text-danger">*</span> </label>
                                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                                value="{{ old('nama_lengkap', $user?->name) }}"
                                                placeholder="Masukkan nama lengkap">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">NUPTK (optional)</label>
                                            <input type="text" name="nuptk" id="nuptk" class="form-control"
                                                value="{{ old('nuptk', $profile?->nuptk) }}" inputmode="numeric"
                                                pattern="[0-9]*" maxlength="18" placeholder="Masukkan 18 Digit NUPTK">
                                            <small id="nuptk_check" class="text-muted"></small>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jenis Kelamin</label> <span class="text-danger">*</span>
                                            <select id="jk" name="jk" class="form-select">
                                                <option value="">Pilih jenis kelamin</option>
                                                <option value="L"
                                                    {{ old('jk', $profile?->jk) == 'L' ? 'selected' : '' }}>
                                                    Laki-laki
                                                </option>
                                                <option value="P"
                                                    {{ old('jk', $profile?->jk) == 'P' ? 'selected' : '' }}>
                                                    Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Asal Sekolah</label>
                                            <input type="text" id="sekolah" name="sekolah" class="form-control"
                                                value="{{ old('sekolah', $profile?->sekolah) }}"
                                                placeholder="Masukkan sekolah">
                                        </div>
                                        <div class="col-md-6">
                                            <label>NPSN</label>
                                            <input type="text" id="npsn" name="npsn" class="form-control"
                                                value="{{ old('npsn', $profile?->npsn) }}" placeholder="Masukkan npsn">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="">Jenjang</label>
                                            <select name="id_jenjang" id="id_jenjang" class="form-select">
                                                <option value="">Pilih Jenjang</option>
                                                @foreach ($ref_jenjang as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_jenjang', $profile?->id_jenjang) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_jenjang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mapel</label>
                                            <select name="id_mapel" id="id_mapel" class="form-select">
                                                <option value="">Pilih Mapel</option>
                                                @foreach ($ref_mapel as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('id_mapel', $profile?->id_mapel) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_mapel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="">Wilayah</label>
                                            <select name="wilayah_petakom" id="wilayah_petakom" class="form-select">
                                                <option value="">Pilih Wilayah Petakom</option>
                                                @foreach ($ref_wilayah_petakom as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('wilayah_petakom', $profile?->wilayah_petakom) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_wilayah }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Nomor Whatsapp</label>
                                            <input type="text" name="no_hp" id="no_hp" class="form-control"
                                                value="{{ old('no_hp', $profile?->no_hp) }}"
                                                placeholder="Contoh: 081234567890">
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
@endsection
@push('scripts')
    <script>
        $(function() {
            var nuptkTimer = null;
            var lastRequest = null;

            function setNuptkStatus(className, icon, text) {
                $('#nuptk_check')
                    .removeClass('text-muted text-danger text-success')
                    .addClass(className)
                    .html(icon ? '<i class="' + icon + '"></i> ' + text : text);
            }

            $('#nuptk').on('input', function() {
                var nuptk = $(this).val().replace(/\D/g, '').substring(0, 18);
                $(this).val(nuptk);

                clearTimeout(nuptkTimer);

                if (lastRequest) {
                    lastRequest.abort();
                }

                if (nuptk.length === 0) {
                    setNuptkStatus('text-muted', '', '');
                    return;
                }

                if (nuptk.length < 9) {
                    setNuptkStatus('text-danger', 'fas fa-times', 'NUPTK tidak valid');
                    return;
                }

                setNuptkStatus('text-muted', '', 'Checking...');

                nuptkTimer = setTimeout(function() {
                    lastRequest = $.ajax({
                        url: "{{ route('profile-petakom-check-nuptk') }}",
                        type: 'GET',
                        data: {
                            nuptk: nuptk
                        },
                        success: function(response) {
                            if (response.exists) {
                                setNuptkStatus('text-danger', 'fas fa-times',
                                    'NUPTK ini telah digunakan oleh ' + response
                                    .email);
                                return;
                            }

                            setNuptkStatus('text-success', 'fas fa-check',
                                'NUPTK belum terdaftar di sistem');
                        },
                        error: function(xhr) {
                            if (xhr.statusText === 'abort') {
                                return;
                            }

                            setNuptkStatus('text-muted', '', '');
                        }
                    });
                }, 400);
            });

            $('#nuptk').trigger('input');
        });
    </script>
@endpush
