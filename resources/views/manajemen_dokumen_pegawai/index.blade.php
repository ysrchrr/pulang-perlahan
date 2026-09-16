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
                                        <button onclick="modalDokumenPegawai()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Dokumen Pegawai
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-dokumen-pegawai">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Dokumen</th>
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
    <div class="modal fade" id="modal_dokumen_pegawai" tabindex="-1" aria-labelledby="modalDokumenPegawaiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formDokumenPegawai">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDokumenPegawaiLabel">Tambah Dokumen Pegawai</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_dokumen_pegawai"
                                    name="id_dokumen_pegawai">
                                <label for="nama_dokumen">Nama Dokumen</label>
                                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen"
                                    placeholder="Contoh: Surat Tugas">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeDokumenPegawai()">Simpan</button>
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

        function modalDokumenPegawai() {
            $('#modalDokumenPegawaiLabel').text('Tambah Dokumen Pegawai');
            $('#formDokumenPegawai')[0].reset();
            $('#id_dokumen_pegawai').val('');
            $('#modal_dokumen_pegawai').modal('show');
        }

        function storeDokumenPegawai() {
            $.ajax({
                type: "POST",
                url: "{{ route('manajemen-dokumen-pegawai-store') }}",
                data: {
                    id_dokumen_pegawai: $('#id_dokumen_pegawai').val(),
                    nama_dokumen: $('#nama_dokumen').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal_dokumen_pegawai').modal('hide');
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
            $('#modalDokumenPegawaiLabel').text('Edit Dokumen Pegawai');
            $('#id_dokumen_pegawai').val(id);
            $.ajax({
                type: "POST",
                url: "{{ route('manajemen-dokumen-pegawai-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_dokumen').val(response.data.nama_dokumen);
                    }
                    $('#modal_dokumen_pegawai').modal('show');
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
                        url: "{{ route('manajemen-dokumen-pegawai-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-dokumen-pegawai')) {
                $('#table-dokumen-pegawai').DataTable().destroy();
            }
            $('#table-dokumen-pegawai').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('manajemen-dokumen-pegawai-get-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_dokumen',
                        name: 'nama_dokumen',
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
