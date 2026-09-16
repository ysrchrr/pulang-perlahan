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
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Filter Jenjang</label>
                                        <select name="filter_jenjang" id="filter_jenjang" class="form-select">
                                            <option value="">-- Pilih Jenjang --</option>
                                            @foreach ($list_jenjang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_jenjang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-template">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Template</th>
                                                <th class="text-center">Mapel</th>
                                                <th class="text-center">Jenjang</th>
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

    <div class="modal fade" id="modal-template" tabindex="-1" aria-labelledby="modal-templateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTemplate" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-templateLabel">Tambah Template</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_template" name="id_template">
                                <label for="nama_template">Nama Template</label>
                                <input type="text" class="form-control" id="nama_template" name="nama_template"
                                    placeholder="Contoh: Evaluasi Narasumber">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="">Mapel</label>
                                <select name="mapel" id="mapel" class="form-select">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach ($list_mapel as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="">Jenjang</label>
                                <select name="jenjang" id="jenjang" class="form-select">
                                    <option value="">-- Pilih Jenjang --</option>
                                    @foreach ($list_jenjang as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_jenjang }}</option>
                                    @endforeach
                                </select>
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

            $('#filter_jenjang').on('change', function() {
                loadTable();
            });
        });

        function modalTemplate() {
            $('#modal-templateLabel').text('Tambah Template');
            $('#formTemplate')[0].reset();
            $('#id_template').val('');
            $('#nama_template').val('');
            $('#mapel').val('');
            $('#jenjang').val('');
            $('#modal-template').modal('show');
        }

        function storeTemplate() {
            let formData = new FormData($('#formTemplate')[0]);

            $.ajax({
                type: "POST",
                url: "{{ route('petakom-master-instrumen-store') }}",
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
                url: "{{ route('petakom-master-instrumen-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_template').val(response.data.nama_instrumen);
                        $('#mapel').val(response.data.mapel);
                        $('#jenjang').val(response.data.jenjang);
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
                        url: "{{ route('petakom-master-instrumen-delete') }}",
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
                ajax: {
                    url: "{{ route('petakom-master-instrumen-get-data') }}",
                    data: function(d) {
                        d.filter_jenjang = $('#filter_jenjang').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_instrumen',
                        name: 'nama_instrumen',
                        className: 'va-middle'
                    },
                    {
                        data: 'mapel',
                        name: 'mapel',
                        className: 'va-middle text-center'
                    },
                    {
                        data: 'jenjang',
                        name: 'jenjang',
                        className: 'va-middle text-center'
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
