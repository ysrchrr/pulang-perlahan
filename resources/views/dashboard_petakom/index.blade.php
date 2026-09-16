@extends('layouts.app')
@push('styles')
@endpush
@section('contents')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">{{ getSalamWaktu() }}, {{ session('user_name') }}!</h4>
                                {{-- <p class="text-muted mb-0">Selamat datang di SIM Kegiatan & Penugasan BGTK Jambi</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if ($profile_belum_lengkap)
                        <div class="col-xl-4 col-sm-6">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h5 class="font-size-15 mb-2">Profil belum lengkap</h5>
                                    <p class="text-muted mb-3">Harap lengkapi jenjang dan mapel di halaman profil terlebih
                                        dahulu</p>
                                    <a href="{{ route('profile-petakom') }}" class="btn btn-warning btn-sm">Buka Profil</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach ($instrumen as $item)
                        @php
                            $status = (string) ($item->enrollment_status ?? '0');
                            $totalSoal = (int) ($item->total_soal_likert ?? 0);
                            $totalJawaban = (int) ($item->total_jawaban_likert ?? 0);
                            $progress = $totalSoal > 0 ? min(100, round(($totalJawaban / $totalSoal) * 100)) : 0;
                        @endphp
                        <div class="col-xl-6 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-4">
                                            <div class="avatar-md">
                                                <span
                                                    class="avatar-title rounded-circle bg-light text-danger font-size-16 mt-3">
                                                    <img src="{{ asset('assets/images/tut-wuri-handayani.png') }}"
                                                        alt="" height="50">
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="text-truncate font-size-15"><a href="javascript: void(0);"
                                                    class="text-dark">{{ $item->nama_instrumen }}</a></h5>
                                            <p class="text-muted">{{ $item->ref_mapel->nama_mapel }} -
                                                {{ $item->ref_jenjang->nama_jenjang }}</p>
                                            <form action="{{ route('petakom-instrumen-list-enrollment') }}" method="POST"
                                                id="form-enrollment-{{ $item->id }}">
                                                @csrf
                                                <input type="hidden" name="id_instrumen" value="{{ enc_id($item->id) }}">
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="confirmStart(`form-enrollment-{{ $item->id }}`)">Isi
                                                    Instrumen <i class="fas fa-arrow-right"></i> </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 border-top">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if ($status === '1')
                                                <span class="badge bg-danger">Proses Pengisian</span>
                                            @elseif ($status === '2')
                                                <span class="badge bg-success">Selesai Mengisi</span>
                                            @else
                                                <span class="badge bg-light text-body">Belum Mengisi</span>
                                            @endif
                                        </div>

                                        @if ($status === '1')
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between fs-12 mb-1">
                                                    <span class="text-muted">Progress</span>
                                                    <span>{{ $totalJawaban }}/{{ $totalSoal }} Soal</span>
                                                </div>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: {{ $progress }}%"
                                                        aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function confirmStart(formId) {
            Swal.fire({
                title: 'Mulai isi instrumen?',
                // text: 'Enrollment akan dibuat sebelum masuk ke instrumen.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $('#' + formId).submit();
                }
            });
        }
    </script>
@endpush
