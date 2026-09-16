@extends('layouts.app')
@section('contents')
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('kegiatan-peran-pegawai', ['id_kegiatan' => $id_kegiatan, 'id_peran' => $id_peran]) }}"
                class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Pegawai Terplotting</a>
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
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <div class="alert alert-secondary alert-border-left fade show" role="alert">
                                            <div><strong>{{ $kegiatan->nama_kegiatan ?? '-' }}</strong></div>
                                            <div>{{ dateRangeIndo($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai) }}
                                            </div>
                                            <div>{{ $kegiatan->lokasi_kegiatan ?? '-' }}</div>
                                            <hr>
                                            <div><strong>Peran:</strong> {{ $peran->nama_peran ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                        id="table-pilih-pegawai">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="40">
                                                    <input type="checkbox" id="check-all">
                                                </th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Jabatan</th>
                                                <th>No HP</th>
                                                <th>Instansi Pendidikan</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-primary" onclick="submitPlot()" id="btn-plotting">
                                    <i class="fa-solid fa-floppy-disk"></i> Plotting 0 orang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-kegiatan-irisan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Kegiatan Beririsan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody id="list-kegiatan-irisan"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            loadTable();

            $(document).on('change', '#check-all', function() {
                $('.pegawai-checkbox:not(:disabled)').prop('checked', this.checked);
                updatePlottingLabel();
            });

            $(document).on('change', '.pegawai-checkbox', function() {
                updatePlottingLabel();
            });

            $(document).on('click', '.btn-kegiatan-irisan', function() {
                var details = $(this).attr('data-details');
                var rows = '';

                try {
                    var parsed = JSON.parse(details);
                    parsed.forEach(function(item) {
                        rows += '<tr>' +
                            '<td>' + (item.nama_kegiatan ?? '-') + '</td>' +
                            '<td>' + formatTanggal(item.tanggal_mulai, item.tanggal_selesai) +
                            '</td>' +
                            '<td>' + (item.lokasi_kegiatan ?? '-') + '</td>' +
                            '</tr>';
                    });
                } catch (e) {
                    rows = '<tr><td colspan="3" class="text-center">Data tidak tersedia</td></tr>';
                }

                if (rows === '') {
                    rows = '<tr><td colspan="3" class="text-center">Data tidak tersedia</td></tr>';
                }

                $('#list-kegiatan-irisan').html(rows);
                $('#modal-kegiatan-irisan').modal('show');
            });
        });

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-pilih-pegawai')) {
                $('#table-pilih-pegawai').DataTable().destroy();
            }

            $('#table-pilih-pegawai').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-peran-pegawai-pilih-get-data', ['id_kegiatan' => $id_kegiatan, 'id_peran' => $id_peran]) }}",
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },

                    {
                        data: 'nip',
                        name: 'nip'
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp'
                    },
                    {
                        data: 'instansi_pendidikan',
                        name: 'instansi_pendidikan'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: false,
                        orderable: false
                    }
                ]
            });
        }

        function updatePlottingLabel() {
            var total = $('.pegawai-checkbox:checked').length;
            $('#btn-plotting').html('<i class="fa-solid fa-floppy-disk"></i> Plotting ' + total + ' orang');
        }

        function formatTanggal(start, end) {
            if (!start || !end) {
                return '-';
            }

            var startDate = new Date(start);
            var endDate = new Date(end);
            return startDate.toLocaleDateString('id-ID') + ' - ' + endDate.toLocaleDateString('id-ID');
        }

        function submitPlot() {
            var kuota = parseInt("{{ (int) ($peran->kuota ?? 0) }}", 10);
            var jumlahTerplot = parseInt("{{ (int) ($jumlah_terplot ?? 0) }}", 10);
            var sisaKapasitas = Math.max(0, kuota - jumlahTerplot);
            var selected = [];
            $('.pegawai-checkbox:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                iziToast.warning({
                    title: 'Info',
                    message: 'Pilih minimal 1 pegawai'
                });
                return;
            }

            if (selected.length > sisaKapasitas) {
                iziToast.warning({
                    title: 'Info',
                    message: 'Jumlah pegawai melebihi sisa kapasitas (' + sisaKapasitas + ' orang)'
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('kegiatan-peran-pegawai-store') }}",
                data: {
                    id_kegiatan: "{{ $id_kegiatan }}",
                    id_peran: "{{ $id_peran }}",
                    id_pegawai: selected
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.msg
                        });

                        setTimeout(function() {
                            window.location.href =
                                "{{ route('kegiatan-peran-pegawai', ['id_kegiatan' => $id_kegiatan, 'id_peran' => $id_peran]) }}";
                        }, 500);
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
    </script>
@endpush
