@extends('layouts.app')

@section('contents')
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('petakom-master-instrumen') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-0">{{ $page_title }}</h4>
                        <div class="text-muted mt-1">{{ $template->nama_instrumen }}</div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button type="button" class="btn btn-secondary" onclick="modalImportSoal()">
                            Import Excel
                        </button>
                        <button type="button" class="btn btn-primary" onclick="modalSoal()">
                            Tambah Soal
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle nowrap" id="table-soal">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Soal</th>
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

    <div class="modal fade" id="modal-soal" tabindex="-1" aria-labelledby="modal-soalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formSoal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-soalLabel">Tambah Soal</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_soal" id="id_soal">
                        <input type="hidden" name="id_template" id="id_template" value="{{ enc_id($template->id) }}">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="question_type">Tipe Pertanyaan</label>
                                <select name="question_type" id="question_type" class="form-select">
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="information">Information</option>
                                    <option value="likert">Likert</option>
                                    <option value="select_option">Select Option</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="question-row">
                            <div class="col-md-12">
                                <label for="question">Soal</label>
                                <textarea name="question" id="question" class="form-control" rows="4" placeholder="Tulis soal di sini"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3 question-config" id="information-config">
                            <div class="col-md-12">
                                <label for="sub_question">Masukkan Informasi</label>
                                <textarea name="sub_question" id="sub_question" class="form-control" rows="4"
                                    placeholder="Silahkan pilih pernyataan sesuai dengan keadaan sebenarnya"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3 question-config" id="select-option-config">
                            <div class="col-md-12">
                                <label for="option">Pilihan</label>
                                <textarea name="option" id="option" class="form-control" rows="4"
                                    placeholder="Kota Jambi, Kota Sungai Penuh, Muaro Jambi"></textarea>
                                <small class="text-muted">Pisahkan pilihan dengan koma.</small>
                            </div>
                        </div>
                        <div class="row mb-3 question-config" id="likert-config">
                            <div class="col-md-6">
                                <label for="min_value">Min Value</label>
                                <input type="number" class="form-control" name="min_value" id="min_value" value="1"
                                    placeholder="1">
                            </div>
                            <div class="col-md-6">
                                <label for="max_value">Max Value</label>
                                <input type="number" class="form-control" name="max_value" id="max_value"
                                    value="4" placeholder="4">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="likert_text">Teks</label>
                                <input type="text" class="form-control" name="likert_text" id="likert_text"
                                    placeholder="Contoh: Setuju">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="storeSoal()">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-import-soal" tabindex="-1" aria-labelledby="modal-import-soalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formImportSoal" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-import-soalLabel">Import Soal Instrumen</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Unduh template Excel, isi data soal, lalu upload kembali di sini.
                            <br>
                            <a href="{{ route('petakom-master-instrumen-soal-template') }}"
                                class="btn btn-sm btn-info mt-2">
                                <i class="fas fa-download"></i> Unduh Template
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">File Excel (.xlsx, .xls)</label>
                            <input type="file" class="form-control" id="file_excel" name="file_excel"
                                accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="importSoal()">Proses Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            loadTable();
            toggleQuestionConfig();

            $('#question_type').on('change', function() {
                toggleQuestionConfig();
            });
        });

        function modalSoal() {
            $('#modal-soalLabel').text('Tambah Soal');
            $('#formSoal')[0].reset();
            $('#id_soal').val('');
            $('#id_template').val("{{ enc_id($template->id) }}");
            toggleQuestionConfig();
            $('#modal-soal').modal('show');
        }

        function modalImportSoal() {
            $('#formImportSoal')[0].reset();
            $('#modal-import-soal').modal('show');
        }

        function toggleQuestionConfig() {
            let questionType = $('#question_type').val();

            $('.question-config').hide();
            $('#question-row').show();

            if (questionType === 'select_option') {
                $('#select-option-config').show();
            }

            if (questionType === 'likert') {
                $('#likert-config').show();
            }

            if (questionType === 'information') {
                $('#information-config').show();
            }
        }

        function storeSoal() {
            $.ajax({
                type: "POST",
                url: "{{ route('petakom-master-instrumen-soal-store') }}",
                data: $('#formSoal').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal-soal').modal('hide');
                        loadTable();
                    } else {
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

                    iziToast.error({
                        title: "Gagal!",
                        message: message
                    });
                }
            });
        }

        function importSoal() {
            let formData = new FormData($('#formImportSoal')[0]);

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Harap tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('petakom-master-instrumen-soal-import', ['id_template' => enc_id($template->id)]) }}",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    Swal.close();

                    if (response.status === true) {
                        iziToast.success({
                            title: "Berhasil!",
                            message: response.msg
                        });
                        $('#modal-import-soal').modal('hide');
                        loadTable();
                    } else {
                        iziToast.error({
                            title: "Gagal!",
                            message: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();

                    let message = xhr.responseJSON?.message ?? xhr.responseJSON?.msg ??
                        'Terjadi kesalahan saat import.';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).map(function(item) {
                            return item[0];
                        }).join('<br>');
                    }

                    iziToast.error({
                        title: "Gagal!",
                        message: message
                    });
                }
            });
        }

        function toEditSoal(id) {
            $('#modal-soalLabel').text('Edit Soal');
            $('#formSoal')[0].reset();
            $('#id_soal').val(id);
            $('#id_template').val("{{ enc_id($template->id) }}");

            $.ajax({
                type: "POST",
                url: "{{ route('petakom-master-instrumen-soal-detail') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        $('#question_type').val(response.data.question_type);
                        $('#question').val(response.data.question);
                        $('#sub_question').val(response.data.sub_question);
                        $('#option').val(formatOptionText(response.data.option));
                        $('#min_value').val(response.data.min_value);
                        $('#max_value').val(response.data.max_value);
                        $('#likert_text').val(response.data.likert_text);
                        toggleQuestionConfig();
                    }

                    $('#modal-soal').modal('show');
                },
                error: function(xhr) {
                    iziToast.error({
                        title: "Gagal!",
                        message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

        function toRemoveSoal(id) {
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
                        url: "{{ route('petakom-master-instrumen-soal-delete') }}",
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
            if ($.fn.DataTable.isDataTable('#table-soal')) {
                $('#table-soal').DataTable().destroy();
            }

            $('#table-soal').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                ajax: "{{ route('petakom-master-instrumen-soal-get-data', ['id_template' => enc_id($template->id)]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'question_type_label',
                        name: 'question_type',
                        className: 'va-middle text-center'
                    },
                    {
                        data: 'question_text',
                        name: 'question',
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

        function formatOptionText(value) {
            if (!value) {
                return '';
            }

            try {
                let parsed = JSON.parse(value);

                if ($.isArray(parsed)) {
                    return parsed.join(', ');
                }
            } catch (e) {}

            return value;
        }
    </script>
@endpush
