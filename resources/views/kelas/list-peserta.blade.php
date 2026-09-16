@extends('layouts.app')
@push('styles')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('kegiatan-kelas-list', ['id_kegiatan' => $id_kegiatan]) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                                <div class="flex-shrink-0">
                                    <div class="btn-group">
                                        <button onclick="modalTambahPeserta()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Peserta
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-kelas-peserta">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Instansi</th>
                                                <th class="text-center">Kontak</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_peserta" tabindex="-1" aria-labelledby="modal_pesertaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPesertaKelas">
                @csrf
                <input type="hidden" name="id_kelas" value="{{ $id_kelas }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_pesertaLabel">Tambah Peserta</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_peserta_terpilih">Pilih Peserta</label>
                            <select class="form-select" name="id_peserta_terpilih[]" id="id_peserta_terpilih" multiple required>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanPeserta">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let pesertaSelect2Initialized = false;

        $(document).ready(function() {
            loadTable();

            $('#formPesertaKelas').on('submit', function(e) {
                e.preventDefault();
                storePeserta();
            });

            $('#modal_peserta').on('shown.bs.modal', function() {
                if (!pesertaSelect2Initialized) {
                    initPesertaSelect2();
                    pesertaSelect2Initialized = true;
                }
            });
        });

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-kelas-peserta')) {
                $('#table-kelas-peserta').DataTable().destroy();
            }

            $('#table-kelas-peserta').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-kelas-get-peserta', ['id_kelas' => $id_kelas]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama',
                        name: 'nama_lengkap',
                        className: 'va-middle'
                    },
                    {
                        data: 'instansi',
                        name: 'unit_kerja',
                        className: 'va-middle'
                    },
                    {
                        data: 'kontak',
                        name: 'kontak',
                        orderable: false,
                        searchable: false,
                        className: 'va-middle'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    }
                ]
            });
        }

        function initPesertaSelect2() {
            $('#id_peserta_terpilih').select2({
                dropdownParent: $('#modal_peserta'),
                placeholder: 'Pilih satu atau beberapa peserta',
                width: '100%',
                closeOnSelect: false,
                allowClear: true
            });
        }

        function modalTambahPeserta() {
            $('#formPesertaKelas')[0].reset();
            $('#id_peserta_terpilih').val(null).trigger('change');
            $('#modal_peserta').modal('show');
            loadPesertaTersedia();
        }

        function loadPesertaTersedia() {
            $('#id_peserta_terpilih').html('');

            $.ajax({
                type: 'GET',
                url: "{{ route('kegiatan-kelas-get-peserta-tersedia', ['id_kelas' => $id_kelas]) }}",
                dataType: 'json',
                success: function(response) {
                    var options = '';

                    if (response.data.length < 1) {
                        $('#id_peserta_terpilih').html(options).prop('disabled', true).trigger('change');
                        return;
                    } else {
                        $.each(response.data, function(index, item) {
                            options += '<option value="' + item.id_enc + '">' + item.nama_lengkap +
                                ' - ' +
                                (item.unit_kerja ? item.unit_kerja : '-') + '</option>';
                        });
                    }

                    $('#id_peserta_terpilih').html(options).prop('disabled', false).trigger('change');
                },
                error: function(xhr) {
                    $('#id_peserta_terpilih').html('').prop('disabled', true).trigger('change');
                    iziToast.error({
                        title: 'Gagal!',
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function storePeserta() {
            $.ajax({
                type: 'POST',
                url: "{{ route('kegiatan-kelas-store-peserta') }}",
                data: $('#formPesertaKelas').serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSimpanPeserta').prop('disabled', true).text('Menyimpan...');
                },
                success: function(response) {
                    iziToast.success({
                        title: 'Berhasil!',
                        message: response.msg
                    });
                    $('#modal_peserta').modal('hide');
                    loadTable();
                },
                error: function(xhr) {
                    var message = xhr.responseJSON?.message ?? xhr.responseJSON?.msg ?? 'Terjadi kesalahan';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).map(function(item) {
                            return item[0];
                        }).join('<br>');
                    }

                    iziToast.error({
                        title: 'Gagal!',
                        message: message
                    });
                },
                complete: function() {
                    $('#btnSimpanPeserta').prop('disabled', false).text('Simpan');
                }
            });
        }

        function keluarkanPeserta(idPesertaTerpilih) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Keluarkan peserta ini dari kelas?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{ route('kegiatan-kelas-delete-peserta') }}",
                    data: {
                        id_kelas: "{{ $id_kelas }}",
                        id_peserta_terpilih: idPesertaTerpilih
                    },
                    dataType: 'json',
                    success: function(response) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.msg
                        });
                        loadTable();
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Gagal!',
                            message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                        });
                    }
                });
            });
        }
    </script>
@endpush
