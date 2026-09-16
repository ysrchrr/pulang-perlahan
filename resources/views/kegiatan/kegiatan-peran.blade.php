@extends('layouts.app')
@push('styles')
    <style>
        #table-kegiatan_wrapper,
        #table-kegiatan_wrapper .table-responsive,
        #table-kegiatan_wrapper .dataTables_scroll,
        #table-kegiatan_wrapper .dataTables_scrollBody,
        #table-kegiatan_wrapper .dataTables_scrollHead,
        #table-kegiatan,
        #table-kegiatan tbody,
        #table-kegiatan tr,
        #table-kegiatan td,
        .card,
        .card-body {
            overflow: visible !important;
        }

        #table-kegiatan td {
            position: relative;
        }

        #table-kegiatan .dropdown-menu {
            z-index: 2000;
        }

        body>.dropdown-menu.dropdown-menu-floating {
            position: fixed;
            z-index: 99999;
            display: block;
        }
    </style>
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('kegiatan') }}" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Semua
                Kegiatan</a>
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
                                @if ($kegiatan->status == '0')
                                    <div class="flex-shrink-0">
                                        <div class="btn-group">
                                            <button onclick="modalPeran()" class="btn btn-primary">
                                                <i class="fa-solid fa-plus"></i> Tambah Peran
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-secondary alert-border-left fade show" role="alert">
                                            <div><strong>{{ $kegiatan->nama_kegiatan ?? '-' }}</strong> </div>
                                            <div>{{ dateRangeIndo($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai) }}
                                            </div>
                                            <div>{{ $kegiatan->lokasi_kegiatan ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-peran">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Peran</th>
                                                <th class="text-center">Jumlah</th>
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

    <div class="modal fade" id="modal_peran" tabindex="-1" aria-labelledby="modal_peranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPeran">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_peranLabel">Tambah Peran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_kegiatan_peran" name="id_kegiatan_peran">
                        <input type="hidden" id="id_kegiatan" name="id_kegiatan" value="{{ $id_kegiatan }}">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="id_peran">Jabatan</label>
                                <select name="id_peran" id="id_peran" class="form-select">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach ($ref_jabatan as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="jumlah">Kuota</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1"
                                    placeholder="Contoh: 5">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storePeran()">Simpan</button>
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

        function modalPeran() {
            $('#modal_peranLabel').text('Tambah Peran');
            $('#formPeran')[0].reset();
            $('#id_kegiatan_peran').val('');
            $('#modal_peran').modal('show');
        }

        function storePeran() {
            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-peran-store') }}",
                data: {
                    id_kegiatan_peran: $('#id_kegiatan_peran').val(),
                    id_kegiatan: $('#id_kegiatan').val(),
                    id_peran: $('#id_peran').val(),
                    jumlah: $('#jumlah').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_peran').modal('hide');
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
            $('#modal_peranLabel').text('Edit Peran');
            $('#id_kegiatan_peran').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-peran-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#id_peran').val(response.data.id_peran);
                        $('#jumlah').val(response.data.kuota);
                    }
                    $('#modal_peran').modal('show');
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
                        url: "{{ route('kegiatan-peran-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-peran')) {
                $('#table-peran').DataTable().destroy();
            }
            $('#table-peran').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-peran-get-data', ['id_kegiatan' => $id_kegiatan]) }}",
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
                        className: 'va-middle'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
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
