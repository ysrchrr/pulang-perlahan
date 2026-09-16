@extends('layouts.app')
@push('styles')
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('kegiatan') }}" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }} - {{ $kegiatan->nama_kegiatan }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table-list-sertifikat">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Peran</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-center">Sertifikat</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($list_peran as $item)
                                                <tr>
                                                    <td>{{ $item['peran'] }}</td>
                                                    <td class="text-center">{{ $item['jumlah'] }}</td>
                                                    <td class="text-center">
                                                        @if (is_array($item['sertifikat']) && $item['sertifikat']['type'] === 'link')
                                                            <a href="{{ $item['sertifikat']['url'] }}" target="_blank">
                                                                {{ $item['sertifikat']['label'] }}
                                                            </a>
                                                        @elseif (is_array($item['sertifikat']) && $item['sertifikat']['type'] === 'text')
                                                            <span>{{ $item['sertifikat']['label'] }}</span>
                                                        @else
                                                            {{ $item['sertifikat'] }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-peran="{{ $item['key'] }}"
                                                            data-peran-label="{{ $item['peran'] }}"
                                                            {{ !$item['can_generate'] || $item['is_processing'] ? 'disabled' : '' }}>
                                                            Generate Sertifikat
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
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
@endsection
@push('scripts')
    <script>
        $('#table-list-sertifikat').on('click', 'button[data-peran]', function() {
            const button = $(this);
            const peran = button.data('peran');
            const peranLabel = button.data('peran-label');
            const oldHtml = button.html();

            button.prop('disabled', true).html('Processing...');

            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-sertifikat-generate') }}",
                data: {
                    id_kegiatan: "{{ $id_kegiatan }}",
                    peran: peran,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: peranLabel + ' masuk queue generate'
                        });

                        setTimeout(function() {
                            window.location.reload();
                        }, 1200);
                    } else {
                        button.prop('disabled', false).html(oldHtml);

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

                    button.prop('disabled', false).html(oldHtml);

                    iziToast.error({
                        title: "Gagal!",
                        message: message
                    });
                }
            });
        });
    </script>
@endpush
