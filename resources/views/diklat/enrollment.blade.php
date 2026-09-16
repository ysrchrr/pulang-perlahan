@extends('layouts.app')
@push('styles')
    <style>
        #table-kegiatan_wrapper,
        #table-kegiatan_wrapper .table-responsive,
        #table-kegiatan_wrapper .dataTables_scroll,
        #table-kegiatan_wrapper .dataTables_scrollBody,
        #table-kegiatan_wrapper .dataTables_scrollHead,
        #table-kegiatan,
        #table-kegiatan tbody,
        #table-kegiatan tr,
        #table-kegiatan td,
        .card,
        .card-body {
            overflow: visible !important;
        }

        #table-kegiatan td {
            position: relative;
        }

        #table-kegiatan .dropdown-menu {
            z-index: 2000;
        }

        body>.dropdown-menu.dropdown-menu-floating {
            position: fixed;
            z-index: 99999;
            display: block;
        }

        #kegiatan-banner-wrap img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            display: block;
        }

        #kegiatan-banner-wrap .carousel-item {
            max-height: 320px;
        }

        .enrollment-guide-timeline {
            position: relative;
            padding-left: 1.75rem;
        }

        .enrollment-guide-timeline:before {
            content: "";
            position: absolute;
            top: 0.35rem;
            bottom: 0.35rem;
            left: 0.45rem;
            width: 1px;
            background: var(--vz-border-color);
        }

        .enrollment-guide-item {
            position: relative;
            padding-bottom: 1rem;
        }

        .enrollment-guide-item:last-child {
            padding-bottom: 0;
        }

        .enrollment-guide-step {
            position: absolute;
            left: -1.75rem;
            top: 0;
            width: 0.95rem;
            height: 0.95rem;
            border-radius: 50%;
            background: var(--vz-primary);
            box-shadow: 0 0 0 4px rgba(var(--vz-primary-rgb), 0.12);
        }

        .enrollment-guide-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--vz-body-color);
            margin-bottom: 0.15rem;
        }

        .enrollment-guide-desc {
            font-size: 0.8125rem;
            color: var(--vz-secondary-color);
            margin-bottom: 0;
        }

        #enrollment-result {
            display: none;
        }
    </style>
@endpush
@section('contents')
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('diklat') }}" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Masukkan kode kegiatan</label>
                            <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control"
                                placeholder="Contoh: XXXXXX">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" onclick="searchKegiatan()">
                                <i class="fas fa-magnifying-glass"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Cara Enroll Diklat</h4>
                </div>
                <div class="card-body">
                    <div class="enrollment-guide-timeline">
                        <div class="enrollment-guide-item">
                            <span class="enrollment-guide-step"></span>
                            <div class="enrollment-guide-title">Masukkan kode</div>
                            <p class="enrollment-guide-desc">Gunakan kode kegiatan yang diberikan panitia</p>
                        </div>
                        <div class="enrollment-guide-item">
                            <span class="enrollment-guide-step"></span>
                            <div class="enrollment-guide-title">Cari kegiatan</div>
                            <p class="enrollment-guide-desc">Pastikan detail kegiatan sudah sesuai</p>
                        </div>
                        <div class="enrollment-guide-item">
                            <span class="enrollment-guide-step"></span>
                            <div class="enrollment-guide-title">Enroll</div>
                            <p class="enrollment-guide-desc">Klik tombol enroll untuk mendaftar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="enrollment-result">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Success Alert -->
                                    <div class="alert alert-success alert-border-left alert-dismissible fade show"
                                        role="alert">
                                        <i class="ri-check-double-line me-3 align-middle"></i> <strong>Berhasil! </strong>
                                        Diklat ditemukan dengan kode kegiatan yang dimasukkan. Silakan klik tombol "Enroll"
                                        di bawah untuk mendaftar
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="text-muted">
                                        <input type="hidden" name="id_kegiatan" id="id_kegiatan" class="form-control">
                                        <h6 class="mb-3 fw-semibold text-uppercase" id="kegiatan-title"></h6>
                                        <p id="kegiatan-description"></p>
                                        <div class="row justify-content-center mb-3">
                                            <div class="col-12 col-md-8 col-lg-6">
                                                <div id="kegiatan-banner-wrap"></div>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <div class="row gy-3">
                                                <div class="col-lg-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium"><i
                                                                class="fa-solid fa-calendar-days"></i> Periode Pelaksanaan
                                                        </p>
                                                        <h5 class="fs-15 mb-0" id="kegiatan-periode"></h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium"><i
                                                                class="fa-solid fa-map-pin"></i> Lokasi Pelaksanaan</p>
                                                        <h5 class="fs-15 mb-0" id="kegiatan-lokasi"></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary" onclick="enrollKegiatan()"><i
                                                        class="fa-solid fa-key"></i> Enroll ke
                                                    Kegiatan Ini</button>
                                            </div>
                                        </div>
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
            $('#kode_kegiatan').on('keypress', function(e) {
                if (e.which === 13) {
                    searchKegiatan();
                }
            });
        });

        function searchKegiatan() {
            var kodeKegiatan = $('#kode_kegiatan').val().trim();

            if (!kodeKegiatan) {
                $('#enrollment-result').hide();
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Kode kegiatan wajib diisi'
                });
                return;
            }

            $.ajax({
                url: "{{ route('diklat-enrollment.search') }}",
                type: 'GET',
                data: {
                    kode_kegiatan: kodeKegiatan
                },
                success: function(response) {
                    if (!response.status) {
                        $('#enrollment-result').hide();
                        iziToast.warning({
                            title: 'Perhatian',
                            message: response.msg || 'Kegiatan tidak ditemukan'
                        });
                        return;
                    }

                    $('#id_kegiatan').val(response.data.id);
                    $('#kegiatan-title').text(response.data.nama_kegiatan || '-');
                    $('#kegiatan-description').html(response.data.deskripsi || '-');
                    $('#kegiatan-periode').text(response.data.periode || '-');
                    $('#kegiatan-lokasi').text(response.data.lokasi_kegiatan || '-');
                    renderKegiatanBanner(response.data.banner_img || []);
                    $('#enrollment-result').show();
                },
                error: function(xhr) {
                    $('#enrollment-result').hide();

                    var message = 'Kegiatan tidak ditemukan';

                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        message = xhr.responseJSON.msg;
                    }

                    iziToast.warning({
                        title: 'Perhatian',
                        message: message
                    });
                }
            });
        }

        function renderKegiatanBanner(images) {
            var $wrap = $('#kegiatan-banner-wrap');

            if (!images || !images.length) {
                $wrap.empty();
                return;
            }

            if (images.length === 1) {
                $wrap.html(
                    '<div class="border border-dark overflow-hidden">' +
                    '<img src="' + "{{ asset('storage') }}/" + images[0] +
                    '" alt="Banner Kegiatan" class="img-fluid w-100">' +
                    '</div>'
                );
                return;
            }

            var carouselId = 'kegiatanBannerCarousel';
            var indicators = '';
            var items = '';
            var i;

            for (i = 0; i < images.length; i++) {
                indicators += '<button type="button" data-bs-target="#' + carouselId + '" data-bs-slide-to="' + i + '"' + (
                        i === 0 ? ' class="active" aria-current="true"' : '') + ' aria-label="Slide ' + (i + 1) +
                    '"></button>';
                items += '<div class="carousel-item' + (i === 0 ? ' active' : '') + '">' +
                    '<img src="' + "{{ asset('storage') }}/" + images[i] + '" class="d-block w-100" alt="Banner Kegiatan ' +
                    (i + 1) + '">' +
                    '</div>';
            }

            $wrap.html(
                '<div id="' + carouselId +
                '" class="carousel slide border border-dark overflow-hidden" data-bs-ride="carousel">' +
                '<div class="carousel-indicators">' + indicators + '</div>' +
                '<div class="carousel-inner">' + items + '</div>' +
                '<button class="carousel-control-prev" type="button" data-bs-target="#' + carouselId +
                '" data-bs-slide="prev">' +
                '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                '<span class="visually-hidden">Previous</span>' +
                '</button>' +
                '<button class="carousel-control-next" type="button" data-bs-target="#' + carouselId +
                '" data-bs-slide="next">' +
                '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
                '<span class="visually-hidden">Next</span>' +
                '</button>' +
                '</div>'
            );
        }

        function enrollKegiatan() {
            var id = $('#id_kegiatan').val();
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin ingin mendaftar ke kegiatan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('diklat-do-enroll') }}",
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === true) {
                                iziToast.success({
                                    title: 'Berhasil!',
                                    message: response.msg
                                });
                                setTimeout(function() {
                                    window.location.href = "{{ route('diklat') }}";
                                }, 1000);
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
                                message: xhr.responseJSON?.msg ?? 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
