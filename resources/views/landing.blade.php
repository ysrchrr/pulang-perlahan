<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>SIM Kegiatan & Penugasan BGTK Jambi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="SIM Kegiatan & Penugasan BGTK Jambi" name="phicosdev" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/tut-wuri-handayani.png') }}">

    <!--Swiper slider css-->
    <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        <nav class="navbar navbar-expand-lg navbar-landing navbar-light fixed-top" id="navbar">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <img src="{{ asset('assets/images/logo-kemendikdasmen.png') }}" class="card-logo card-logo-dark"
                        alt="logo dark" height="35">
                    <img src="{{ asset('assets/images/logo-kemendikdasmen.png') }}" class="card-logo card-logo-light"
                        alt="logo light" height="35">
                </a>
                <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                        <li class="nav-item">
                            <a class="nav-link active" href="#hero">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#wallet">Fitur</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#categories">Role</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#creators">Alur Sistem</a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
        <div class="bg-overlay bg-overlay-pattern"></div>
        <!-- end navbar -->

        <!-- start hero section -->
        <section class="section nft-hero" id="hero">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-sm-10">
                        <div class="text-center">
                            <h1 class="display-4 fw-medium mb-4 lh-base text-white">Sistem Informasi Kegiatan &
                                Penugasan<br> <strong>BGTK
                                    Jambi</strong></h1>
                            <p class="text-white-50 fs-16 mb-4">
                                Pengelolaan kegiatan, penugasan pegawai, perjadin, dan pemetaan kompetensi dalam satu
                                sistem terintegrasi
                            </p>

                            <div class="hstack gap-2 justify-content-center">
                                <a href="#wallet" class="btn btn-primary">Lihat Fitur</a>
                                <a href="{{ route('login') }}" class="btn btn-danger">Masuk <i
                                        class="ri-arrow-right-line align-middle ms-1"></i></a>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('auth-google-petakom') }}" class="btn btn-outline-light">Instrumen
                                    Petakom</a>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end hero section -->

        <!-- start wallet -->
        <section class="section" id="wallet">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h2 class="mb-3 fw-semibold lh-base">Ruang Lingkup Utama Sistem</h2>
                            <p class="text-muted">Konten halaman ini mengacu ke pembahasan awal
                            </p>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card text-center border shadow-none material-shadow">
                            <div class="card-body py-5 px-4">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="55"
                                    class="mb-3 pb-2">
                                <h5>Kepegawaian dan Penugasan</h5>
                                <p class="text-muted pb-1">Kelola tim kerja, data pegawai, dokumen pegawai,
                                    verifikasi penugasan, draft surat tugas, dan SK kegiatan</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-lg-4">
                        <div class="card text-center border shadow-none material-shadow">
                            <div class="card-body py-5 px-4">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="55"
                                    class="mb-3 pb-2">
                                <h5>Pengelolaan Kegiatan</h5>
                                <p class="text-muted pb-1">Kelola biodata peserta, presensi, evaluasi, kelas,
                                    foto kegiatan, panduan, laporan, dan sertifikat</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-4">
                        <div class="card text-center border shadow-none material-shadow">
                            <div class="card-body py-5 px-4">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="55"
                                    class="mb-3 pb-2">
                                <h5>Perjadin dan Kompetensi</h5>
                                <p class="text-muted pb-1">Sediakan form laporan keuangan perjadin, SPD,
                                    pembuatan instrumen, dan pengisian pemetaan kompetensi oleh guru</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end wallet -->


        <!-- start features -->
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h2 class="mb-3 fw-semibold lh-base">Fitur Kunci yang Dibutuhkan</h2>
                            <p class="text-muted">Empat blok besar ini jadi fondasi implementasi awal sistem.</p>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-lg-3">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                    class="avatar-sm">
                                <h5 class="mt-4">Tim Kerja dan Pegawai</h5>
                                <p class="text-muted fs-14">Daftar tim kerja, profil pegawai, keaktifan, dan arsip
                                    dokumen pegawai.</p>
                                <a href="#categories" class="link-success fs-14">Detail Role <i
                                        class="ri-arrow-right-line align-bottom"></i></a>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-lg-3">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                    class="avatar-sm">
                                <h5 class="mt-4">Administrasi Kegiatan</h5>
                                <p class="text-muted fs-14">Draft surat tugas, template panduan, laporan, SK, dan
                                    sertifikat kegiatan.</p>
                                <a href="#creators" class="link-success fs-14">Lihat Alur <i
                                        class="ri-arrow-right-line align-bottom"></i></a>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-lg-3">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                    class="avatar-sm">
                                <h5 class="mt-4">Presensi dan Evaluasi</h5>
                                <p class="text-muted fs-14">Form biodata, presensi peserta, panitia, narasumber,
                                    serta evaluasi penyelenggaraan.</p>
                                <a href="{{ route('login') }}" class="link-success fs-14">Masuk Sistem <i
                                        class="ri-arrow-right-line align-bottom"></i></a>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-lg-3">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                    class="avatar-sm">
                                <h5 class="mt-4">Perjadin dan Instrumen</h5>
                                <p class="text-muted fs-14">Laporan keuangan perjadin, SPD, dan instrumen pemetaan
                                    kompetensi guru.</p>
                                <a href="{{ route('login') }}" class="link-success fs-14">Akses Aplikasi <i
                                        class="ri-arrow-right-line align-bottom"></i></a>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!-- end container -->
        </section><!-- end features -->

        <!-- start plan -->
        <section class="section bg-light" id="categories">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="text-center mb-5">
                            <h2 class="mb-3 fw-semibold lh-base">Role Sistem</h2>
                            <p class="text-muted">Konsep role diambil dari dokumen diskusi awal pengembangan aplikasi.
                            </p>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Swiper -->
                        <div class="swiper mySwiper pb-4">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-1 mb-3">
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mt-1">
                                                </div><!--end col-->
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mb-1">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                </div><!--end col-->
                                            </div><!--end row-->
                                            <a href="{{ route('login') }}" class="float-end"> Masuk <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                            <h5 class="mb-0 fs-16"><a href="#!">Admin BGTK <span
                                                        class="badge bg-success-subtle text-success">RBAC</span></a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-1 mb-3">
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mt-1">
                                                </div><!--end col-->
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mb-1">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                </div><!--end col-->
                                            </div><!--end row-->
                                            <a href="{{ route('login') }}" class="float-end"> Masuk <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                            <h5 class="mb-0 fs-16"><a href="#!">Kepegawaian <span
                                                        class="badge bg-success-subtle text-success">Data
                                                        Pegawai</span></a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-1 mb-3">
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mt-1">
                                                </div><!--end col-->
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mb-1">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                </div><!--end col-->
                                            </div><!--end row-->
                                            <a href="{{ route('login') }}" class="float-end"> Masuk <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                            <h5 class="mb-0 fs-16"><a href="#!">Admin Tim Kerja <span
                                                        class="badge bg-success-subtle text-success">Plotting</span></a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-1 mb-3">
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mt-1">
                                                </div><!--end col-->
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mb-1">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                </div><!--end col-->
                                            </div><!--end row-->
                                            <a href="{{ route('login') }}" class="float-end"> Masuk <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                            <h5 class="mb-0 fs-16"><a href="#!">Panitia <span
                                                        class="badge bg-success-subtle text-success">Kelas</span></a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-1 mb-3">
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mt-1">
                                                </div><!--end col-->
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded mb-1">
                                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}"
                                                        alt="" class="img-fluid rounded">
                                                </div><!--end col-->
                                            </div><!--end row-->
                                            <a href="{{ route('login') }}" class="float-end"> Masuk <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                            <h5 class="mb-0 fs-16"><a href="#!">Pegawai dan Peserta <span
                                                        class="badge bg-success-subtle text-success">Layanan</span></a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination swiper-pagination-dark"></div>
                        </div>
                    </div>
                </div>
            </div><!-- end container -->
        </section>
        <!-- end plan -->

        <!-- start Discover Items-->
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center mb-5">
                            <h2 class="mb-0 fw-semibold lh-base flex-grow-1">Layanan dan Dokumen Utama</h2>
                            <a href="{{ route('login') }}" class="btn btn-primary">Masuk <i
                                    class="ri-arrow-right-line align-bottom"></i></a>
                        </div>
                    </div>
                </div><!-- end row -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card explore-box card-animate border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                        class="avatar-xs rounded-circle">
                                    <div class="ms-2 flex-grow-1">
                                        <a href="#!">
                                            <h6 class="mb-0 fs-15">Dokumen Kegiatan</h6>
                                        </a>
                                        <p class="mb-0 text-muted fs-13">Template dan Generate</p>
                                    </div>
                                    <div class="bookmark-icon">
                                        <button type="button" class="btn btn-icon active" data-bs-toggle="button"
                                            aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                    </div>
                                </div>
                                <div class="explore-place-bid-img overflow-hidden rounded">
                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}" alt=""
                                        class="explore-img w-100">
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{ route('login') }}" class="btn btn-success"><i
                                                class="ri-arrow-right-line align-bottom me-1"></i> Akses</a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="fw-medium mb-0 float-end">Surat Tugas</p>
                                    <h5 class="text-success">SK Kegiatan</h5>
                                    <h6 class="fs-16 mb-0"><a href="#!">Panduan, laporan, sertifikat, dan surat
                                            keterangan</a></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card explore-box card-animate border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                        class="avatar-xs rounded-circle">
                                    <div class="ms-2 flex-grow-1">
                                        <a href="#!">
                                            <h6 class="mb-0 fs-15">Pelaksanaan Kegiatan</h6>
                                        </a>
                                        <p class="mb-0 text-muted fs-13">Panitia dan Peserta</p>
                                    </div>
                                    <div class="bookmark-icon">
                                        <button type="button" class="btn btn-icon" data-bs-toggle="button"
                                            aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                    </div>
                                </div>
                                <div class="explore-place-bid-img overflow-hidden rounded">
                                    <img src="{{ asset('assets/images/profile-bg.jpg') }}" alt=""
                                        class="explore-img w-100">
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{ route('login') }}" class="btn btn-success"><i
                                                class="ri-arrow-right-line align-bottom me-1"></i> Akses</a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="fw-medium mb-0 float-end">Presensi</p>
                                    <h5 class="text-success">Evaluasi</h5>
                                    <h6 class="fs-16 mb-0"><a href="#!">Biodata peserta, data kelas, unggah foto,
                                            dan unduh kelengkapan kelas</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card explore-box card-animate border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                        class="avatar-xs rounded-circle">
                                    <div class="ms-2 flex-grow-1">
                                        <a href="#!">
                                            <h6 class="mb-0 fs-15">Perjadin dan Kompetensi</h6>
                                        </a>
                                        <p class="mb-0 text-muted fs-13">Pegawai dan Guru</p>
                                    </div>
                                    <div class="bookmark-icon">
                                        <button type="button" class="btn btn-icon active" data-bs-toggle="button"
                                            aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>
                                    </div>
                                </div>
                                <div class="explore-place-bid-img overflow-hidden rounded">
                                    <img src="{{ asset('assets/images/auth-one-bg.jpg') }}" alt=""
                                        class="img-fluid explore-img">
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{ route('login') }}" class="btn btn-success"><i
                                                class="ri-arrow-right-line align-bottom me-1"></i> Akses</a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="fw-medium mb-0 float-end">SPD</p>
                                    <h5 class="text-success">Instrumen</h5>
                                    <h6 class="fs-16 mb-0"><a href="#!">Laporan perjadin pegawai, form instrumen,
                                            dan pengisian melalui link</a></h6>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section>
        <!--end Discover Items-->

        <!-- start Work Process -->
        <section class="section bg-light" id="creators">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h2 class="mb-3 fw-semibold lh-base">Alur Kerja Sistem</h2>
                            <p class="text-muted">Ringkasan alur proses yang tercermin dari kebutuhan awal aplikasi.
                            </p>
                        </div>
                    </div>
                </div><!-- end row -->
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shink-0">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                            class="avatar-sm object-fit-cover rounded" />
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5 class="mb-1">1. Setup Kegiatan</h5>
                                        </a>
                                        <p class="text-muted mb-0">Admin tim kerja membuat data kegiatan dan kebutuhan
                                            penugasan.</p>
                                    </div>
                                    <div>
                                        <div class="dropdown float-end">
                                            <button class="btn btn-ghost-primary btn-icon dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle fs-16"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn"
                                                        href="javascript:void(0);">Kegiatan</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-xl-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shink-0">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                            class="avatar-sm object-fit-cover rounded">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5 class="mb-1">2. Plotting Pegawai</h5>
                                        </a>
                                        <p class="text-muted mb-0">Pegawai dipilih sesuai kuota dan kebutuhan lintas
                                            tim kerja.</p>
                                    </div>
                                    <div>
                                        <div class="dropdown float-end">
                                            <button class="btn btn-ghost-primary btn-icon dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle fs-16"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn"
                                                        href="javascript:void(0);">Plotting</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-xl-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shink-0">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                            class="avatar-sm object-fit-cover rounded">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5 class="mb-1">3. Verifikasi</h5>
                                        </a>
                                        <p class="text-muted mb-0">Kepegawaian memverifikasi penugasan yang sudah
                                            lengkap.</p>
                                    </div>
                                    <div>
                                        <div class="dropdown float-end">
                                            <button class="btn btn-ghost-primary btn-icon dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle fs-16"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn"
                                                        href="javascript:void(0);">Verifikasi</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-xl-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shink-0">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                            class="avatar-sm object-fit-cover rounded">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5 class="mb-1">4. Pelaksanaan</h5>
                                        </a>
                                        <p class="text-muted mb-0">Panitia kelola kelas, presensi, biodata, evaluasi,
                                            dan dokumentasi kegiatan.</p>
                                    </div>
                                    <div>
                                        <div class="dropdown float-end">
                                            <button class="btn btn-ghost-primary btn-icon dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle fs-16"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn"
                                                        href="javascript:void(0);">Pelaksanaan</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-xl-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shink-0">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                            class="avatar-sm object-fit-cover rounded">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5 class="mb-1">5. Output Dokumen</h5>
                                        </a>
                                        <p class="text-muted mb-0">Sistem menghasilkan sertifikat, panduan, laporan,
                                            dan dokumen kegiatan lainnya.</p>
                                    </div>
                                    <div>
                                        <div class="dropdown float-end">
                                            <button class="btn btn-ghost-primary btn-icon dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle fs-16"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn"
                                                        href="javascript:void(0);">Dokumen</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-xl-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shink-0">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                            class="avatar-sm object-fit-cover rounded">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <a href="#!">
                                            <h5 class="mb-1">6. Layanan Mandiri</h5>
                                        </a>
                                        <p class="text-muted mb-0">Pegawai input perjadin, peserta mendaftar kegiatan,
                                            guru mengisi instrumen melalui link.</p>
                                    </div>
                                    <div>
                                        <div class="dropdown float-end">
                                            <button class="btn btn-ghost-primary btn-icon dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle fs-16"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn"
                                                        href="javascript:void(0);">Layanan</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div><!-- end container -->
        </section><!-- end Work Process -->

        <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-white mb-0 fw-semibold">SIM Kegiatan & Penugasan BGTK Jambi untuk proses
                                kerja yang lebih
                                terpusat</h4>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <div>
                            <a href="{{ route('login') }}" class="btn bg-gradient btn-danger">Masuk Sistem</a>
                            <a href="#wallet" class="btn bg-gradient btn-info">Lihat Fitur</a>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <!-- Start footer -->
        <footer class="custom-footer bg-dark py-5 position-relative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mt-4">
                        <div>
                            <div>
                                <img src="{{ asset('assets/images/logo-kemendikdasmen.png') }}" alt="logo light"
                                    height="55">
                            </div>
                            <div class="mt-4">
                                <p>Balai Besar Guru Tenaga Kependidikan Jambi</p>
                                <p>Jl. Koni No.43, Rengas Condong, Kec. Muara Bulian, Kabupaten Batang Hari, Jambi</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 ms-lg-auto">
                        <div class="row">
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Modul</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list">
                                        <li><a href="#wallet">Kepegawaian</a></li>
                                        <li><a href="#wallet">Penugasan</a></li>
                                        <li><a href="#wallet">Kegiatan</a></li>
                                        <li><a href="#wallet">Perjadin</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Role</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list">
                                        <li><a href="#categories">Admin BGTK</a></li>
                                        <li><a href="#categories">Kepegawaian</a></li>
                                        <li><a href="#categories">Admin Tim Kerja</a></li>
                                        <li><a href="#categories">Panitia</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Akses</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list">
                                        <li><a href="{{ route('login') }}">Masuk</a></li>
                                        <li><a href="#creators">Alur Sistem</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row text-center text-sm-start align-items-center mt-5">
                    <div class="col-sm-6">

                        <div>
                            <p class="copy-rights mb-0">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © SIM Kegiatan & Penugasan BGTK Jambi
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end mt-3 mt-sm-0">
                            <ul class="list-inline mb-0 footer-social-link">
                                <li class="list-inline-item">
                                    <a href="{{ route('login') }}" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-login-box-line"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#wallet" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-file-list-3-line"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#categories" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-team-line"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#creators" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-route-line"></i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->


    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/nft-landing.init.js') }}"></script>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>

</html>
