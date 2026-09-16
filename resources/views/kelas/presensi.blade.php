@extends('layouts.app')

@push('styles')
    <style>
        .presensi-table th,
        .presensi-table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .presensi-table thead th {
            text-align: center;
        }

        .presensi-day {
            min-width: 90px;
        }

        .presensi-day small {
            display: block;
            font-size: 11px;
            line-height: 1.2;
            opacity: 0.75;
            margin-top: 2px;
        }

        .presensi-check {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
    </style>
@endpush

@section('contents')
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('kegiatan-kelas-list', ['id_kegiatan' => $id_kegiatan]) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-1">{{ $page_title }}</h4>
                        <div class="text-muted">
                            {{ $kelas->nama_kelas }} -
                            {{ dateRangeIndo($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai) }}
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-success" id="btnExportAbsensi" onclick="exportExcelAbsensi()">
                            <i class="fas fa-download"></i> Unduh Rekap Abensi
                        </button>
                        <button type="button" class="btn btn-primary" id="btnGenerateAbsensi" onclick="generateAbsensi()">
                            <i class="fas fa-check"></i> Generate Absensi
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        Hilangkan centang jika peserta tidak hadir.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle presensi-table" id="table-presensi">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    @foreach ($tanggal_range as $index => $tanggal)
                                        <th class="presensi-day text-center">
                                            Hari {{ $index + 1 }}
                                            <small>{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peserta as $index => $row)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $row->nama_lengkap ?? '-' }}</td>
                                        @foreach ($tanggal_range as $tanggal)
                                            @php
                                                $absensi = $absensi_map[$row->id][$tanggal] ?? null;
                                                $checked = $absensi ? $absensi->presensi == '1' : false;
                                            @endphp
                                            <td class="text-center">
                                                <input type="checkbox" class="presensi-check js-presensi-toggle"
                                                    data-id-kelas="{{ $id_kelas }}"
                                                    data-id-peserta-terpilih="{{ enc_id($row->id) }}"
                                                    data-tanggal="{{ $tanggal }}" {{ $checked ? 'checked' : '' }}>
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + count($tanggal_range) }}" class="text-center text-muted">
                                            Belum ada peserta di kelas ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
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
            if ($.fn.DataTable.isDataTable('#table-presensi')) {
                $('#table-presensi').DataTable().destroy();
            }

            $('#table-presensi').DataTable({
                ordering: false,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: true,
                scrollX: true,
                autoWidth: false
            });

            $(document).on('change', '.js-presensi-toggle', function() {
                updatePresensi($(this));
            });
        });

        function generateAbsensi() {
            $.ajax({
                type: 'POST',
                url: "{{ route('kegiatan-kelas-presensi-generate', ['id_kelas' => $id_kelas]) }}",
                dataType: 'json',
                beforeSend: function() {
                    $('#btnGenerateAbsensi').prop('disabled', true).text('Generating...');
                },
                success: function(response) {
                    if (response.status) {
                        iziToast.success({
                            message: response.msg
                        });

                        window.location.reload();
                        return;
                    }

                    iziToast.error({
                        message: response.msg || 'Gagal generate absensi'
                    });
                },
                error: function(xhr) {
                    iziToast.error({
                        message: xhr.responseJSON?.msg || 'Gagal generate absensi'
                    });
                },
                complete: function() {
                    $('#btnGenerateAbsensi').prop('disabled', false).text('Generate Absensi');
                }
            });
        }

        function exportExcelAbsensi() {
            window.location.href = "{{ route('kegiatan-kelas-presensi-export', ['id_kelas' => $id_kelas]) }}";
        }

        function updatePresensi($checkbox) {
            var checked = $checkbox.is(':checked') ? '1' : '0';

            $.ajax({
                type: 'POST',
                url: "{{ route('kegiatan-kelas-presensi-update') }}",
                dataType: 'json',
                data: {
                    id_kelas: $checkbox.data('id-kelas'),
                    id_peserta_terpilih: $checkbox.data('id-peserta-terpilih'),
                    tanggal: $checkbox.data('tanggal'),
                    presensi: checked
                },
                error: function(xhr) {
                    $checkbox.prop('checked', checked === '0');

                    iziToast.error({
                        message: xhr.responseJSON?.msg || 'Gagal simpan presensi'
                    });
                }
            });
        }
    </script>
@endpush
