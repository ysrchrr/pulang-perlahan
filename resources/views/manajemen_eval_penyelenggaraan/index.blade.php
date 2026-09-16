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
                                        <button onclick="modalTemplate()" class="btn btn-primary">
                                            Tambah Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-template">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Template</th>
                                                <th class="text-center">Jumlah Soal</th>
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
    <div class="modal fade" id="modal-template" tabindex="-1" aria-labelledby="modal-templateLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formTemplate" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-templateLabel">Tambah Template</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_template" name="id_template">
                                <label for="nama_template">Nama Template</label>
                                <input type="text" class="form-control" id="nama_template" name="nama_template"
                                    placeholder="Contoh: Evaluasi Penyelenggaraan Pelatihan">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeTemplate()">Simpan</button>
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

        function modalTemplate() {
            $('#modal-templateLabel').text('Tambah Template');
            $('#formTemplate')[0].reset();
            $('#id_template').val('');
            $('#nama_template').val('');
            $('#modal-template').modal('show');
        }

        function storeTemplate() {
            let formData = new FormData($('#formTemplate')[0]);

            $.ajax({
                type: "POST",
                url: "{{ route('eval-penyelenggaraan-store') }}",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal-template').modal('hide');
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
            $('#modal-templateLabel').text('Edit Template');
            $('#formTemplate')[0].reset();
            $('#id_template').val(id);

            $.ajax({
                type: "POST",
                url: "{{ route('eval-penyelenggaraan-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_template').val(response.data.nama_template);
                    }

                    $('#modal-template').modal('show');
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
                        url: "{{ route('eval-penyelenggaraan-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-template')) {
                $('#table-template').DataTable().destroy();
            }
            $('#table-template').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('eval-penyelenggaraan-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_template',
                        name: 'nama_template',
                        className: 'va-middle'
                    },
                    {
                        data: 'jumlah_soal',
                        name: 'jumlah_soal',
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
