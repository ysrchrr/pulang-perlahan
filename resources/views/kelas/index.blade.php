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
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>

                                <button type="button" class="btn btn-dark" id="btnExportAbsensi"
                                    onclick="exportFormPresensi()">
                                    <i class="fa-solid fa-file-zipper"></i> Form Absensi
                                </button>
                                <button onclick="modalKelas()" class="btn btn-primary ms-2">
                                    <i class="fa-solid fa-plus"></i> Tambah Kelas
                                </button>
                                {{-- <div class="flex-shrink-0">
                                    <div class="btn-group">
                                    </div>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-kelas">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Kelas</th>
                                                <th class="text-center">Jumlah Peserta</th>
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

    <div class="modal fade" id="modal_kelas" tabindex="-1" aria-labelledby="modal_kelasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formKelas">
                <input type="hidden" name="id_kelas" id="id_kelas">
                <input type="hidden" name="id_kegiatan" id="id_kegiatan" value="{{ $id_kegiatan }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_kelasLabel">Tambah Kelas</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kelas">Nama Kelas</label>
                            <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                                placeholder="Contoh: Kelas A">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeKelas()">Simpan</button>
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

        function exportFormPresensi() {
            window.location.href = "{{ route('kegiatan-kelas-form-presensi', ['id_kegiatan' => $id_kegiatan]) }}";
        }

        function modalKelas() {
            $('#modal_kelasLabel').text('Tambah Kelas');
            $('#formKelas')[0].reset();
            $('#id_kelas').val('');
            $('#modal_kelas').modal('show');
        }

        function storeKelas() {
            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-kelas-store') }}",
                data: $('#formKelas').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_kelas').modal('hide');
                        loadTable();
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

        function toEdit(id) {
            $('#modal_kelasLabel').text('Edit Kelas');
            $('#formKelas')[0].reset();
            $('#id_kelas').val(id);

            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-kelas-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_kelas').val(response.data.nama_kelas);
                        $('#modal_kelas').modal('show');
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
                        url: "{{ route('kegiatan-kelas-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-kelas')) {
                $('#table-kelas').DataTable().destroy();
            }
            $('#table-kelas').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-kelas-get-data', ['id_kegiatan' => $id_kegiatan]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_kelas',
                        name: 'nama_kelas',
                        className: 'va-middle'
                    },
                    {
                        data: 'jumlah_peserta',
                        name: 'jumlah_peserta',
                        className: 'va-middle text-center'
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
