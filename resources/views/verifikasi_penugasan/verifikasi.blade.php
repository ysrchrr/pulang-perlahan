@extends('layouts.app')
@push('styles')
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('verifikasi-penugasan') }}" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i>
                Kembali</a>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Jabatan</th>
                                                        <th class="text-center">Nama</th>
                                                        <th class="text-center">Kontak</th>
                                                        {{-- <th class="text-center">Informasi</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($list_pegawai_terpilih as $item)
                                                        <tr>
                                                            <td class="va-middle text-center">{{ $loop->iteration }}</td>
                                                            <td class="va-middle">
                                                                {{ $item->jabatan ? $item->jabatan->nama_jabatan : '-' }}
                                                            </td>
                                                            <td class="va-middle">
                                                                {{ $item->pegawai ? $item->pegawai->nama : '-' }}</td>
                                                            <td class="va-middle">
                                                                <div><small class="text-muted">Email:</small>
                                                                    {{ $item->pegawai ? $item->pegawai->email ?? '-' : '-' }}
                                                                </div>
                                                                <div><small class="text-muted">No HP:</small>
                                                                    {{ $item->pegawai ? $item->pegawai->no_hp ?? '-' : '-' }}
                                                                </div>
                                                            </td>
                                                            {{-- <td class="va-middle text-center fw-semibold">
                                                                @if ($item->pegawai->users_id)
                                                                    <span class="text-success">Sudah memiliki akun</span>
                                                                @else
                                                                    <span class="text-danger">Belum memiliki akun</span>
                                                                @endif
                                                            </td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Keputusan Verifikasi Penugasan</label>
                                        <select name="hasil_keputusan" id="hasil_keputusan" class="form-select">
                                            <option value="">Pilih Keputusan</option>
                                            <option value="2">Disetujui</option>
                                            <option value="3">Revisi</option>
                                            <option value="6">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Masukkan catatan..."></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary mt-3"
                                            onclick="storeVerifikasi()">Simpan
                                            Keputusan</button>
                                    </div>
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
            // loadTable();
        });

        function storeVerifikasi() {
            const hasilKeputusan = $('#hasil_keputusan').val();

            if (!hasilKeputusan) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Pilih keputusan verifikasi penugasan'
                });
                return;
            }

            $.ajax({
                url: "{{ route('verifikasi-penugasan-store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id_kegiatan: "{{ $id_kegiatan }}",
                    hasil_keputusan: hasilKeputusan,
                    catatan: $('#catatan').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = "{{ route('verifikasi-penugasan') }}";
                        });
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
                        message: xhr.responseJSON?.message ?? xhr.responseJSON?.msg ??
                            'Terjadi kesalahan'
                    });
                }
            });
        }
    </script>
@endpush
