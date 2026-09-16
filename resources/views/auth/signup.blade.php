<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Daftar | SIM Kegiatan & Penugasan BGTK Jambi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="SIM Kegiatan & Penugasan BGTK Jambi" name="phicosdev" />
    <meta content="BBGTK Jambi" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/tut-wuri-handayani.png') }}">

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

<body>

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden card-bg-fill galaxy-border-none rounded-5">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg bgtk-login-panel h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="#" class="d-block">
                                                    <img src="{{ asset('assets/images/logo-kemendikdasmen.png') }}"
                                                        alt="" height="55">
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="mb-4">
                                                    <div class="bgtk-auth-kicker">SIM Kegiatan & Penugasan BGTK Jambi
                                                    </div>
                                                    <h1 class="bgtk-auth-title">Layanan administrasi kegiatan &
                                                        kepegawaian</h1>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide"
                                                    data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button"
                                                            data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="0" class="active" aria-current="true"
                                                            aria-label="Slide 1"></button>
                                                        <button type="button"
                                                            data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button"
                                                            data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner bgtk-auth-copy pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15">Pengelolaan data tim kerja,
                                                                pegawai, penugasan, dan arsip kegiatan BGTK Provinsi
                                                                Jambi dalam satu sistem.</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15">Mendukung administrasi
                                                                kegiatan mulai dari biodata, presensi, evaluasi,
                                                                sertifikat, hingga laporan kegiatan.</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15">Terintegrasi untuk kebutuhan
                                                                perjadin pegawai dan instrumen pemetaan kompetensi guru.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary">Selamat Datang</h5>
                                            <p class="text-muted">Silakan isi form di bawah ini, untuk mendaftar sebagai
                                                peserta</p>
                                        </div>

                                        <div class="mt-4">
                                            @if (session('success'))
                                                <div class="alert alert-primary" role="alert">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

                                            @if (session('error'))
                                                <div class="alert alert-danger" role="alert">
                                                    {!! session('error') !!}
                                                </div>
                                            @endif
                                            <form action="{{ route('doSignup') }}" method="POST">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama Lengkap (Dengan
                                                        Gelar)</label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Masukkan nama lengkap" name="name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="text" class="form-control" id="email"
                                                        placeholder="Enter email" name="email_username">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="no_hp" class="form-label">Nomor Telepon</label>
                                                    <input type="text" class="form-control" id="no_hp"
                                                        placeholder="Masukkan nomor telepon" name="no_hp">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Password</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password"
                                                            class="form-control pe-5 password-input"
                                                            placeholder="Enter password" id="password-input"
                                                            name="password">
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                            type="button" id="password-addon"><i
                                                                class="ri-eye-fill align-middle"></i></button>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password-confirm-input">Konfirmasi
                                                        Password</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password"
                                                            class="form-control pe-5 password-input"
                                                            placeholder="Enter password" id="password-confirm-input"
                                                            name="password_confirmation">
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                            type="button" id="password-confirm-addon"><i
                                                                class="ri-eye-fill align-middle"></i></button>
                                                    </div>
                                                </div>

                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="auth-remember-check">
                                                    <label class="form-check-label" for="auth-remember-check">Remember
                                                        me</label>
                                                </div> --}}

                                                <div class="mt-4">
                                                    <button class="btn btn-success w-100"
                                                        type="submit">Daftar</button>
                                                </div>

                                                {{-- <div class="mt-4 text-center">
                                                    <div class="signin-other-title">
                                                        <h5 class="fs-13 mb-4 title">atau masuk dengan</h5>
                                                    </div>

                                                    <div>
                                                        <a href="{{ route('auth-google') }}">
                                                            <button type="button"
                                                                class="btn btn-danger waves-effect waves-light">
                                                                <i class="ri-google-fill"></i> Akun Google
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div> --}}

                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                                    class="fw-semibold text-primary text-decoration-underline">
                                                    Masuk</a> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <footer class="footer galaxy-border-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-white">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> SIM Kegiatan & Penugasan BGTK Jambi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    <!-- password-addon init -->
    <script src="{{ asset('assets/js/pages/password-addon.init.js') }}"></script>
</body>

</html>
