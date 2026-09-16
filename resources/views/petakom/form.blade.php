@extends('layouts.app')
@push('styles')
    <style>
        .petakom-form-container {
            max-width: 860px;
            margin: 0 auto;
        }

        .petakom-likert-row {
            display: grid;
            grid-template-columns: repeat(var(--likert-count), minmax(72px, 1fr));
            gap: 10px;
        }

        .petakom-likert-scale {
            max-width: 680px;
            margin: 0 auto;
        }

        .petakom-likert-item .form-check-input {
            margin-top: 0;
        }

        .petakom-likert-label {
            display: block;
            border: 1px solid var(--vz-border-color);
            border-radius: .25rem;
            padding: 8px 10px;
            text-align: center;
            cursor: pointer;
            background: var(--vz-card-bg);
            transition: .15s ease-in-out;
        }

        .petakom-likert-item .form-check-input:checked+.petakom-likert-label {
            border-color: #164c69;
            background: #2A9CDB;
            color: #fff;
            font-weight: 600;
        }

        @media (max-width: 575.98px) {
            .petakom-likert-row {
                grid-template-columns: repeat(var(--likert-count), minmax(52px, 1fr));
                gap: 8px;
            }
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
                            </div>
                            <div class="card-body">
                                <form action="#" method="POST" class="petakom-form-container">
                                    @csrf
                                    @php
                                        $nomorSoal = 1;
                                    @endphp

                                    @forelse ($soal_instrumen as $soal)
                                        @if ($soal->question_type === 'information')
                                            <div class="alert alert-info mb-3">
                                                <h5 class="alert-heading mb-2">{{ $soal->question }}</h5>
                                                @if (!empty($soal->sub_question))
                                                    <div>{{ $soal->sub_question }}</div>
                                                @endif
                                            </div>
                                        @elseif ($soal->question_type === 'likert')
                                            @php
                                                $minValue = (int) $soal->min_value;
                                                $maxValue = (int) $soal->max_value;
                                                $startValue = min($minValue, $maxValue);
                                                $endValue = max($minValue, $maxValue);
                                            @endphp

                                            <div class="border rounded p-3 mb-3">
                                                <div class="d-flex gap-2 mb-2">
                                                    {{-- <span class="badge bg-primary-subtle text-primary">
                                                        {{ $nomorSoal }}
                                                    </span> --}}
                                                    <strong>{{ $nomorSoal }}. </strong>
                                                    <div class="fw-semibold">{{ $soal->question }}</div>
                                                </div>

                                                <div class="petakom-likert-scale"
                                                    style="--likert-count: {{ $endValue - $startValue + 1 }}">
                                                    <div class="petakom-likert-row">
                                                        @for ($value = $startValue; $value <= $endValue; $value++)
                                                            <div class="form-check petakom-likert-item ps-0 mb-0">
                                                                <input class="form-check-input d-none petakom-jawaban"
                                                                    type="radio" name="jawaban[{{ $soal->id }}]"
                                                                    id="jawaban_{{ $soal->id }}_{{ $value }}"
                                                                    value="{{ $value }}"
                                                                    data-id-soal="{{ $soal->id }}"
                                                                    {{ (string) ($jawaban_soal[$soal->id] ?? '') === (string) $value ? 'checked' : '' }}>
                                                                <label class="petakom-likert-label"
                                                                    for="jawaban_{{ $soal->id }}_{{ $value }}">
                                                                    {{ $value }}
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>

                                                    <div class="d-flex justify-content-between fw-semibold fs-13 mt-2">
                                                        <span>Tidak {{ $soal->likert_text }}</span>
                                                        <span class="text-end">Sangat {{ $soal->likert_text }}</span>
                                                    </div>
                                                    <small class="text-muted petakom-save-status"
                                                        data-id-soal="{{ $soal->id }}"></small>
                                                </div>
                                            </div>

                                            @php
                                                $nomorSoal++;
                                            @endphp
                                        @endif
                                    @empty
                                        <div class="alert alert-warning mb-0">Soal instrumen belum tersedia.</div>
                                    @endforelse

                                    @if ($soal_instrumen->where('question_type', 'likert')->count() > 0)
                                        <div class="text-end mt-4">
                                            <button type="button" class="btn btn-primary" id="btn-selesai">
                                                Selesai
                                            </button>
                                        </div>
                                    @endif
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
        $(function() {
            var saveTimers = {};
            var autosaveUrl = "{{ route('petakom-instrumen-autosave', ['id_instrumen' => $id_instrumen_enc]) }}";
            var selesaiUrl = "{{ route('petakom-instrumen-selesai', ['id_instrumen' => $id_instrumen_enc]) }}";

            function setSaveStatus(idSoal, className, text) {
                $('.petakom-save-status[data-id-soal="' + idSoal + '"]')
                    .removeClass('text-muted text-success text-danger')
                    .addClass(className)
                    .text(text);
            }

            $('.petakom-jawaban').on('change', function() {
                var input = $(this);
                var idSoal = input.data('id-soal');
                var value = input.val();

                clearTimeout(saveTimers[idSoal]);
                setSaveStatus(idSoal, 'text-muted', 'Menyimpan...');

                saveTimers[idSoal] = setTimeout(function() {
                    $.ajax({
                        url: autosaveUrl,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_soal: idSoal,
                            value: value
                        },
                        success: function(response) {
                            setSaveStatus(idSoal, 'text-success', response.message);
                        },
                        error: function(xhr) {
                            var message = 'Gagal menyimpan jawaban.';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            setSaveStatus(idSoal, 'text-danger', message);
                        }
                    });
                }, 1000);
            });

            $('#btn-selesai').on('click', function() {
                var button = $(this);

                button.prop('disabled', true).text('Mengecek...');

                $.ajax({
                    url: selesaiUrl,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Selesai',
                            text: response.message
                        });
                    },
                    error: function(xhr) {
                        var message = 'Instrumen belum lengkap.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: 'Belum lengkap',
                            text: message
                        });
                    },
                    complete: function() {
                        button.prop('disabled', false).text('Selesai');
                    }
                });
            });
        });
    </script>
@endpush
