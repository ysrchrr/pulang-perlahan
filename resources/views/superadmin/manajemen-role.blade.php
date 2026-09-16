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
                                    <button onclick="modalRole()" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i> Tambah Role
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-role">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Role</th>
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

    <div class="modal fade" id="modal_role" tabindex="-1" aria-labelledby="modal_roleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_roleLabel">Tambah Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_role">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="">Nama Role</label>
                            <input type="text" name="nama_role" id="nama_role" class="form-control"
                                placeholder="Contoh: Monitoring">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="">Slug</label>
                            <input type="text" name="slug_name" id="slug_name" class="form-control"
                                placeholder="Contoh: monitoring">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" rows="3"
                                placeholder="Deskripsi role"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="storeRole()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            loadTable();
            // $('#table-role').DataTable();
        });

        function modalRole(id) {
            if (id) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('superadmin-manajemen-role-detail') }}",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#id_role').val(response.id);
                        $('#nama_role').val(response.role_name);
                        $('#slug_name').val(response.slug_name);
                        $('#description').val(response.description);
                        $('#modal_roleLabel').text('Edit Role');
                    }
                });
            } else {
                $('#id_role').val('');
                $('#nama_role').val('');
                $('#slug_name').val('');
                $('#description').val('');
                $('#modal_roleLabel').text('Tambah Role');
            }

            $('#modal_role').modal('show');
        }

        $('#nama_role').on('keyup', function() {
            if ($('#id_role').val()) {
                return;
            }

            let slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');

            $('#slug_name').val(slug);
        });

        function storeRole() {
            let data_input = {
                id: $('#id_role').val(),
                role_name: $('#nama_role').val(),
                slug_name: $('#slug_name').val(),
                description: $('#description').val(),
            }

            $.ajax({
                type: "POST",
                url: "{{ route('superadmin-manajemen-role-store') }}",
                data: {
                    data: data_input
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: 'Berhasil',
                            message: response.msg,
                        });
                        $('#modal_role').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: 'Gagal',
                            message: response.msg,
                        });
                    }
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Gagal',
                        message: xhr.responseJSON?.msg || 'Terjadi kesalahan saat menyimpan role',
                    });
                }
            });
        }

        function toRemove(id) {
            Swal.fire({
                text: "Hapus role ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('superadmin-manajemen-role-delete') }}",
                        data: {
                            id: id
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Berhasil',
                                    message: response.msg,
                                });
                                loadTable();
                            } else {
                                iziToast.error({
                                    title: 'Gagal',
                                    message: response.msg,
                                });
                            }
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Gagal',
                                message: xhr.responseJSON?.msg || 'Terjadi kesalahan saat menghapus role',
                            });
                        }
                    });
                }
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-role')) {
                $('#table-role').DataTable().destroy();
            }
            $('#table-role').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('superadmin-manajemen-role-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name',
                        className: 'va-middle'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        className: 'text-center va-middle'
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

        $(document).on('change', '.code-switcher', function() {
            let isChecked = $(this).is(':checked');
            let id = $(this).data('id-role');

            $.ajax({
                url: "{{ route('superadmin-manajemen-role-set-status') }}",
                type: 'POST',
                data: {
                    data: {
                        id: id,
                        is_active: isChecked ? 1 : 0
                    }
                },
                dataType: 'json',
                success: function(response) {
                    // Success feedback
                    iziToast.success({
                        title: 'Berhasil',
                        message: response.msg,
                    });
                },
                error: function() {
                    // Error feedback & revert checkbox
                    iziToast.error({
                        title: 'Gagal',
                        message: 'Terjadi kesalahan saat mengubah status role',
                    });
                }
            });
        });
    </script>
@endpush
