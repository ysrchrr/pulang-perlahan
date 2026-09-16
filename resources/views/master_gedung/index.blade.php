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
                                        <button onclick="exportGedung()" class="btn btn-success">
                                            <i class="fa-solid fa-file-excel"></i> Export
                                        </button>
                                        <button onclick="modalImport()" class="btn btn-info">
                                            <i class="fa-solid fa-file-import"></i> Import
                                        </button>
                                        <button onclick="modalGedung()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Gedung
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-gedung">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Gedung</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Jumlah Ruang</th>
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

    <!-- Import Modal -->
    <div class="modal fade" id="modal_import" tabindex="-1" aria-labelledby="modal_importLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formImport" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_importLabel">Import Gedung</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            Silakan unduh template Excel terlebih dahulu sebelum melakukan import.
                            <br>
                            <a href="{{ route('master-gedung-template') }}" class="fw-bold"><i class="fa-solid fa-download"></i> Unduh Template</a>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="file_excel">File Excel (.xlsx, .xls)</label>
                                <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx, .xls" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Import</button>
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

        function modalGedung() {
            $('#modal_gedungLabel').text('Tambah Gedung');
            $('#modal_gedung').modal('show');
            $('#id_gedung').val('');
            $('#kode_gedung').val('');
            $('#nama_gedung').val('');
            $('#lokasis_kampus').val('');
            $('#alamat').val('');
        }

        function storeGedung() {
            let id_gedung = $('#id_gedung').val();
            let kode_gedung = $('#kode_gedung').val();
            let nama_gedung = $('#nama_gedung').val();
            let lokasi_kampus = $('#lokasis_kampus').val();
            let alamat = $('#alamat').val();

            $.ajax({
                type: "POST",
                url: "{{ route('master-gedung-store') }}",
                data: {
                    id_gedung: id_gedung,
                    kode_gedung: kode_gedung,
                    nama_gedung: nama_gedung,
                    lokasi_kampus: lokasi_kampus,
                    alamat: alamat
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_gedung').modal('hide');
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
            $('#modal_gedungLabel').text('Edit Gedung');
            $('#id_gedung').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('master-gedung-get-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#kode_gedung').val(response.data.kode_gedung);
                        $('#nama_gedung').val(response.data.nama_gedung);
                        $('#lokasis_kampus').val(response.data.lokasi_kampus);
                        $('#alamat').val(response.data.alamat);
                    }
                    $('#modal_gedung').modal('show');
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
                        url: "{{ route('master-gedung-delete') }}",
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

        function exportGedung() {
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

            // Redirect to export route
            window.location.href = "{{ route('master-gedung-export') }}";

            // Close swal after a delay as we can't easily detect download completion
            setTimeout(() => {
                Swal.close();
            }, 3000);
        }

        function modalImport() {
            $('#modal_import').modal('show');
            $('#formImport')[0].reset();
        }

        $('#formImport').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('master-gedung-import') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
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
                    iziToast.error({
                        title: "Error!",
                        message: xhr.responseJSON ? xhr.responseJSON.msg : "Terjadi kesalahan sistem"
                    });
                }
            });
        });

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-gedung')) {
                $('#table-gedung').DataTable().destroy();
            }
            $('#table-gedung').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('master-gedung-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_gedung',
                        name: 'nama_gedung',
                        className: 'va-middle'
                    },
                    {
                        data: 'lokasi_kampus',
                        name: 'lokasi_kampus',
                        className: 'va-middle'
                    },
                    {
                        data: 'jumlah_ruang',
                        name: 'jumlah_ruang',
                        className: 'text-center va-middle'
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
