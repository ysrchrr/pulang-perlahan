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
                                    <button onclick="modalMenu()" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i> Tambah Menu
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-menu">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Menu</th>
                                                <th class="text-center">Parent Menu</th>
                                                <th class="text-center">Icon</th>
                                                <th class="text-center">Link</th>
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

    <!-- Modal -->
    <div class="modal fade" id="modal_menu" tabindex="-1" aria-labelledby="modal_menuLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formMenu">

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_menuLabel">Tambah Menu</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_menu" id="id_menu">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Parent Menu</label>
                                <select name="parent_id" id="parent_id" class="form-select">
                                    <option value="">-- Pilih Parent (Kosongkan jika menu utama) --</option>
                                    @foreach ($menu_parent as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->parent_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nama Menu -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="menu_name" id="menu_name" class="form-control"
                                    placeholder="Contoh: Dashboard Monitoring" required>
                            </div>
                        </div>

                        <!-- Slug & Icon -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug_name" id="slug_name" class="form-control"
                                    placeholder="Contoh: dashboard-monitoring" required>
                                <small class="text-muted">URL: /dashboard-monitoring (bisa custom kalau mau)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Icon</label>
                                <input type="text" name="icon" id="icon" class="form-control"
                                    placeholder="Contoh: <svg>...</svg>">
                                <small class="text-muted">Gunakan <a href="https://lucide.dev/icons/" target="_blank">Lucide
                                        Icons</a> – Copy SVG</small>
                            </div>
                        </div>

                        <!-- Order & Status -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="menu_order" id="menu_order" class="form-control" placeholder="0"
                                    value="0" min="0">
                                <small class="text-muted">Semakin kecil, semakin atas</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="is_active" id="is_active" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Roles & Permissions -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Role & Permissions <span class="text-danger">*</span></label>
                                <div class="border rounded p-3">
                                    @foreach ($roles as $role)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox" type="checkbox" name="roles[]"
                                                value="{{ $role->id }}" id="role_{{ $role->id }}">
                                            <label class="form-check-label fw-bold" for="role_{{ $role->id }}">
                                                {{ $role->role_name }}
                                            </label>
                                            <div class="ms-4 mt-2 permissions-group" id="permissions_{{ $role->id }}"
                                                style="display:none;">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[{{ $role->id }}][]" value="view"
                                                        id="perm_{{ $role->id }}_view">
                                                    <label class="form-check-label"
                                                        for="perm_{{ $role->id }}_view">View</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[{{ $role->id }}][]" value="create"
                                                        id="perm_{{ $role->id }}_create">
                                                    <label class="form-check-label"
                                                        for="perm_{{ $role->id }}_create">Create</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[{{ $role->id }}][]" value="edit"
                                                        id="perm_{{ $role->id }}_edit">
                                                    <label class="form-check-label"
                                                        for="perm_{{ $role->id }}_edit">Edit</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[{{ $role->id }}][]" value="delete"
                                                        id="perm_{{ $role->id }}_delete">
                                                    <label class="form-check-label"
                                                        for="perm_{{ $role->id }}_delete">Delete</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Centang role yang bisa mengakses menu ini</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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

        function modalMenu(id = null) {
            if (id) {
                // Mode EDIT - fetch data menu
                $.ajax({
                    url: "{{ route('superadmin-manajemen-menu-detail') }}",
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.status === 'success') {
                            const menu = response.data;

                            // Fill form fields
                            $('#id_menu').val(id);
                            $('#menu_name').val(menu.menu_name);
                            $('#slug_name').val(menu.slug_name);
                            $('#parent_id').val(menu.parent_id);
                            $('#icon').val(menu.icon);
                            $('#menu_order').val(menu.menu_order);
                            $('#is_active').val(menu.is_active);

                            // Reset semua checkbox role & permissions
                            $('.role-checkbox').prop('checked', false);
                            $('.permissions-group').hide();
                            $('.permissions-group input[type="checkbox"]').prop('checked', false);

                            // Set roles & permissions yang sudah ada
                            if (menu.roles && menu.roles.length > 0) {
                                menu.roles.forEach(function(role) {
                                    // Check role checkbox
                                    $('#role_' + role.id).prop('checked', true);

                                    // Show permissions group
                                    $('#permissions_' + role.id).show();

                                    // Check permissions yang aktif
                                    if (role.pivot.permissions) {
                                        const permissions = JSON.parse(role.pivot.permissions);
                                        permissions.forEach(function(perm) {
                                            $('#perm_' + role.id + '_' + perm).prop('checked',
                                                true);
                                        });
                                    }
                                });
                            }

                            // Show modal
                            $('#modal_menu').modal('show');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data menu: ' + (xhr.responseJSON?.message ||
                                'Terjadi kesalahan')
                        });
                    }
                });
            } else {
                // Mode CREATE - reset form
                $('#formMenu')[0].reset();
                $('#id_menu').val('');

                // Reset semua checkbox
                $('.role-checkbox').prop('checked', false);
                $('.permissions-group').hide();
                $('.permissions-group input[type="checkbox"]').prop('checked', false);

                // Show modal
                $('#modal_menu').modal('show');
            }
        }

        // Toggle permissions ketika role di-check
        $('.role-checkbox').on('change', function() {
            const roleId = $(this).val();
            const permissionsDiv = $('#permissions_' + roleId);

            if ($(this).is(':checked')) {
                permissionsDiv.show();
            } else {
                permissionsDiv.hide();
                permissionsDiv.find('input[type="checkbox"]').prop('checked', false);
            }
        });

        // Auto-generate slug dari nama menu
        $('#menu_name').on('keyup', function() {
            const slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
            $('#slug_name').val(slug);
        });

        // Submit form menu
        $('#formMenu').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');

            // Disable button & show loading
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: "{{ route('superadmin-manajemen-menu-store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Reset form & close modal
                        $('#formMenu')[0].reset();
                        $('#modal_menu').modal('hide');

                        // Reload table atau redirect
                        loadTable();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan!';

                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorList = '<ul class="text-start mb-0">';

                        $.each(errors, function(key, value) {
                            errorList += '<li>' + value[0] + '</li>';
                        });

                        errorList += '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorList
                        });
                    } else {
                        // Server error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || errorMessage
                        });
                    }
                },
                complete: function() {
                    // Enable button & restore text
                    submitBtn.prop('disabled', false).html('Simpan');
                }
            });
        });

        // Buka modal untuk tambah menu
        $('#btnTambahMenu').on('click', function() {
            $('#formMenu')[0].reset();
            $('#id_menu').val('');
            $('#modalMenu').modal('show');
        });

        $(document).on('change', '.code-switcher', function() {
            let isChecked = $(this).is(':checked');
            let id = $(this).data('id-role');

            $.ajax({
                url: "{{ route('superadmin-manajemen-menu-set-status') }}",
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

        function toRemove(id) {
            Swal.fire({
                text: "Hapus menu ini?",
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
                        url: "{{ route('superadmin-manajemen-menu-delete') }}",
                        data: {
                            menu_id: id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === 'success') {
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
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Oops!',
                                message: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menghapus menu'
                            });
                        }
                    });
                }
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-menu')) {
                $('#table-menu').DataTable().destroy();
            }
            $('#table-menu').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('superadmin-manajemen-menu-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'menu_name',
                        name: 'menu_name',
                        className: 'va-middle'
                    },
                    {
                        data: 'parent_menu',
                        name: 'parent_menu',
                        className: 'va-middle text-center'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        className: 'text-center va-middle',
                    },
                    {
                        data: 'link',
                        name: 'link',
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
    </script>
@endpush
