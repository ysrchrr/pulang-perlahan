@extends('layouts.app')
@push('styles')
    <style>
        #foto-dropzone {
            min-height: 190px;
            border: 1px dashed #adb5bd;
            border-radius: 0;
            background: #fbfbfb;
            padding: 12px;
        }

        html[data-theme="dark"] #foto-dropzone,
        html[data-theme="dark"] #foto-dropzone.dropzone,
        html[data-theme="dark"] .dropzone#foto-dropzone {
            border-color: #495057 !important;
            background: #1f2328 !important;
        }

        #foto-dropzone.dropzone .dz-message {
            margin: 2.5rem 0;
            text-align: center;
        }

        #foto-dropzone.dropzone .dz-message .dz-button {
            border: 0;
            background: transparent;
            color: #212529;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 0;
        }

        html[data-theme="dark"] #foto-dropzone.dropzone .dz-message,
        html[data-theme="dark"] #foto-dropzone.dropzone .dz-message .dz-button {
            color: #e9ecef !important;
        }

        #foto-dropzone.dropzone .dz-preview {
            margin: 10px;
        }

        #foto-dropzone.dropzone .dz-preview .dz-image {
            border-radius: 0;
            width: 110px;
            height: 110px;
        }

        #foto-dropzone.dropzone .dz-preview .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        html[data-theme="dark"] #foto-dropzone.dropzone .dz-preview .dz-image {
            background: #111418 !important;
        }

        .foto-item {
            position: relative;
            border: 1px solid #dee2e6;
            background: #fff;
        }

        html[data-theme="dark"] .foto-item {
            border-color: #495057 !important;
            background: #1b1f24 !important;
        }

        .foto-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .foto-item .foto-action {
            position: absolute;
            top: 8px;
            right: 8px;
        }

        html[data-theme="dark"] #form-foto-kegiatan .text-muted {
            color: #adb5bd !important;
        }

        html[data-theme="dark"] #foto-preview .foto-item img {
            background: #111418;
        }

        .foto-item .foto-action .btn {
            border-radius: 0;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush
@section('contents')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <a href="{{ route('kegiatan') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="fw-bold">{{ $kegiatan->nama_kegiatan ?? '-' }}</div>
                                </div>
                                <form id="form-foto-kegiatan" enctype="multipart/form-data">
                                    <input type="hidden" id="id_kegiatan" name="id_kegiatan" value="{{ $id_kegiatan }}">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div id="foto-dropzone" class="dropzone">
                                                <div class="dz-message needsclick">
                                                    <div class="mb-2">
                                                        <i class="display-6 text-muted ri-upload-cloud-2-line"></i>
                                                    </div>
                                                    <h5 class="mb-1">Drop files here or click to upload</h5>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-2">Upload JPG/PNG. Foto lama tetap
                                                tersimpan, yang dihapus hanya yang kamu klik delete.</small>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div id="foto-preview" class="row g-2"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-save"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
        Dropzone.autoDiscover = false;

        let fotoDropzone = null;
        $(document).ready(function() {
            initDropzone();
            renderExistingFotos(@json($list_foto));
        });

        function initDropzone() {
            fotoDropzone = new Dropzone('#foto-dropzone', {
                url: "{{ route('kegiatan-foto-store') }}",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFilesize: 2,
                acceptedFiles: 'image/jpeg,image/png,image/jpg',
                addRemoveLinks: true,
                dictDefaultMessage: 'Drop files here or click to upload.',
                dictRemoveFile: 'Delete',
                previewsContainer: '#foto-dropzone',
                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image">
                            <img data-dz-thumbnail />
                        </div>
                        <div class="dz-details">
                            <div class="dz-size"><span data-dz-size></span></div>
                            <div class="dz-filename"><span data-dz-name></span></div>
                        </div>
                    </div>
                `
            });
        }

        function renderExistingFotos(items) {
            $('#foto-preview').empty();

            if (!Array.isArray(items) || items.length === 0) {
                return;
            }

            items.forEach(function(item) {
                if (!item || !item.img_path) {
                    return;
                }

                const imageUrl = "{{ asset('storage') }}/" + item.img_path;
                const fotoId = item.id;
                const html = `
                    <div class="col-md-3" id="foto-item-${fotoId}">
                        <div class="foto-item">
                            <div class="foto-action">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeExistingFoto('${fotoId}')">
                                    Hapus
                                </button>
                            </div>
                            <a href="${imageUrl}" target="_blank" class="d-block">
                                <img src="${imageUrl}" alt="Foto kegiatan">
                            </a>
                        </div>
                    </div>
                `;
                $('#foto-preview').append(html);
            });
        }

        function removeExistingFoto(id) {
            if (!id) {
                return;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-foto-delete') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#foto-item-' + id).remove();
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message ?? xhr.responseJSON?.msg ?? 'Terjadi kesalahan';
                    iziToast.error({
                        title: "Gagal!",
                        message: errorMsg
                    });
                }
            });
        }

        $('#form-foto-kegiatan').on('submit', function(e) {
            e.preventDefault();

            const existingCount = $('#foto-preview .foto-item').length;
            const newCount = fotoDropzone ? fotoDropzone.getAcceptedFiles().length : 0;

            if (existingCount === 0 && newCount === 0) {
                iziToast.warning({
                    title: "Perhatian",
                    message: "Minimal upload 1 foto."
                });
                return;
            }

            const formData = new FormData(this);

            if (fotoDropzone) {
                fotoDropzone.getAcceptedFiles().forEach(function(file) {
                    formData.append('foto_img[]', file, file.name);
                });
            }

            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-foto-store') }}",
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
                        window.location.reload();
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message ?? xhr.responseJSON?.msg ??
                        'Terjadi kesalahan';
                    iziToast.error({
                        title: "Gagal!",
                        message: errorMsg
                    });
                }
            });
        });
    </script>
@endpush
