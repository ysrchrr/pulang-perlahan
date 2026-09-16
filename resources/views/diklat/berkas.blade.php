@extends('layouts.app')
@push('styles')
    <style>
    </style>
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('diklat') }}" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Dokumen</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ref_dokumen as $item)
                                            <tr>
                                                <td class="va-middle">{{ $item->nama_dokumen }}</td>
                                                <td>
                                                    @php
                                                        $dokumen = $unggahan_dokumen[$item->id] ?? null;
                                                    @endphp
                                                    <div class="mb-2">
                                                        @if ($dokumen && !empty($dokumen['path_dokumen']))
                                                            <a href="{{ asset('storage/' . $dokumen['path_dokumen']) }}"
                                                                target="_blank" id="file_link_{{ $item->id }}"
                                                                class="btn btn-sm btn-primary">Lihat File</a>
                                                        @else
                                                            <a href="javascript:void(0)" target="_blank"
                                                                id="file_link_{{ $item->id }}"
                                                                class="btn btn-sm btn-secondary disabled d-none">Lihat
                                                                File</a>
                                                        @endif
                                                    </div>
                                                    <input type="file" name="file_upload_{{ $item->id }}"
                                                        id="file_upload_{{ $item->id }}" class="text-control"
                                                        data-id-ref-dokumen="{{ $item->id }}"
                                                        data-upload-url="{{ route('diklat-berkas-upload', $id_kegiatan_enc) }}">
                                                    <small class="text-muted d-block mt-1"
                                                        id="status_{{ $item->id }}"></small>
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('change', 'input[type="file"][data-upload-url]', function() {
                var input = this;
                var file = input.files[0];
                var idRefDokumen = $(input).data('id-ref-dokumen');
                var uploadUrl = $(input).data('upload-url');
                var statusEl = $('#status_' + idRefDokumen);
                var linkEl = $('#file_link_' + idRefDokumen);

                if (!file) {
                    return;
                }

                var formData = new FormData();
                formData.append('file_upload', file);
                formData.append('id_ref_dokumen', idRefDokumen);
                formData.append('_token', '{{ csrf_token() }}');

                statusEl.text('Uploading...');

                $.ajax({
                    url: uploadUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            statusEl.text(response.msg);
                            linkEl.removeClass('d-none disabled btn-secondary').addClass(
                                'btn-primary').attr('href', response.data.url).text(
                                'Lihat File');
                            input.value = '';
                        } else {
                            statusEl.text(response.msg || 'Upload gagal');
                        }
                    },
                    error: function(xhr) {
                        var message = 'Upload gagal';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                            if (firstKey && xhr.responseJSON.errors[firstKey] && xhr
                                .responseJSON.errors[firstKey][0]) {
                                message = xhr.responseJSON.errors[firstKey][0];
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                            message = xhr.responseJSON.msg;
                        }

                        statusEl.text(message);
                    }
                });
            });
        });
    </script>
@endpush
