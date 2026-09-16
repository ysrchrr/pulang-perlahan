<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>{{ $page_title ?? 'Dashboard' }} | SIM Kegiatan & Penugasan BGTK Jambi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="SIM Kegiatan & Penugasan BGTK Jambi" name="phicosdev" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/tut-wuri-handayani.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.components.css')
    @stack('styles')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.components.header')
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/tut-wuri-handayani.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-kemendikdasmen.png') }}" alt="" height="30">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/tut-wuri-handayani.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-kemendikdasmen.png') }}" alt="" height="30">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu">
                    </div>
                    @if (session('program') == 'simdiklat')
                        @if (session('role_id') == 1)
                            @include('layouts.components.sidebar-superadmin')
                        @else
                            @include('layouts.components.sidebar-roles')
                        @endif
                    @else
                        @if (session('role_id') == 1)
                            @include('layouts.components.sidebar-petakom-superadmin')
                        @else
                            @include('layouts.components.sidebar-petakom-roles')
                        @endif
                    @endif
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('contents')
                </div>
            </div>

            @include('layouts.components.footer')
        </div>
    </div>

    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info rounded-pill shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
            data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    <!-- Theme Settings -->
    @include('layouts.components.theme-settings')

    <!-- JAVASCRIPT -->
    @include('layouts.components.js')
    @stack('scripts')
</body>

</html>
