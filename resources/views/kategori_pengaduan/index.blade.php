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
                                        <button onclick="exportKategori()" class="btn btn-success">
                                            <i class="fa-solid fa-file-excel"></i> Export
                                        </button>
                                        <button onclick="modalImport()" class="btn btn-info">
                                            <i class="fa-solid fa-file-import"></i> Import
                                        </button>
                                        <button onclick="modalKategoriPengaduan()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Kategori
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-kategori-pengaduan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Kategori</th>
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
    <div class="modal fade" id="modal_kategori_pengaduan" tabindex="-1" aria-labelledby="modal_kategori_pengaduanLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formKategoriPengaduan">

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_kategori_pengaduanLabel">Tambah Kategori Pengaduan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_kategori" name="id_kategori">
                                <label for="nama_kategori">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                    placeholder="Contoh: Keamanan & Keselamatan">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeKategori()">Simpan</button>
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

        function modalKategoriPengaduan() {
            $('#modal_kategori_pengaduan').modal('show');
            $('#id_kategori').val('');
            $('#nama_kategori').val('');
        }

        function storeKategori() {
            let id_kategori = $('#id_kategori').val();
            let nama_kategori = $('#nama_kategori').val();

            $.ajax({
                type: "POST",
                url: "{{ route('kategori-pengaduan-store') }}",
                data: {
                    id_kategori: id_kategori,
                    nama_kategori: nama_kategori
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_kategori_pengaduan').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                }
            });
        }

        function toEdit(id) {
            $('#modal_kategori_pengaduanLabel').text('Edit Kategori Pengaduan');
            $('#id_kategori').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('kategori-pengaduan-get-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_kategori').val(response.data.nama_kategori);
                    }
                    $('#modal_kategori_pengaduan').modal('show');
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
                        url: "{{ route('kategori-pengaduan-delete') }}",
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
                            showMessage('Terjadi kesalahan. Coba lagi.', 'error');
                        }
                    });
                }
            });
        }

        }

        function exportKategori() {
            Swal.fire({
                title: 'Sedang mengekspor...',
                text: 'Harap tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = "{{ route('kategori-pengaduan-export') }}";

            setTimeout(() => {
                Swal.close();
            }, 3000);
        }

        function modalImport() {
            $('#modal_import').modal('show');
            $('#formImport')[0].reset();
        }

        function importKategori() {
            let formData = new FormData($('#formImport')[0]);

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Harap tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('kategori-pengaduan-import') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.close();
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_import').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    iziToast.error({
                        title: "Gagal!",
                        message: "Terjadi kesalahan saat memproses data."
                    });
                }
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-kategori-pengaduan')) {
                $('#table-kategori-pengaduan').DataTable().destroy();
            }
            $('#table-kategori-pengaduan').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('kategori-pengaduan-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                        className: 'va-middle'
                    },
                    {
                        data: 'dibuat_oleh',
                        name: 'dibuat_oleh',
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
