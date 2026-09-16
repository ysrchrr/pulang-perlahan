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
                                        <button onclick="modalTimKerja()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Tim Kerja
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-tim-kerja">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Tim Kerja</th>
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
    <div class="modal fade" id="modal_tim_kerja" tabindex="-1" aria-labelledby="modal_tim_kerjaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTimKerja">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_tim_kerjaLabel">Tambah Tim kerja</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_tim_kerja" name="id_tim_kerja">
                                <label for="nama_tim_kerja">Nama Tim Kerja</label>
                                <input type="text" class="form-control" id="nama_tim_kerja" name="nama_tim_kerja"
                                    placeholder="Contoh: Tim Pengembang">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeTimKerja()">Simpan</button>
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

        function modalTimKerja() {
            $('#modal_tim_kerjaLabel').text('Tambah Tim Kerja');
            $('#formTimKerja')[0].reset();
            $('#id_tim_kerja').val('');
            $('#modal_tim_kerja').modal('show');
        }

        function storeTimKerja() {
            $.ajax({
                type: "POST",
                url: "{{ route('manajemen-tim-kerja-store') }}",
                data: {
                    id_tim_kerja: $('#id_tim_kerja').val(),
                    nama_tim_kerja: $('#nama_tim_kerja').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_tim_kerja').modal('hide');
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
            $('#modal_tim_kerjaLabel').text('Edit Tim Kerja');
            $('#id_tim_kerja').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('manajemen-tim-kerja-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_tim_kerja').val(response.data.nama_tim_kerja);
                    }
                    $('#modal_tim_kerja').modal('show');
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
                        url: "{{ route('manajemen-tim-kerja-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-tim-kerja')) {
                $('#table-tim-kerja').DataTable().destroy();
            }
            $('#table-tim-kerja').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('manajemen-tim-kerja-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_tim_kerja',
                        name: 'nama_tim_kerja',
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
