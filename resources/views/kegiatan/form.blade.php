@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css">
    <style>
        .note-editor.note-frame {
            border-radius: 0;
            border-color: #ced4da;
        }

        .note-editor .note-toolbar {
            background: #f8f9fa;
        }

        .note-editor .note-editable {
            min-height: 140px;
            max-height: 140px;
            overflow-y: auto;
            background: #fff;
        }

        #banner-dropzone {
            min-height: 190px;
            border: 1px dashed #adb5bd;
            border-radius: 0;
            background: #fbfbfb;
            padding: 12px;
        }

        #banner-dropzone.dropzone .dz-message {
            margin: 2.5rem 0;
            text-align: center;
        }

        #banner-dropzone.dropzone .dz-message .dz-button {
            border: 0;
            background: transparent;
            color: #212529;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 0;
        }

        #banner-dropzone.dropzone .dz-preview {
            margin: 10px;
        }

        #banner-dropzone.dropzone .dz-preview .dz-image {
            border-radius: 0;
            width: 110px;
            height: 110px;
        }

        #banner-dropzone.dropzone .dz-preview .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #banner-preview img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border: 1px solid #dee2e6;
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
                                <form id="form-kegiatan" enctype="multipart/form-data">
                                    <input type="hidden" id="id_kegiatan" name="id_kegiatan"
                                        value="{{ $id_kegiatan ?? '' }}">
                                    <input type="hidden" id="deskripsi" name="deskripsi">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Penanggungjawab Kegiatan</label>
                                            <select name="id_penanggungjawab" id="id_penanggungjawab" class="form-select">
                                                <option value="">Pilih Penanggungjawab Kegiatan</option>
                                                @foreach ($ref_timkerja as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_tim_kerja }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="nama_kegiatan">Nama Kegiatan</label>
                                            <input type="text" class="form-control" id="nama_kegiatan"
                                                name="nama_kegiatan" placeholder="Contoh: Pelatihan Pengembangan Web">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="jenis_kegiatan">Jenis Kegiatan</label>
                                            <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan">
                                                <option value="">Pilih Jenis Kegiatan</option>
                                                @foreach ($ref_jenis_kegiatan as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_jenis_kegiatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sumber_dana">Sumber Dana</label>
                                            <select class="form-select" id="sumber_dana" name="sumber_dana">
                                                <option value="">Pilih Sumber Dana</option>
                                                <option value="DIPA">DIPA</option>
                                                <option value="NON DIPA">NON DIPA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="tanggal_mulai">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="tanggal_mulai"
                                                name="tanggal_mulai">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tanggal_selesai">Tanggal Selesai</label>
                                            <input type="date" class="form-control" id="tanggal_selesai"
                                                name="tanggal_selesai">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="lokasi_kegiatan">Lokasi</label>
                                            <input type="text" class="form-control" id="lokasi_kegiatan"
                                                name="lokasi_kegiatan" placeholder="Contoh: Gedung Serbaguna">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="kuota_peserta">Kuota Peserta</label>
                                            <input type="number" class="form-control" id="kuota_peserta"
                                                name="kuota_peserta" placeholder="Contoh: 50">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="deskripsi-editor">Deskripsi Kegiatan</label>
                                            <textarea id="deskripsi-editor"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="mb-2">Banner Image</label>
                                            <div id="banner-dropzone" class="dropzone">
                                                <div class="dz-message needsclick">
                                                    <div class="mb-2">
                                                        <i class="display-6 text-muted ri-upload-cloud-2-line"></i>
                                                    </div>
                                                    <h5 class="mb-1">Drop files here or click to upload</h5>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-2">Upload gambar JPG/PNG. Kalau edit dan
                                                upload baru, banner lama akan terganti</small>
                                            <div id="banner-preview" class="row g-2 mt-2"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit"> <i class="fas fa-save"></i>
                                                Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        let bannerDropzone = null;

        $(document).ready(function() {
            initSummernote();
            initDropzone();

            const idKegiatan = $('#id_kegiatan').val();
            if (idKegiatan) {
                loadDetail(idKegiatan);
            }
        });

        function initSummernote() {
            $('#deskripsi-editor').summernote({
                placeholder: 'Tulis deskripsi kegiatan...',
                height: 140,
                minHeight: 140,
                maxHeight: 140,
                disableResizeEditor: true,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        }

        function initDropzone() {
            bannerDropzone = new Dropzone('#banner-dropzone', {
                url: "{{ route('kegiatan-store') }}",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFilesize: 2,
                acceptedFiles: 'image/jpeg,image/png,image/jpg',
                addRemoveLinks: true,
                dictDefaultMessage: 'Drop files here or click to upload.',
                dictRemoveFile: 'Delete',
                previewsContainer: '#banner-dropzone',
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

        function renderBannerPreview(images) {
            $('#banner-preview').empty();

            if (!Array.isArray(images) || images.length === 0) {
                return;
            }

            images.forEach(function(path) {
                if (!path) {
                    return;
                }

                const imageUrl = "{{ asset('storage') }}/" + path;
                const html = `
                    <div class="col-md-3">
                        <a href="${imageUrl}" target="_blank" class="d-block">
                            <img src="${imageUrl}" alt="Banner kegiatan" class="img-fluid">
                        </a>
                    </div>
                `;
                $('#banner-preview').append(html);
            });
        }

        function resetBannerPreview() {
            $('#banner-preview').empty();
            if (bannerDropzone) {
                bannerDropzone.removeAllFiles(true);
            }
        }

        $('#form-kegiatan').on('submit', function(e) {
            e.preventDefault();

            const deskripsiHtml = $('#deskripsi-editor').summernote('code');
            const deskripsiText = $('<div>').html(deskripsiHtml).text().trim();
            const isEdit = $('#id_kegiatan').val().trim() !== '';

            if (!deskripsiText) {
                iziToast.warning({
                    title: "Perhatian",
                    message: "Deskripsi kegiatan wajib diisi."
                });
                return;
            }

            if (!isEdit && (!bannerDropzone || bannerDropzone.getAcceptedFiles().length === 0)) {
                iziToast.warning({
                    title: "Perhatian",
                    message: "Banner image wajib diisi."
                });
                return;
            }

            $('#deskripsi').val(deskripsiHtml);

            let formData = new FormData(this);
            if (bannerDropzone) {
                bannerDropzone.getAcceptedFiles().forEach(function(file) {
                    formData.append('banner_img[]', file, file.name);
                });
            }

            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-store') }}",
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
                        window.location.href = "{{ route('kegiatan') }}";
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

        function loadDetail(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('kegiatan-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#id_penanggungjawab').val(response.data.id_penanggungjawab);
                        $('#nama_kegiatan').val(response.data.nama_kegiatan);
                        $('#jenis_kegiatan').val(response.data.id_jenis_kegiatan);
                        $('#sumber_dana').val(response.data.sumber_dana);
                        $('#tanggal_mulai').val(response.data.tanggal_mulai);
                        $('#tanggal_selesai').val(response.data.tanggal_selesai);
                        $('#lokasi_kegiatan').val(response.data.lokasi_kegiatan);
                        $('#kuota_peserta').val(response.data.kuota_peserta);
                        $('#deskripsi-editor').summernote('code', response.data.deskripsi ?? '');
                        $('#deskripsi').val(response.data.deskripsi ?? '');

                        let bannerImages = response.data.banner_img ?? [];
                        if (typeof bannerImages === 'string') {
                            try {
                                bannerImages = JSON.parse(bannerImages);
                            } catch (e) {
                                bannerImages = [];
                            }
                        }

                        renderBannerPreview(bannerImages);
                    }
                }
            });
        }
    </script>
@endpush
