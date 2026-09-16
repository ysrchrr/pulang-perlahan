@extends('layouts.app')

@push('styles')
    <style>
        /* =============================================
             * Verifikasi Page Styles
             * ============================================= */
        .detail-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        .detail-label {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6c757d;
            margin-bottom: .25rem;
        }

        .detail-value {
            font-size: .95rem;
            color: var(--vz-body-color, #212529);
        }

        /* Ticket badge */
        .ticket-badge {
            display: inline-block;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: .45rem 1rem;
            border-radius: 30px;
        }

        /* Eviden grid */
        .eviden-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
        }

        .eviden-thumb {
            width: 110px;
            height: 110px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
        }

        .eviden-thumb:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .18);
        }

        /* Decision card styles */
        .decision-card {
            border: 2px solid transparent;
            border-radius: 10px;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s, background .2s;
            padding: 1rem 1.25rem;
            background: transparent;
        }

        .decision-card:hover {
            border-color: #adb5bd;
            background: rgba(0, 0, 0, .03);
        }

        .decision-card.active-tindak_lanjut {
            border-color: #0ab39c;
            background: rgba(10, 179, 156, .07);
        }

        .decision-card.active-eskalasi {
            border-color: #f7b84b;
            background: rgba(247, 184, 75, .07);
        }

        .decision-card.active-ditolak {
            border-color: #f06548;
            background: rgba(240, 101, 72, .07);
        }

        .decision-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-right: .75rem;
            flex-shrink: 0;
        }

        /* Lightbox overlay */
        #lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .85);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        #lightbox-overlay.show {
            display: flex;
        }

        #lightbox-overlay img {
            max-width: 90vw;
            max-height: 88vh;
            border-radius: 8px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .5);
        }

        #lightbox-overlay .lb-close {
            position: absolute;
            top: 16px;
            right: 20px;
            color: #fff;
            font-size: 1.8rem;
            cursor: pointer;
            line-height: 1;
        }
    </style>
@endpush

@section('contents')
    {{-- Lightbox --}}
    <div id="lightbox-overlay" onclick="closeLightbox()">
        <span class="lb-close" title="Tutup">&times;</span>
        <img id="lightbox-img" src="" alt="Bukti">
    </div>

    <div class="row">
        <div class="col-12">

            {{-- Page Header --}}
            <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">{{ $page_title }}</h4>
                <a href="{{ route('pengaduan') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="row g-4">
                {{-- ======================================================
                     LEFT COLUMN: Detail Pengaduan
                     ====================================================== --}}
                <div class="col-lg-7">

                    {{-- Info Tiket --}}
                    <div class="card detail-card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="ticket-badge badge bg-danger text-white">
                                    {{ $pengaduan->nomor_tiket }}
                                </span>
                                {!! $pengaduan->status_badge !!}
                            </div>
                            <h5 class="fw-semibold mb-1">{{ $pengaduan->judul }}</h5>
                            <p class="text-muted small mb-0">
                                <i class="fa-regular fa-clock me-1"></i>
                                Dibuat pada {{ $pengaduan->created_at->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    {{-- Detail Informasi --}}
                    <div class="card detail-card mb-4">
                        <div class="card-header fw-semibold">
                            <i class="fa-solid fa-circle-info me-2 text-primary"></i> Informasi Pengaduan
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="detail-label">Kategori</div>
                                    <div class="detail-value">
                                        {{ $pengaduan->kategoriPengaduan?->nama_kategori ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Dibuat Oleh</div>
                                    <div class="detail-value">
                                        <i class="fa-regular fa-user me-1 text-muted"></i>
                                        {{ $pengaduan->creator?->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Gedung</div>
                                    <div class="detail-value">
                                        {{ $pengaduan->gedung?->nama_gedung ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Ruang</div>
                                    <div class="detail-value">
                                        {{ $pengaduan->ruang?->nama_ruang ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-label">Deskripsi</div>
                                    <div class="detail-value" style="white-space: pre-line;">{{ $pengaduan->deskripsi }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Foto / Bukti --}}
                    <div class="card detail-card">
                        <div class="card-header fw-semibold">
                            <i class="fa-solid fa-images me-2 text-primary"></i> Foto / Bukti
                        </div>
                        <div class="card-body">
                            @if ($pengaduan->eviden_path && count($pengaduan->eviden_path) > 0)
                                <div class="eviden-grid">
                                    @foreach ($pengaduan->eviden_path as $path)
                                        <img src="{{ Storage::url($path) }}" alt="Bukti" class="eviden-thumb"
                                            onclick="openLightbox('{{ Storage::url($path) }}')">
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0"><i class="fa-solid fa-ban me-1"></i> Tidak ada bukti foto.</p>
                            @endif
                        </div>
                    </div>
                    {{-- end foto --}}

                </div>
                {{-- end left col --}}

                {{-- ======================================================
                     RIGHT COLUMN: Form Keputusan
                     ====================================================== --}}
                <div class="col-lg-5">
                    @if (in_array($pengaduan->status, [1, 2]))
                        {{-- Masih bisa diputuskan --}}
                        <div class="card detail-card">
                            <div class="card-header fw-semibold">
                                <i class="fa-solid fa-scale-balanced me-2 text-warning"></i> Keputusan Verifikasi
                            </div>
                            <div class="card-body">

                                {{-- Pilihan Keputusan --}}
                                <p class="text-muted small mb-3">Pilih salah satu keputusan untuk pengaduan ini:</p>

                                {{-- Tindak Lanjut --}}
                                <div class="decision-card mb-2" id="card-tindak_lanjut"
                                    onclick="selectKeputusan('tindak_lanjut')">
                                    <div class="d-flex align-items-center">
                                        <span class="decision-icon bg-success bg-opacity-15 text-success">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold">Diterima / Tindak Lanjut</div>
                                            <div class="text-muted small">Pengaduan diterima dan akan ditindaklanjuti.</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Eskalasi --}}
                                <div class="decision-card mb-2" id="card-eskalasi" onclick="selectKeputusan('eskalasi')">
                                    <div class="d-flex align-items-center">
                                        <span class="decision-icon bg-warning bg-opacity-15 text-warning">
                                            <i class="fa-solid fa-arrow-up-right-dots"></i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold">Eskalasi ke Wakil Dekan</div>
                                            <div class="text-muted small">Teruskan pengaduan kepada Wakil Dekan.</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ditolak --}}
                                <div class="decision-card mb-3" id="card-ditolak" onclick="selectKeputusan('ditolak')">
                                    <div class="d-flex align-items-center">
                                        <span class="decision-icon bg-danger bg-opacity-15 text-danger">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold">Ditolak</div>
                                            <div class="text-muted small">Pengaduan tidak dapat diproses.</div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                {{-- Catatan Tindak Lanjut (untuk tindak_lanjut & ditolak) --}}
                                <div id="field-catatan" style="display:none;" class="mb-3">
                                    <label for="catatan_tindak_lanjut" class="form-label fw-semibold">
                                        Catatan Tindak Lanjut
                                        <small class="text-muted fw-normal">(opsional)</small>
                                    </label>
                                    <textarea class="form-control" id="catatan_tindak_lanjut" name="catatan_tindak_lanjut" rows="3"
                                        placeholder="Tuliskan catatan..."></textarea>
                                </div>

                                {{-- Pilihan Wakil Dekan (untuk eskalasi) --}}
                                <div id="field-eskalasi" style="display:none;" class="mb-3">
                                    <label for="escalated_to" class="form-label fw-semibold">
                                        Pilih Wakil Dekan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="escalated_to" name="escalated_to">
                                        <option value="">-- Pilih Wakil Dekan --</option>
                                        @foreach ($wakil_dekan_users as $wd)
                                            <option value="{{ $wd->id }}">{{ $wd->name }}
                                                @if ($wd->email)
                                                    ({{ $wd->email }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($wakil_dekan_users->isEmpty())
                                        <div class="text-danger small mt-1">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                            Tidak ada user dengan role Wakil Dekan.
                                        </div>
                                    @endif

                                    <label for="catatan_eskalasi" class="form-label fw-semibold mt-3">
                                        Catatan Eskalasi
                                        <small class="text-muted fw-normal">(opsional)</small>
                                    </label>
                                    <textarea class="form-control" id="catatan_eskalasi" name="catatan_eskalasi" rows="3"
                                        placeholder="Alasan eskalasi..."></textarea>
                                </div>

                                {{-- Hidden input keputusan --}}
                                <input type="hidden" id="keputusan" name="keputusan" value="">

                                {{-- Tombol Submit --}}
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" id="btn-simpan-verifikasi"
                                        onclick="submitVerifikasi()" disabled>
                                        <i class="fa-solid fa-paper-plane me-1"></i> Simpan Keputusan
                                    </button>
                                </div>

                            </div>
                        </div>
                    @else
                        {{-- Sudah diputuskan --}}
                        <div class="card detail-card border-0">
                            <div class="card-body text-center py-5">
                                <i class="fa-solid fa-circle-info fa-3x text-info mb-3"></i>
                                <p class="fw-semibold mb-1">Pengaduan ini sudah diproses</p>
                                <p class="text-muted small mb-0">Status: {!! $pengaduan->status_badge !!}</p>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- end right col --}}

            </div>
            {{-- end row --}}

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ============================================================
         * Lightbox
         * ============================================================ */
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox-overlay').classList.add('show');
        }

        function closeLightbox() {
            document.getElementById('lightbox-overlay').classList.remove('show');
            document.getElementById('lightbox-img').src = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });

        /* ============================================================
         * Decision Card Selection
         * ============================================================ */
        let currentKeputusan = '';

        function selectKeputusan(val) {
            currentKeputusan = val;
            document.getElementById('keputusan').value = val;

            // Reset card styles
            ['tindak_lanjut', 'eskalasi', 'ditolak'].forEach(function(k) {
                let el = document.getElementById('card-' + k);
                el.className = 'decision-card mb-2';
                if (k === 'ditolak') el.className = 'decision-card mb-3';
            });

            // Activate selected
            let activeCard = document.getElementById('card-' + val);
            activeCard.classList.add('active-' + val);

            // Show/hide extra fields
            document.getElementById('field-catatan').style.display = (val === 'tindak_lanjut' || val === 'ditolak') ?
                'block' : 'none';
            document.getElementById('field-eskalasi').style.display = (val === 'eskalasi') ? 'block' : 'none';

            // Enable submit
            document.getElementById('btn-simpan-verifikasi').disabled = false;
        }

        /* ============================================================
         * Submit Verifikasi
         * ============================================================ */
        function submitVerifikasi() {
            let keputusan = document.getElementById('keputusan').value;
            if (!keputusan) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Pilih keputusan terlebih dahulu.'
                });
                return;
            }

            if (keputusan === 'eskalasi') {
                let escalated_to = document.getElementById('escalated_to').value;
                if (!escalated_to) {
                    iziToast.warning({
                        title: 'Perhatian',
                        message: 'Pilih Wakil Dekan yang dituju.'
                    });
                    return;
                }
            }

            let payload = {
                _token: '{{ csrf_token() }}',
                keputusan: keputusan,
                catatan_tindak_lanjut: document.getElementById('catatan_tindak_lanjut')?.value ?? '',
                escalated_to: document.getElementById('escalated_to')?.value ?? '',
                catatan_eskalasi: document.getElementById('catatan_eskalasi')?.value ?? '',
            };

            let $btn = document.getElementById('btn-simpan-verifikasi');
            $btn.disabled = true;
            $btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...';

            $.ajax({
                url: '{{ route('pengaduan-store-verifikasi', $enc_id) }}',
                type: 'POST',
                data: payload,
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: res.msg
                        });
                        setTimeout(function() {
                            window.location.href = '{{ route('pengaduan') }}';
                        }, 1500);
                    } else {
                        iziToast.error({
                            title: 'Gagal!',
                            message: res.msg
                        });
                        $btn.disabled = false;
                        $btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Simpan Keputusan';
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.msg ?? xhr.responseJSON?.message ?? 'Terjadi kesalahan server.';
                    iziToast.error({
                        title: 'Error',
                        message: msg
                    });
                    $btn.disabled = false;
                    $btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Simpan Keputusan';
                }
            });
        }
    </script>
@endpush
