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
                                    <button onclick="modalUser()" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i> Tambah User
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-user">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Lengkap</th>
                                                <th class="text-center">Username</th>
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
    <div class="modal fade" id="modal_user" tabindex="-1" aria-labelledby="modal_userLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_userLabel">Tambah User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_user" id="id_user">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nama User</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Contoh: Bahri Siregar">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Username</label>
                            <input type="text" name="email" id="email" class="form-control"
                                placeholder="Contoh: namakamu@email.com">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Role</label>
                            <div class="row mt-2">
                                @foreach ($ref_roles as $item)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox"
                                                value="{{ $item->id }}">
                                            <label class="form-check-label">
                                                {{ $item->role_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="storeUser()">Simpan</button>
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

        function modalUser(id) {
            $('#id_user').val('');
            $('#name').val('');
            $('#email').val('');
            $('#id_user, #name, #email').prop('readonly', false);
            $('.role-checkbox').prop('checked', false);

            if (id) {
                $('#id_user').val(id);
                $('#id_user, #name, #email').prop('readonly', true);
                $('#modal_userLabel').text('Edit User');

                $.ajax({
                    type: "POST",
                    url: "{{ route('manajemen-user-detail') }}",
                    data: {
                        user_id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        let roleIds = response.data.roles.map(function(role) {
                            return role.id;
                        });
                        $('.role-checkbox').prop('checked', false);

                        $('.role-checkbox').each(function() {
                            let checkboxVal = parseInt($(this).val());

                            if (roleIds.includes(checkboxVal)) {
                                $(this).prop('checked', true);
                            }
                        });

                        $('#name').val(response.data.name);
                        $('#email').val(response.data.email);
                    }
                });
            } else {
                $('#modal_userLabel').text('Tambah User');
            }
            $('#modal_user').modal('show');
        }

        function storeUser() {
            let roles = [];

            $('.role-checkbox:checked').each(function() {
                roles.push($(this).val());
            });

            let data_input = {
                id_user: $('#id_user').val(),
                nama: $('#name').val(),
                email: $('#email').val(),
                roles: roles
            }

            $.ajax({
                type: "POST",
                url: "{{ route('manajemen-user-store') }}",
                data: {
                    data: data_input
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.message
                        });

                        $('#modal_user').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: 'Oops!',
                            message: 'Terjadi kesalahan saat menambahkan user'
                        });
                    }
                }
            });
        }

        function toRemove(id) {
            Swal.fire({
                text: "Hapus user ini?",
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
                        url: "{{ route('manajemen-user-delete') }}",
                        data: {
                            user_id: id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Okee!',
                                    message: response.message
                                });
                                loadTable();
                            } else {
                                iziToast.error({
                                    title: 'Oops!',
                                    message: response.message
                                });
                            }
                        }
                    });
                }
            });
        }

        function confirmResetPwd(id) {
            Swal.fire({
                text: "Reset password user ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Gaskeun!',
                cancelBUtotonTExt: 'Gajadi'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('manajemen-user-reset-password') }}",
                        data: {
                            user_id: id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Okee!',
                                    message: response.message
                                });
                                loadTable();
                            } else {
                                iziToast.error({
                                    title: 'Oops!',
                                    message: response.message
                                });
                            }
                        }
                    });
                }
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-user')) {
                $('#table-user').DataTable().destroy();
            }
            $('#table-user').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('manajemen-user-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'va-middle'
                    },
                    {
                        data: 'email',
                        name: 'email',
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

        function impersonate(id) {
            Swal.fire({
                text: "Impersonate user ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Gas!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('manajemen-user-impersonate') }}",
                        data: {
                            user_id: id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Berhasil!',
                                    message: response.message
                                });
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1000);
                            } else {
                                iziToast.error({
                                    title: 'Oops!',
                                    message: response.message
                                });
                            }
                        }
                    });
                }
            });
        }

        function sendAccount(id) {
            Swal.fire({
                text: "Kirim detail akun ke email user ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('manajemen-user-send-account') }}",
                        data: {
                            user_id: id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Berhasil!',
                                    message: response.message
                                });
                            } else {
                                iziToast.error({
                                    title: 'Oops!',
                                    message: response.message
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
