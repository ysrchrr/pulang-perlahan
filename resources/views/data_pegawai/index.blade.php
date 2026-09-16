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
                                    <button onclick="modalImport()" class="btn btn-secondary">
                                        <i class="fa-solid fa-upload"></i> Import Pegawai
                                    </button>
                                    <a href="{{ route('data-pegawai-create') }}" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i> Tambah Pegawai
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-pegawai">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">NIP</th>
                                                <th class="text-center">NIK</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Keaktifan</th>
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
    <div class="modal fade" id="modal_import_pegawai" tabindex="-1" aria-labelledby="modal_import_pegawaiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formImportPegawai" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_import_pegawaiLabel">Import Pegawai</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="template">Template Import</label><br>
                                <a href="{{ route('data-pegawai-template') }}"
                                    class="btn btn-danger btn-sm"> <i class="fas fa-download"></i> Unduh
                                    Template (.xlsx)</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="file">Pilih File</label>
                                <input type="file" class="form-control" id="file" name="file">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeImportPegawai()">Simpan</button>
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

        function modalImport() {
            $('#modal_import_pegawai').modal('show');
            $('#formImportPegawai')[0].reset();
        }

        function storeImportPegawai() {
            let formData = new FormData($('#formImportPegawai')[0]);

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
                url: "{{ route('data-pegawai-import') }}",
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
                        $('#modal_import_pegawai').modal('hide');
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
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan saat import.'
                    });
                }
            });
        }

        $(document).on('change', '.switch-keaktifan', function() {
            var $switch = $(this);
            var isChecked = $switch.is(':checked');
            var previousState = !isChecked;

            $.ajax({
                url: "{{ route('data-pegawai-update-keaktifan') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: $switch.data('id'),
                    is_active: isChecked ? '1' : '0'
                },
                beforeSend: function() {
                    $switch.prop('disabled', true);
                },
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                    } else {
                        $switch.prop('checked', previousState);
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    $switch.prop('checked', previousState);
                    iziToast.error({
                        title: "Gagal!",
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                },
                complete: function() {
                    $switch.prop('disabled', false);
                }
            });
        });

        function toEdit(id) {
            window.location.href = "{{ url('/data-pegawai-edit') }}/" + id;
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
                        url: "{{ route('data-pegawai-delete') }}",
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

        function createAccount(button, id) {
            var $button = $(button);
            var originalHtml = $button.html();

            $.ajax({
                url: "{{ route('data-pegawai-create-account') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
                beforeSend: function() {
                    $button.prop('disabled', true);
                    $button.html('<i class="fa-solid fa-circle-notch fa-spin"></i> Buat Akun');
                },
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
                    iziToast.error({
                        title: "Gagal!",
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                },
                complete: function() {
                    $button.prop('disabled', false);
                    $button.html(originalHtml);
                    loadTable();
                }
            });
        }

        function sendAccount(id) {
            Swal.fire({
                icon: 'success',
                text: 'Berhasil mengirim akun ke email'
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-pegawai')) {
                $('#table-pegawai').DataTable().destroy();
            }
            $('#table-pegawai').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('data-pegawai-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        className: 'va-middle text-left'
                    },
                    {
                        data: 'nip',
                        name: 'nip',
                        className: 'va-middle text-left'
                    },
                    {
                        data: 'nik',
                        name: 'nik',
                        className: 'va-middle text-left'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'va-middle text-left'
                    },
                    {
                        data: 'keaktifan',
                        name: 'keaktifan',
                        className: 'va-middle text-left'
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
