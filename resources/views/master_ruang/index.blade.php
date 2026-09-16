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
                                        <button onclick="exportRuang()" class="btn btn-success">
                                            <i class="fa-solid fa-file-excel"></i> Export
                                        </button>
                                        <button onclick="modalImport()" class="btn btn-info">
                                            <i class="fa-solid fa-file-import"></i> Import
                                        </button>
                                        <button onclick="modalRuang()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Ruang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-ruang">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Ruang</th>
                                                <th class="text-center">Gedung</th>
                                                <th class="text-center">Lantai</th>
                                                <th class="text-center">Jenis Ruang</th>
                                                <th class="text-center">Kapasitas</th>
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
    <div class="modal fade" id="modal_ruang" tabindex="-1" aria-labelledby="modal_ruangLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formRuang">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_ruangLabel">Tambah Ruang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id_ruang" name="id_ruang">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="gedung_id">Gedung <span class="text-danger">*</span></label>
                                <select name="gedung_id" id="gedung_id" class="form-control" style="width: 100%;">
                                    <option value="">-- Pilih Gedung --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kode_ruang">Kode Ruang</label>
                                <input type="text" name="kode_ruang" id="kode_ruang" class="form-control"
                                    placeholder="Contoh: R-101">
                            </div>
                            <div class="col-md-6">
                                <label for="nama_ruang">Nama Ruang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_ruang" name="nama_ruang"
                                    placeholder="Contoh: Ruang Kuliah 101">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="lantai">Lantai</label>
                                <input type="text" name="lantai" id="lantai" class="form-control"
                                    placeholder="Contoh: 1">
                            </div>
                            <div class="col-md-4">
                                <label for="jenis_ruang">Jenis Ruang</label>
                                <input type="text" name="jenis_ruang" id="jenis_ruang" class="form-control"
                                    placeholder="Contoh: Kelas">
                            </div>
                            <div class="col-md-4">
                                <label for="kapasitas">Kapasitas</label>
                                <input type="number" name="kapasitas" id="kapasitas" class="form-control"
                                    placeholder="Contoh: 40">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="is_active">Status</label>
                                <select name="is_active" id="is_active" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeRuang()">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Import -->
    <div class="modal fade" id="modal_import" tabindex="-1" aria-labelledby="modal_importLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formImport" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_importLabel">Import Ruang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Silakan unduh template excel berikut untuk mengunggah data ruang secara massal.
                            <br>
                            <a href="{{ route('master-ruang-template') }}" class="btn btn-sm btn-info mt-2">
                                <i class="fa-solid fa-download"></i> Unduh Template
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">File Excel (.xlsx, .xls)</label>
                            <input type="file" class="form-control" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="importRuang()">Proses Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let select2Initialized = false;

        $(document).ready(function() {
            loadTable();

            // Initialize Select2 when modal is shown (element must be visible)
            $('#modal_ruang').on('shown.bs.modal', function() {
                if (!select2Initialized) {
                    initSelect2Gedung();
                    select2Initialized = true;
                }
            });
        });

        function initSelect2Gedung() {
            $('#gedung_id').select2({
                dropdownParent: $('#modal_ruang'),
                placeholder: '-- Pilih Gedung --',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('master-ruang-get-gedung') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });
        }

        function modalRuang() {
            $('#modal_ruangLabel').text('Tambah Ruang');
            $('#id_ruang').val('');
            $('#kode_ruang').val('');
            $('#nama_ruang').val('');
            $('#lantai').val('');
            $('#jenis_ruang').val('');
            $('#kapasitas').val('');
            $('#is_active').val('1');
            $('#gedung_id').val(null).trigger('change');
            $('#modal_ruang').modal('show');
        }

        function storeRuang() {
            let id_ruang = $('#id_ruang').val();
            let gedung_id = $('#gedung_id').val();
            let kode_ruang = $('#kode_ruang').val();
            let nama_ruang = $('#nama_ruang').val();
            let lantai = $('#lantai').val();
            let jenis_ruang = $('#jenis_ruang').val();
            let kapasitas = $('#kapasitas').val();
            let is_active = $('#is_active').val();

            $.ajax({
                type: "POST",
                url: "{{ route('master-ruang-store') }}",
                data: {
                    id_ruang: id_ruang,
                    gedung_id: gedung_id,
                    kode_ruang: kode_ruang,
                    nama_ruang: nama_ruang,
                    lantai: lantai,
                    jenis_ruang: jenis_ruang,
                    kapasitas: kapasitas,
                    is_active: is_active
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_ruang').modal('hide');
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
            $('#modal_ruangLabel').text('Edit Ruang');
            $('#id_ruang').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('master-ruang-get-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        let data = response.data;
                        $('#kode_ruang').val(data.kode_ruang);
                        $('#nama_ruang').val(data.nama_ruang);
                        $('#lantai').val(data.lantai);
                        $('#jenis_ruang').val(data.jenis_ruang);
                        $('#kapasitas').val(data.kapasitas);
                        $('#is_active').val(data.is_active);

                        // Set gedung Select2 value
                        if (data.gedung) {
                            let option = new Option(
                                data.gedung.nama_gedung + ' (' + data.gedung.kode_gedung + ')',
                                data.gedung.id,
                                true,
                                true
                            );
                            $('#gedung_id').append(option).trigger('change');
                        } else {
                            $('#gedung_id').val(null).trigger('change');
                        }
                    }
                    $('#modal_ruang').modal('show');
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
                        url: "{{ route('master-ruang-delete') }}",
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

        function exportRuang() {
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

            window.location.href = "{{ route('master-ruang-export') }}";

            setTimeout(() => {
                Swal.close();
            }, 3000);
        }

        function modalImport() {
            $('#modal_import').modal('show');
            $('#formImport')[0].reset();
        }

        function importRuang() {
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
                url: "{{ route('master-ruang-import') }}",
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
            if ($.fn.DataTable.isDataTable('#table-ruang')) {
                $('#table-ruang').DataTable().destroy();
            }
            $('#table-ruang').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('master-ruang-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_ruang_col',
                        name: 'nama_ruang',
                        className: 'va-middle'
                    },
                    {
                        data: 'nama_gedung',
                        name: 'nama_gedung',
                        className: 'va-middle'
                    },
                    {
                        data: 'lantai_col',
                        name: 'lantai',
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'jenis_ruang_col',
                        name: 'jenis_ruang',
                        className: 'va-middle'
                    },
                    {
                        data: 'kapasitas_col',
                        name: 'kapasitas',
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'status_col',
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
