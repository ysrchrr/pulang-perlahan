@extends('layouts.app')

@section('contents')
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('eval-penyelenggaraan') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-0">{{ $page_title }}</h4>
                        <div class="text-muted mt-1">{{ $template->nama_template }}</div>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary" onclick="modalSoal()">
                            Tambah Soal
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle nowrap" id="table-soal">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Soal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-soal" tabindex="-1" aria-labelledby="modal-soalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formSoal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-soalLabel">Tambah Soal</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_soal" id="id_soal">
                        <input type="hidden" name="id_template" id="id_template" value="{{ enc_id($template->id) }}">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="soal">Soal</label>
                                <textarea name="soal" id="soal" class="form-control" rows="4" placeholder="Tulis soal di sini"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeSoal()">Simpan</button>
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

        function modalSoal() {
            $('#modal-soalLabel').text('Tambah Soal');
            $('#formSoal')[0].reset();
            $('#id_soal').val('');
            $('#id_template').val("{{ enc_id($template->id) }}");
            $('#modal-soal').modal('show');
        }

        function storeSoal() {
            $.ajax({
                type: "POST",
                url: "{{ route('eval-penyelenggaraan-soal-store') }}",
                data: $('#formSoal').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal-soal').modal('hide');
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

        function toEditSoal(id) {
            $('#modal-soalLabel').text('Edit Soal');
            $('#formSoal')[0].reset();
            $('#id_soal').val(id);
            $('#id_template').val("{{ enc_id($template->id) }}");

            $.ajax({
                type: "POST",
                url: "{{ route('eval-penyelenggaraan-soal-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#soal').val(response.data.soal);
                    }

                    $('#modal-soal').modal('show');
                },
                error: function(xhr) {
                    iziToast.error({
                        title: "Gagal!",
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function toRemoveSoal(id) {
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
                        url: "{{ route('eval-penyelenggaraan-soal-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-soal')) {
                $('#table-soal').DataTable().destroy();
            }

            $('#table-soal').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('eval-penyelenggaraan-soal-get-data', ['id_template' => enc_id($template->id)]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'soal',
                        name: 'soal',
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
