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
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                                @if (session('role_id') == 4)
                                    <div class="flex-shrink-0">
                                        <div class="btn-group">
                                            <a href="{{ route('kegiatan-create') }}" class="btn btn-primary">
                                                <i class="fa-solid fa-plus"></i> Tambah Kegiatan
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-kegiatan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Kegiatan</th>
                                                <th class="text-center">Periode Pelaksanaan</th>
                                                <th class="text-center">Lokasi</th>
                                                <th class="text-center">Jenis Kegiatan</th>
                                                <th class="text-center">Informasi</th>
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

    {{-- Modal Buka Tutup Pendaftaran --}}
    <div class="modal fade" id="modal_status_pendaftaran" tabindex="-1" aria-labelledby="modalStatusPendaftaranLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formStatusPendaftaran">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalStatusPendaftaranLabel">Buka/Tutup Pendaftaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id_kegiatan" name="id_kegiatan">
                                <label for="nama_dokumen">Status Pendaftaran</label>
                                <select name="status_pendaftaran" id="status_pendaftaran" class="form-select">
                                    <option value="0">Pendaftaran Ditutup</option>
                                    <option value="1">Pendaftaran Dibuka</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3" id="qr_wrapper" style="display:none;">
                                <label for="link_pendaftaran">URL Pendaftaran</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="link_pendaftaran" id="link_pendaftaran" class="form-control"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary js-copy-kode-kegiatan"
                                        id="btn_copy_link_pendaftaran" data-copy-text="">Salin</button>
                                </div>
                                <label class="mb-2">QR Pendaftaran</label>
                                <div class="text-center">
                                    <img id="qr_pendaftaran_preview" src="" alt="QR Pendaftaran"
                                        style="max-width:260px; width:100%;">
                                </div>
                                <div class="mt-2 text-center">
                                    <a id="btn_download_qr" href="#" target="_blank" download="qr-pendaftaran.png"
                                        class="btn btn-outline-primary btn-sm">Download QR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeStatusPendaftaran()">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Buka Tutup Pendaftaran --}}
    <div class="modal fade" id="modal_setting_evaluasi" tabindex="-1" aria-labelledby="modalSetEvaluasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formSettingEvaluasi">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalSetEvaluasiLabel">Setting Evaluasi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_kegiatan_evaluasi" name="id_kegiatan_evaluasi">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="nama_dokumen">Pilih Template Evaluasi Penyelenggaraaan</label>
                                <select name="id_eval_penyelenggaraan" id="id_eval_penyelenggaraan" class="form-select">
                                    <option value="">-- Belum Memilih Template --</option>
                                    @foreach ($ref_eval_penyelenggaraan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_template }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nama_dokumen">Pilih Template Evaluasi Narasumber</label>
                                <select name="id_eval_narasumber" id="id_eval_narasumber" class="form-select">
                                    <option value="">-- Belum Memilih Template --</option>
                                    @foreach ($ref_eval_narasumber as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_template }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeSettingEvaluasi()">Simpan</button>
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

        function toEdit(id) {
            window.location.href = "{{ route('kegiatan-create') }}?id=" + id;
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
                        url: "{{ route('kegiatan-delete') }}",
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

        function toDetail(id) {
            window.location.href = "{{ route('kegiatan-create') }}?id=" + id;
        }

        function toVerifikasi(id) {
            iziToast.info({
                title: "Info",
                message: "Aksi verifikasi untuk ID " + id + " belum diimplementasikan."
            });
        }

        function toMulaiKegiatan(id) {
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah anda yakin untuk memulai kegiatan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('kegiatan-start-kegiatan') }}",
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

        function kirimKepegawaian(id) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin kirim kegiatan ini ke kepegawaian?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('kegiatan-kirim-kepegawaian') }}",
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Berhasil!',
                                    message: response.msg
                                });
                                loadTable();
                            } else {
                                iziToast.error({
                                    title: 'Gagal!',
                                    message: response.msg
                                });
                            }
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Gagal!',
                                message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        }

        $(document).on('shown.bs.dropdown', '#table-kegiatan .btn-group', function() {
            var $group = $(this);
            var $menu = $group.find('.dropdown-menu');

            if (!$menu.length) {
                return;
            }

            var rect = $group[0].getBoundingClientRect();
            var menuWidth = $menu.outerWidth();
            var menuHeight = $menu.outerHeight();
            var viewportWidth = window.innerWidth;
            var viewportHeight = window.innerHeight;
            var top = rect.bottom;
            var left = rect.left;

            if (top + menuHeight > viewportHeight - 8) {
                top = rect.top - menuHeight;
            }

            if (left + menuWidth > viewportWidth - 8) {
                left = viewportWidth - menuWidth - 8;
            }

            if (left < 8) {
                left = 8;
            }

            if (top < 8) {
                top = 8;
            }

            $group.data('dropdown-parent', $group);
            $group.data('dropdown-menu', $menu);

            $menu.addClass('dropdown-menu-floating')
                .css({
                    top: top + 'px',
                    left: left + 'px'
                })
                .appendTo('body');
        });

        $(document).on('hidden.bs.dropdown', '#table-kegiatan .btn-group', function() {
            var $group = $(this);
            var $menu = $group.data('dropdown-menu');
            var $parent = $group.data('dropdown-parent');

            if ($menu && $menu.length && $parent && $parent.length) {
                $menu.removeClass('dropdown-menu-floating')
                    .removeAttr('style')
                    .appendTo($parent);
            }

            $group.removeData('dropdown-menu');
            $group.removeData('dropdown-parent');
        });

        function modalStatusPendaftaran(id) {
            $('#modal_status_pendaftaran').modal('show');
            $('#id_kegiatan').val(id);
            loadStatusPendaftaran(id);
        }

        function generateQrUrl(enrollmentUrl) {
            if (!enrollmentUrl) {
                return '';
            }

            return 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' + encodeURIComponent(enrollmentUrl);
        }

        function toggleQrStatus(status, qrUrl, enrollmentUrl) {
            if (String(status) === '1' && qrUrl) {
                $('#qr_pendaftaran_preview').attr('src', qrUrl);
                $('#link_pendaftaran').val(enrollmentUrl || '');
                $('#btn_copy_link_pendaftaran').attr('data-copy-text', enrollmentUrl || '');
                $('#btn_download_qr').attr('href', qrUrl);
                $('#qr_wrapper').show();
                return;
            }

            $('#qr_pendaftaran_preview').attr('src', '');
            $('#link_pendaftaran').val('');
            $('#btn_copy_link_pendaftaran').attr('data-copy-text', '');
            $('#btn_download_qr').attr('href', '#');
            $('#qr_wrapper').hide();
        }

        function loadStatusPendaftaran(id) {
            $.ajax({
                url: "{{ route('kegiatan-get-status-pendaftaran') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.status !== true) {
                        return;
                    }

                    $('#status_pendaftaran').val(String(response.data.status_pendaftaran));
                    $('#status_pendaftaran').data('enrollment-url', response.data.url_enrollment);
                    toggleQrStatus(
                        response.data.status_pendaftaran,
                        response.data.qr_pendaftaran,
                        response.data.url_enrollment
                    );
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        $('#status_pendaftaran').on('change', function() {
            var status = $(this).val();
            var enrollmentUrl = $('#status_pendaftaran').data('enrollment-url');
            var currentQr = $('#qr_pendaftaran_preview').attr('src');

            if (String(status) === '1') {
                var qrUrl = currentQr || generateQrUrl(enrollmentUrl);
                toggleQrStatus('1', qrUrl, enrollmentUrl);
                return;
            }

            toggleQrStatus('0', '', enrollmentUrl);
        });

        function storeStatusPendaftaran() {
            $.ajax({
                url: "{{ route('kegiatan-store-status-pendaftaran') }}",
                type: 'POST',
                dataType: 'json',
                data: $('#formStatusPendaftaran').serialize(),
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.msg
                        });

                        toggleQrStatus(
                            response.data.status_pendaftaran,
                            response.data.qr_pendaftaran,
                            response.data.url_enrollment
                        );
                        loadTable();
                        $('#modal_status_pendaftaran').modal('hide');
                        return;
                    }

                    iziToast.error({
                        title: 'Gagal!',
                        message: response.msg
                    });
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function modalSetEvaluasi(id) {
            $('#formSettingEvaluasi')[0].reset();
            $('#id_eval_penyelenggaraan').val('');
            $('#id_eval_narasumber').val('');
            $('#id_kegiatan_evaluasi').val(id);
            $('#modal_setting_evaluasi').modal('show');
            loadSettingEvaluasi(id);
        }

        function loadSettingEvaluasi(id) {
            $.ajax({
                url: "{{ route('kegiatan-detail-evaluasi') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.status !== true) {
                        return;
                    }

                    $('#id_eval_penyelenggaraan').val(response.data.id_eval_penyelenggaraan || '');
                    $('#id_eval_narasumber').val(response.data.id_eval_narasumber || '');
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function storeSettingEvaluasi() {
            $.ajax({
                url: "{{ route('kegiatan-store-evaluasi') }}",
                type: 'POST',
                dataType: 'json',
                data: $('#formSettingEvaluasi').serialize(),
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.msg
                        });
                        $('#modal_setting_evaluasi').modal('hide');
                        loadTable();
                        return;
                    }

                    iziToast.error({
                        title: 'Gagal!',
                        message: response.msg
                    });
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-kegiatan')) {
                $('#table-kegiatan').DataTable().destroy();
            }
            $('#table-kegiatan').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-get-data') }}",
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
                        data: 'periode_pelaksanaan',
                        name: 'periode_pelaksanaan',
                        className: 'va-middle'
                    },
                    {
                        data: 'lokasi_kegiatan',
                        name: 'lokasi_kegiatan',
                        className: 'va-middle'
                    },
                    {
                        data: 'jenis_kegiatan',
                        name: 'jenis_kegiatan',
                        className: 'va-middle'
                    },
                    {
                        data: 'informasi',
                        name: 'informasi',
                        className: 'va-middle'
                    },
                    {
                        data: 'status',
                        name: 'status',
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
