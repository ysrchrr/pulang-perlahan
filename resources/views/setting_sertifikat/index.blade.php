@extends('layouts.app')
@push('styles')
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('kegiatan') }}" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }} - {{ $kegiatan->nama_kegiatan }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <form id="formSettingSertifikat">
                                    <input type="hidden" name="id_kegiatan" value="{{ $id_kegiatan }}">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Template Sertifikat</label>
                                            <select name="id_template_sertifikat" id="id_template_sertifikat"
                                                class="form-select">
                                                <option value=""
                                                    {{ empty($current_setting?->id_template_sertifikat) ? 'selected' : '' }}>
                                                    Pilih Template Sertifikat</option>
                                                @foreach ($list_template as $template)
                                                    <option value="{{ $template->id }}"
                                                        {{ (string) $current_setting?->id_template_sertifikat === (string) $template->id ? 'selected' : '' }}>
                                                        {{ $template->nama_template }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Cetak Kelas Pada Sertifikat</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="is_cetak_kelas"
                                                    {{ $current_setting?->is_cetak_kelas == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_cetak_kelas">
                                                    Ya, cetak
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Penandatangan Sertifikat</label>
                                            <select name="id_penandatangan" id="id_penandatangan" class="form-select">
                                                <option value=""
                                                    {{ empty($current_setting?->id_penandatangan) ? 'selected' : '' }}>Pilih
                                                    Penandatangan Sertifikat</option>
                                                @foreach ($list_penandatangan as $penandatangan)
                                                    <option value="{{ $penandatangan->id }}"
                                                        {{ (string) $current_setting?->id_penandatangan === (string) $penandatangan->id ? 'selected' : '' }}>
                                                        {{ $penandatangan->nama_penandatangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Kota Pelaksanaan</label>
                                            <input type="text" name="kota_pelaksanaan" id="kota_pelaksanaan"
                                                class="form-control" placeholder="Contoh: Batang Hari"
                                                value="{{ $current_setting?->kota_pelaksanaan }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tanggal Penandatanganan</label>
                                            <input type="date" name="tanggal_penandatangan" id="tanggal_penandatangan"
                                                class="form-control"
                                                value="{{ $current_setting?->tanggal_penandatangan }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Nomor Sertifikat Peserta</label>
                                            <input type="text" name="nomor_sertifikat" id="nomor_sertifikat"
                                                class="form-control" placeholder=".... hingga ...." readonly
                                                value="{{ $nomor_sertifikat_range }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Nomor Sertifikat Narsumber</label>
                                            <input type="text" name="nomor_sertifikat_narasumber"
                                                id="nomor_sertifikat_narasumber" class="form-control"
                                                placeholder=".... hingga ...." readonly
                                                value="{{ $nomor_sertifikat_narasumber_range }}">
                                        </div>
                                    </div>
                                    <input type="hidden" name="is_cetak_kelas" id="is_cetak_kelas_value"
                                        value="{{ $current_setting?->is_cetak_kelas == '1' ? '1' : '0' }}">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary"
                                                onclick="storeSettingSertifikat()"> <i class="fas fa-save"></i>
                                                Simpan</button>
                                            <button type="button" class="btn btn-success"
                                                onclick="generateNomorSertifikat()"> <i class="fas fa-stamp"></i>
                                                Generate Nomor Sertifikat</button>
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
        function storeSettingSertifikat() {
            $('#is_cetak_kelas_value').val($('#is_cetak_kelas').is(':checked') ? '1' : '0');

            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-setting-sertifikat-store') }}",
                data: $('#formSettingSertifikat').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message ?? xhr.responseJSON?.msg ?? 'Terjadi kesalahan';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).map(function(item) {
                            return item[0];
                        }).join('<br>');
                    }

                    iziToast.error({
                        title: "Gagal!",
                        message: message
                    });
                }
            });
        }

        function generateNomorSertifikat() {
            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-generate-no-sertifikat') }}",
                data: {
                    id_kegiatan: $('input[name="id_kegiatan"]').val(),
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nomor_sertifikat').val(response.nomor_sertifikat_range ?? '');
                        $('#nomor_sertifikat_narasumber').val(response.nomor_sertifikat_narasumber_range ?? '');

                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message ?? xhr.responseJSON?.msg ?? 'Terjadi kesalahan';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).map(function(item) {
                            return item[0];
                        }).join('<br>');
                    }

                    if (xhr.status === 422 && xhr.responseJSON?.msg) {
                        message = xhr.responseJSON.msg;
                    }

                    iziToast.error({
                        title: "Gagal!",
                        message: message
                    });
                }
            });
        }
    </script>
@endpush
