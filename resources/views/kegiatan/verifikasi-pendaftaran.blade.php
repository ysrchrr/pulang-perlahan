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
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-secondary alert-border-left fade show" role="alert">
                                            <div><strong>{{ $kegiatan->nama_kegiatan ?? '-' }}</strong> </div>
                                            <div>{{ dateRangeIndo($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai) }}
                                            </div>
                                            <div>{{ $kegiatan->lokasi_kegiatan ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card border card-border-primary">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                            <i class="fa-solid fa-users-rectangle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Kuota
                                                            Peserta</p>
                                                        <h4 class=" mb-0"><span class="counter-value"
                                                                data-target="{{ $kegiatan->kuota_peserta }}">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border card-border-danger">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-light text-danger rounded-circle fs-3">
                                                            <i class="fa-solid fa-user-clock"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Calon
                                                            Peserta</p>
                                                        <h4 class=" mb-0"><span class="counter-value"
                                                                data-target="{{ $calon_peserta_count }}">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border card-border-success">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-success rounded-circle fs-3">
                                                            <i class="fa-solid fa-user-check"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Peserta
                                                        </p>
                                                        <h4 class=" mb-0"><span class="counter-value"
                                                                data-target="{{ $peserta_count }}">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-pendaftar">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Instansi</th>
                                                <th class="text-center">Kontak</th>
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

    {{-- Modal Dokumen --}}
    <div class="modal fade" id="modal_dokumen" tabindex="-1" aria-labelledby="modalDokumenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formStatusPendaftaran">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDokumenLabel">Berkas Pendaftaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Dokumen</th>
                                                <th>File</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Verifikasi --}}
    <div class="modal fade" id="modal_verifikasi" tabindex="-1" aria-labelledby="modalVerifikasiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formVerifikasiPendaftaran">
                @csrf
                <input type="hidden" name="id_peserta_terpilih" id="id_peserta_terpilih">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalVerifikasiLabel">Verifikasi Pendaftaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Pilih Keputusan</label>
                                <select class="form-select" name="status_pendaftaran" id="status_pendaftaran" required>
                                    <option value="">-- Pilih Keputusan --</option>
                                    <option value="1">Diterima</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="wrapper_kelas_id" style="display: none;">
                            <div class="col-md-12">
                                <label>Pilih Kelas (Opsional)</label>
                                <select class="form-select" name="kelas_id" id="kelas_id">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas_list as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-primary" id="btnVerifikasi">Verifikasi</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

            $('#status_pendaftaran').on('change', function() {
                toggleKelas($(this).val());
            });

            $('#formVerifikasiPendaftaran').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('kegiatan-verifikasi-pendaftaran-store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#btnVerifikasi').prop('disabled', true).text('Memproses...');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.msg
                        });

                        $('#modal_verifikasi').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                        });
                    },
                    complete: function() {
                        $('#btnVerifikasi').prop('disabled', false).text('Verifikasi');
                    }
                });
            });
        });

        function toggleKelas(status) {
            if (status === '1') {
                $('#wrapper_kelas_id').show();
                return;
            }

            $('#wrapper_kelas_id').hide();
            $('#kelas_id').prop('required', false).val('');
        }

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function getBerkas(id) {
            var tbody = $('#modal_dokumen tbody');

            tbody.html('<tr><td colspan="2" class="text-center">Memuat berkas...</td></tr>');
            $('#modal_dokumen').modal('show');

            $.ajax({
                url: "{{ route('kegiatan-verifikasi-pendaftaran-get-berkas', ':id') }}".replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var rows = '';

                    if (response.status === true && response.data.length > 0) {
                        $.each(response.data, function(index, item) {
                            var file = '<span class="badge bg-danger">Belum Upload</span>';

                            if (item.url) {
                                file = '<a href="' + item.url +
                                    '" target="_blank" class="btn btn-sm btn-primary">Lihat File</a>';
                            }

                            rows += '<tr>' +
                                '<td>' + escapeHtml(item.nama_dokumen) + '</td>' +
                                '<td>' + file + '</td>' +
                                '</tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="2" class="text-center">Tidak ada dokumen</td></tr>';
                    }

                    tbody.html(rows);
                },
                error: function(xhr) {
                    tbody.html('<tr><td colspan="2" class="text-center text-danger">' +
                        (xhr.responseJSON?.msg ?? 'Gagal memuat berkas') + '</td></tr>');
                }
            });
        }

        function verifikasiPendaftaran(id) {
            $('#formVerifikasiPendaftaran')[0].reset();
            $('#id_peserta_terpilih').val(id);
            toggleKelas('');
            $('#modal_verifikasi').modal('show');
        }

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-pendaftar')) {
                $('#table-pendaftar').DataTable().destroy();
            }
            $('#table-pendaftar').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-verifikasi-pendaftaran-get-data', ['kode_kegiatan' => $id_kegiatan]) }}",
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
                        data: 'unit_kerja',
                        name: 'unit_kerja',
                        className: 'va-middle'
                    },
                    {
                        data: 'kontak',
                        name: 'kontak',
                        className: 'va-middle'
                    },
                    {
                        data: 'status',
                        name: 'status',
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
