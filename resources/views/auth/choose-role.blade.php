<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Pilih Role | SIM BGTK Jambi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
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
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8 col-sm-10">
                        <div class="card overflow-hidden card-bg-fill galaxy-border-none rounded-5">
                            <div class="row g-0">
                                <div class="col-lg-12">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary">Pilih Role</h5>
                                            <p class="text-muted">Silakan pilih peran untuk melanjutkan</p>
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

                                            <form id="formAuthentication" class="mb-6"
                                                action="{{ route('choose-role-set') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Pilih Role</label>
                                                    <select name="role" id="role" class="form-select" required>
                                                        <option value="" selected disabled>-- Pilih Role --
                                                        </option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role['slug_name'] }}">
                                                                {{ $role['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-success w-100 text-white">
                                                    Pilih Role
                                                </button>
                                            </form>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="text-center">
                                                <a class="fw-semibold text-danger"
                                                    href="{{ route('logout') }}">Keluar</a>
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

        <!-- footer -->
        <footer class="footer galaxy-border-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-white">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> SIM Pengaduan UNS
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
