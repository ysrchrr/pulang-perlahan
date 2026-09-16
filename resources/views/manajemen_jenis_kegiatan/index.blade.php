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
                                <div class="flex-shrink-0">
                                    <div class="btn-group">
                                        <button onclick="modalJenisKegiatan()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Jenis Kegiatan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-jenis-kegiatan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Jenis Kegiatan</th>
                                                <th class="text-center">Dibuat Oleh</th>
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

    <!-- Modal -->
    <div class="modal fade" id="modal_jenis_kegiatan" tabindex="-1" aria-labelledby="modal_jenis_kegiatanLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formJenisKegiatan">

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_jenis_kegiatanLabel">Tambah Jenis Kegiatan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_jenis_kegiatan" name="id_jenis_kegiatan">
                                <label for="nama_jenis_kegiatan">Nama Jenis Kegiatan</label>
                                <input type="text" class="form-control" id="nama_jenis_kegiatan"
                                    name="nama_jenis_kegiatan" placeholder="Contoh: Workshop">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="">Unique Peserta (Cross Kegiatan)</label>
                                <select name="is_unique_peserta" id="is_unique_peserta" class="form-select">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                                <small class="text-muted">Apakah peserta bisa mengikuti kegiatan berbeda dalam satu
                                    waktu?</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeJenisKegiatan()">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            loadTable();
        });

        function modalJenisKegiatan() {
            $('#modal_jenis_kegiatanLabel').text('Tambah Jenis Kegiatan');
            $('#formJenisKegiatan')[0].reset();
            $('#id_jenis_kegiatan').val('');
            $('#modal_jenis_kegiatan').modal('show');
        }

        function storeJenisKegiatan() {
            $.ajax({
                type: "POST",
                url: "{{ route('jenis-kegiatan-store') }}",
                data: {
                    id_jenis_kegiatan: $('#id_jenis_kegiatan').val(),
                    nama_jenis_kegiatan: $('#nama_jenis_kegiatan').val(),
                    is_unique_peserta: $('#is_unique_peserta').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_jenis_kegiatan').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    iziToast.error({
                        title: "Gagal!",
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function toEdit(id) {
            $('#modal_jenis_kegiatanLabel').text('Edit Jenis Kegiatan');
            $('#id_jenis_kegiatan').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('jenis-kegiatan-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_jenis_kegiatan').val(response.data.nama_jenis_kegiatan);
                        $('#is_unique_peserta').val(response.data.is_unique_peserta);
                    }
                    $('#modal_jenis_kegiatan').modal('show');
                }
            });
        }

        function toRemove(id) {
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah anda yakin untuk menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('jenis-kegiatan-delete') }}",
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: "Berhasil!",
                                    message: response.msg
                                });
                                loadTable();
                            } else {
                                iziToast.error({
                                    title: "Gagal!",
                                    message: response.msg
                                });
                            }
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: "Gagal!",
                                message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-jenis-kegiatan')) {
                $('#table-jenis-kegiatan').DataTable().destroy();
            }
            $('#table-jenis-kegiatan').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('jenis-kegiatan-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_jenis_kegiatan',
                        name: 'nama_jenis_kegiatan',
                        className: 'va-middle'
                    },
                    {
                        data: 'creator',
                        name: 'creator',
                        className: 'va-middle'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });
        }
    </script>
@endpush
