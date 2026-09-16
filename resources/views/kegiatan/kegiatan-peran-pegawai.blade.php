@extends('layouts.app')
@section('contents')
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('kegiatan-peran', $id_kegiatan) }}" class="btn btn-danger btn-sm"> <i
                    class="fas fa-arrow-left"></i> Peran Dalam Kegiatan</a>
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
                                        <a href="{{ route('kegiatan-peran-pegawai-pilih', ['id_kegiatan' => $id_kegiatan, 'id_peran' => $id_peran]) }}"
                                            class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Plot Pegawai
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
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
                                        id="table-pegawai">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Pegawai</th>
                                                <th class="text-center">Kontak</th>
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            loadTable();
        });

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
                        url: "{{ route('kegiatan-peran-pegawai-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-pegawai')) {
                $('#table-pegawai').DataTable().destroy();
            }

            $('#table-pegawai').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('kegiatan-peran-pegawai-get-data', ['id_kegiatan' => $id_kegiatan, 'id_peran' => $id_peran]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'pegawai',
                        name: 'pegawai',
                        className: 'va-middle'
                    },
                    {
                        data: 'kontak',
                        name: 'kontak',
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
