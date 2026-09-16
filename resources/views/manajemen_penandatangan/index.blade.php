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
                                        <button onclick="modalPenandatangan()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Tambah Penandatangan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-penandatangan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Penandatangan</th>
                                                <th class="text-center">Cap</th>
                                                <th class="text-center">Signature</th>
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
    <div class="modal fade" id="modal_penandatangan" tabindex="-1" aria-labelledby="modal_penandatanganLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formPenandatangan" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_penandatanganLabel">Tambah Penandatangan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="id_penandatangan" name="id_penandatangan">
                                <label for="nama_penandatangan">Nama Penandatangan</label>
                                <input type="text" class="form-control" id="nama_penandatangan" name="nama_penandatangan"
                                    placeholder="Contoh: John Doe">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan"
                                    placeholder="Contoh: Kepala BGTK Jambi">
                            </div>
                            <div class="col-md-6">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip"
                                    placeholder="Contoh: 123456789">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="">Cap / Stempel</label>
                                <input type="file" name="path_cap" id="path_cap" class="form-control"
                                    accept=".jpg,.jpeg,.png">
                                <small class="text-danger">Maks 1 mb (.jpg / .png)</small>
                                <div id="preview_cap" class="mt-2"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Tandatangan</label>
                                <input type="file" name="path_signature" id="path_signature" class="form-control"
                                    accept=".jpg,.jpeg,.png">
                                <small class="text-danger">Maks 1 mb (.jpg / .png)</small>
                                <div id="preview_signature" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storePenandatangan()">Simpan</button>
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

        function modalPenandatangan() {
            $('#modal_penandatanganLabel').text('Tambah Penandatangan');
            $('#formPenandatangan')[0].reset();
            $('#id_penandatangan').val('');
            $('#nama_penandatangan').val('');
            $('#nip').val('');
            $('#jabatan').val('');
            $('#preview_cap').html('');
            $('#preview_signature').html('');
            $('#modal_penandatangan').modal('show');
        }

        function storePenandatangan() {
            let formData = new FormData($('#formPenandatangan')[0]);

            $.ajax({
                type: "POST",
                url: "{{ route('penandatangan-sertifikat-store') }}",
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
                        $('#modal_penandatangan').modal('hide');
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
            $('#modal_penandatanganLabel').text('Edit Penandatangan');
            $('#formPenandatangan')[0].reset();
            $('#id_penandatangan').val(id);
            $('#preview_cap').html('');
            $('#preview_signature').html('');

            $.ajax({
                type: "POST",
                url: "{{ route('penandatangan-sertifikat-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#nama_penandatangan').val(response.data.nama_penandatangan);
                        $('#nip').val(response.data.nip);
                        $('#jabatan').val(response.data.jabatan);

                        if (response.cap_url) {
                            $('#preview_cap').html(
                                '<a href="' + response.cap_url + '" target="_blank">Lihat cap saat ini</a>');
                        }

                        if (response.signature_url) {
                            $('#preview_signature').html(
                                '<a href="' + response.signature_url +
                                '" target="_blank">Lihat tandatangan saat ini</a>');
                        }
                    }

                    $('#modal_penandatangan').modal('show');
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
                        url: "{{ route('penandatangan-sertifikat-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-penandatangan')) {
                $('#table-penandatangan').DataTable().destroy();
            }
            $('#table-penandatangan').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('penandatangan-sertifikat-get-data') }}",
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
                        data: 'cap',
                        name: 'cap',
                        className: 'va-middle text-center'
                    },
                    {
                        data: 'signature',
                        name: 'signature',
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
