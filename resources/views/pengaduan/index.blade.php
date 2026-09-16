@extends('layouts.app')
@push('styles')
    <style>
        /* Preview grid for uploaded images */
        #preview-eviden {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        #preview-eviden .preview-item {
            position: relative;
            width: 90px;
            height: 90px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #dee2e6;
        }

        #preview-eviden .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #preview-eviden .preview-item .remove-img {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(220, 53, 69, 0.85);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .upload-area {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s;
            background: transparent;
        }

        .upload-area:hover {
            border-color: #0d6efd;
        }
    </style>
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
                                <div class="flex-shrink-0">
                                    @if (session('role_slug') == 'admin_prodi')
                                        <button onclick="modalBuatPengaduan()" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Buat Pengaduan
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-pengaduan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">No. Tiket</th>
                                                <th class="text-center">Judul</th>
                                                <th class="text-center">Kategori</th>
                                                <th class="text-center">Lokasi</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Dibuat Oleh</th>
                                                <th class="text-center">Tgl. Dibuat</th>
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

    <!-- Modal Buat Pengaduan -->
    <div class="modal fade" id="modal_pengaduan" tabindex="-1" aria-labelledby="modal_pengaduanLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <form id="formPengaduan" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal_pengaduanLabel">
                            Buat Pengaduan
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Kategori Pengaduan -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="kategori_pengaduan_id" class="form-label">
                                    Kategori Pengaduan <span class="text-danger">*</span>
                                </label>
                                <select name="kategori_pengaduan_id" id="kategori_pengaduan_id" class="form-select"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategori_pengaduan as $kp)
                                        <option value="{{ $kp->id }}">{{ $kp->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Gedung & Ruang -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="gedung_id" class="form-label">
                                    Gedung <small class="text-muted">(opsional)</small>
                                </label>
                                <select name="gedung_id" id="gedung_id" class="form-control select2-gedung"
                                    style="width:100%;">
                                    <option value="">-- Pilih Gedung --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="ruang_id" class="form-label">
                                    Ruang <small class="text-muted">(opsional)</small>
                                </label>
                                <select name="ruang_id" id="ruang_id" class="form-control select2-ruang"
                                    style="width:100%;">
                                    <option value="">-- Pilih Ruang --</option>
                                </select>
                                <div id="ruang-loading" class="text-muted small mt-1" style="display:none;">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Memuat ruang...
                                </div>
                            </div>
                        </div>

                        <!-- Judul -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="judul" class="form-label">
                                    Judul Pengaduan <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    placeholder="Contoh: Lampu ruang kelas mati" required maxlength="255">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="deskripsi" class="form-label">
                                    Deskripsi <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                    placeholder="Jelaskan pengaduan secara detail..." required></textarea>
                            </div>
                        </div>

                        <!-- Foto / Bukti -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">
                                    Foto / Bukti <small class="text-muted">(opsional, bisa pilih lebih dari 1)</small>
                                </label>
                                <div class="upload-area" id="upload-area" onclick="$('#eviden_path').click()">
                                    <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2 d-block"></i>
                                    <span class="text-muted">Klik untuk memilih foto / seret file ke sini</span>
                                </div>
                                <input type="file" id="eviden_path" name="eviden_path[]" accept="image/*" multiple
                                    style="display:none;">
                                <div id="preview-eviden"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="btn-simpan-pengaduan"
                            onclick="storePengaduan()">
                            <i class="fa-solid fa-paper-plane me-1"></i>Kirim Pengaduan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ============================================================
         * Helpers
         * ============================================================ */
        let selectedFiles = [];

        /* ============================================================
         * Data Table
         * ============================================================ */
        $(document).ready(function() {
            loadTable();
        });

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-pengaduan')) {
                $('#table-pengaduan').DataTable().destroy();
            }
            $('#table-pengaduan').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('pengaduan-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nomor_tiket_col',
                        name: 'nomor_tiket',
                        className: 'va-middle'
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        className: 'va-middle'
                    },
                    {
                        data: 'kategori_col',
                        name: 'kategori',
                        className: 'va-middle'
                    },
                    {
                        data: 'lokasi_col',
                        name: 'lokasi',
                        className: 'va-middle'
                    },
                    {
                        data: 'status_col',
                        name: 'status',
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'dibuat_oleh',
                        name: 'dibuat_oleh',
                        className: 'va-middle'
                    },
                    {
                        data: 'tanggal_col',
                        name: 'created_at',
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });
        }

        /* ============================================================
         * Modal Buat Pengaduan
         * ============================================================ */
        function modalBuatPengaduan() {
            // Reset form
            $('#formPengaduan')[0].reset();
            selectedFiles = [];
            $('#preview-eviden').html('');
            $('#kategori_pengaduan_id').val('');

            // Reset Select2
            if ($('.select2-gedung').data('select2')) {
                $('.select2-gedung').val(null).trigger('change');
            }
            if ($('.select2-ruang').data('select2')) {
                $('.select2-ruang').val(null).trigger('change');
            }

            // Reset ruang options
            $('#ruang_id').html('<option value="">-- Pilih Gedung dulu --</option>');

            $('#modal_pengaduan').modal('show');
        }

        /* ============================================================
         * Select2 — Gedung (AJAX)
         * ============================================================ */
        $('#modal_pengaduan').on('shown.bs.modal', function() {
            if (!$('.select2-gedung').data('select2')) {
                initSelect2Gedung();
            }
            if (!$('.select2-ruang').data('select2')) {
                initSelect2Ruang();
            }
        });

        function initSelect2Gedung() {
            $('.select2-gedung').select2({
                dropdownParent: $('#modal_pengaduan'),
                placeholder: '-- Pilih Gedung --',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('master-ruang-get-gedung') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });

            // When gedung changes, load ruang
            $('.select2-gedung').on('change', function() {
                let gedungId = $(this).val();
                loadRuangByGedung(gedungId);
            });
        }

        function initSelect2Ruang() {
            $('.select2-ruang').select2({
                dropdownParent: $('#modal_pengaduan'),
                placeholder: '-- Pilih Ruang --',
                allowClear: true,
                width: '100%',
            });
        }

        /* ============================================================
         * Load Ruang berdasarkan Gedung
         * ============================================================ */
        function loadRuangByGedung(gedungId) {
            $('#ruang_id').html('<option value="">-- Pilih Ruang --</option>');
            if ($('.select2-ruang').data('select2')) {
                $('.select2-ruang').val(null).trigger('change');
            }

            if (!gedungId) return;

            $('#ruang-loading').show();

            $.ajax({
                url: "{{ route('pengaduan-ruang-by-gedung') }}",
                type: 'GET',
                data: {
                    gedung_id: gedungId
                },
                dataType: 'json',
                success: function(response) {
                    $('#ruang-loading').hide();
                    let options = '<option value="">-- Pilih Ruang --</option>';
                    if (response.results && response.results.length > 0) {
                        response.results.forEach(function(item) {
                            options += `<option value="${item.id}">${item.text}</option>`;
                        });
                    } else {
                        options += '<option value="" disabled>Tidak ada ruang tersedia</option>';
                    }
                    $('#ruang_id').html(options);
                    if ($('.select2-ruang').data('select2')) {
                        $('.select2-ruang').val(null).trigger('change');
                    }
                },
                error: function() {
                    $('#ruang-loading').hide();
                    iziToast.error({
                        title: 'Error',
                        message: 'Gagal memuat daftar ruang.'
                    });
                }
            });
        }

        /* ============================================================
         * Image Preview (multiple)
         * ============================================================ */
        $('#eviden_path').on('change', function(e) {
            let newFiles = Array.from(e.target.files);
            newFiles.forEach(function(file) {
                if (!file.type.startsWith('image/')) return;
                selectedFiles.push(file);
                let reader = new FileReader();
                let index = selectedFiles.length - 1;
                reader.onload = function(ev) {
                    let html = `
                    <div class="preview-item" id="preview-${index}">
                        <img src="${ev.target.result}" alt="preview">
                        <button type="button" class="remove-img" onclick="removeImage(${index})">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>`;
                    $('#preview-eviden').append(html);
                };
                reader.readAsDataURL(file);
            });
            // Reset input so same files can be re-added after removal
            $(this).val('');
        });

        // Drag & drop support
        let uploadArea = document.getElementById('upload-area');
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#0d6efd';
        });
        uploadArea.addEventListener('dragleave', function() {
            uploadArea.style.borderColor = '#ced4da';
        });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#ced4da';
            let files = e.dataTransfer.files;
            let dt = new DataTransfer();
            for (let f of files) dt.items.add(f);
            document.getElementById('eviden_path').files = dt.files;
            $('#eviden_path').trigger('change');
        });

        function removeImage(index) {
            selectedFiles[index] = null; // mark as removed
            $('#preview-' + index).remove();
        }

        /* ============================================================
         * Store Pengaduan
         * ============================================================ */
        function storePengaduan() {
            let kategori_pengaduan_id = $('#kategori_pengaduan_id').val();
            let gedung_id = $('.select2-gedung').val();
            let ruang_id = $('.select2-ruang').length ? $('.select2-ruang').val() : $('#ruang_id').val();
            let judul = $('#judul').val().trim();
            let deskripsi = $('#deskripsi').val().trim();

            // Validasi
            if (!kategori_pengaduan_id) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Kategori pengaduan wajib dipilih.'
                });
                return;
            }
            // Gedung & Ruang opsional (tidak semua kategori butuh lokasi spesifik)
            if (!judul) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Judul pengaduan wajib diisi.'
                });
                return;
            }
            if (!deskripsi) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Deskripsi wajib diisi.'
                });
                return;
            }

            // Build FormData
            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('kategori_pengaduan_id', kategori_pengaduan_id);
            formData.append('gedung_id', gedung_id);
            formData.append('ruang_id', ruang_id);
            formData.append('judul', judul);
            formData.append('deskripsi', deskripsi);

            // Append only non-null selected files
            selectedFiles.forEach(function(file) {
                if (file !== null) {
                    formData.append('eviden_path[]', file, file.name);
                }
            });

            // Disable tombol
            let $btn = $('#btn-simpan-pengaduan');
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Mengirim...');

            $.ajax({
                type: 'POST',
                url: "{{ route('pengaduan-store') }}",
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $btn.prop('disabled', false).html(
                        '<i class="fa-solid fa-paper-plane me-1"></i>Kirim Pengaduan');
                    if (response.status === true) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.msg
                        });
                        $('#modal_pengaduan').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: 'Gagal!',
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(
                        '<i class="fa-solid fa-paper-plane me-1"></i>Kirim Pengaduan');
                    let msg = xhr.responseJSON ? xhr.responseJSON.msg : 'Terjadi kesalahan server.';
                    iziToast.error({
                        title: 'Error',
                        message: msg
                    });
                }
            });
        }

        function toDetail(id) {
            // TODO: implement detail view
            iziToast.info({
                title: 'Info',
                message: 'Fitur detail segera hadir.'
            });
        }

        function toRemove(id) {
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah anda yakin untuk menghapus pengaduan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pengaduan-destroy') }}",
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
                            showMessage('Terjadi kesalahan. Coba lagi.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
