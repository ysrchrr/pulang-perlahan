<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Masuk ke Pulang Perlahan untuk melanjutkan perjalanan belajar Islam.">
    <title>Masuk | Pulang Perlahan</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/pulang-perlahan-colored.png') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --green: #34443d;
            --dark-green: #293730;
            --yellow: #e6b666;
            --paper: #fbfaf7;
            --ink: #24302b;
            --muted: #59645e;
        }

        body {
            min-width: 320px;
            margin: 0;
            color: var(--ink);
            background: var(--paper);
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        a {
            color: var(--green);
        }

        a:hover {
            color: var(--dark-green);
        }

        .login-layout,
        .login-visual,
        .login-content {
            min-height: 100vh;
            min-height: 100dvh;
        }

        .login-visual {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: clamp(2rem, 5vw, 4.5rem);
            color: #fff;
            background: var(--green) url("{{ asset('assets/images/login-mosque.jpg') }}") center / cover no-repeat;
            isolation: isolate;
        }

        .login-visual::before {
            position: absolute;
            z-index: -1;
            inset: 0;
            background: linear-gradient(180deg, rgba(36, 48, 43, .75), rgba(36, 48, 43, .32) 42%, rgba(36, 48, 43, .9));
            content: "";
        }

        .visual-brand {
            display: inline-flex;
            align-items: center;
            gap: .85rem;
            color: #fff;
            text-decoration: none;
        }

        .visual-brand:hover,
        .photo-credit a {
            color: #fff;
        }

        .visual-brand img {
            width: 54px;
            height: 54px;
            border-radius: 50%;
        }

        .visual-brand span {
            font: 700 1.3rem Georgia, "Times New Roman", serif;
        }

        .visual-copy {
            max-width: 560px;
        }

        .visual-copy h1 {
            margin: 0 0 1.25rem;
            color: #fff;
            font: 700 clamp(2.4rem, 4.2vw, 4.5rem)/1.08 Georgia, "Times New Roman", serif;
            letter-spacing: -.035em;
        }

        .visual-copy p {
            max-width: 440px;
            margin: 0;
            color: #fff;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .visual-copy .photo-credit {
            margin-top: 2rem;
            font-size: .75rem;
        }

        .login-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem clamp(1.5rem, 6vw, 6rem);
        }

        .login-form {
            width: 100%;
            max-width: 430px;
            margin: auto;
        }

        .mobile-brand {
            display: none;
        }

        .eyebrow {
            margin-bottom: .85rem;
            color: var(--green);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            display: inline-block;
            width: 26px;
            height: 2px;
            margin-right: .6rem;
            background: var(--yellow);
            content: "";
            vertical-align: middle;
        }

        .login-form h2 {
            margin-bottom: .6rem;
            color: var(--green);
            font: 700 clamp(2.2rem, 3vw, 3rem)/1.1 Georgia, "Times New Roman", serif;
        }

        .intro {
            margin-bottom: 2rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .form-label {
            margin-bottom: .5rem;
            font-weight: 600;
        }

        .form-control {
            min-height: 52px;
            padding: .8rem 1rem;
            color: var(--ink);
            background: #fff;
            border: 1px solid #87938c;
            border-radius: .6rem;
        }

        .form-control:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 .22rem rgba(230, 182, 102, .35);
        }

        .password-toggle {
            position: absolute;
            top: 0;
            right: 0;
            width: 52px;
            height: 52px;
            color: var(--green);
            background: transparent;
            border: 0;
        }

        .btn-brand {
            min-height: 52px;
            color: #fff;
            background: var(--green);
            border: 1px solid var(--green);
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-brand:hover {
            color: #fff;
            background: var(--dark-green);
            border-color: var(--dark-green);
        }

        .btn-google {
            display: flex;
            min-height: 52px;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            color: var(--green);
            border: 1px solid var(--green);
            border-radius: 999px;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-google:hover {
            color: #fff;
            background: var(--green);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: var(--muted);
            font-size: .88rem;
        }

        .divider::before,
        .divider::after {
            height: 1px;
            flex: 1;
            background: #c7cec8;
            content: "";
        }

        .signup-link {
            margin: 2rem 0 0;
            text-align: center;
        }

        .signup-link a {
            font-weight: 700;
            text-underline-offset: 3px;
        }

        .login-footer {
            padding-top: 2rem;
            color: var(--muted);
            font-size: .82rem;
            text-align: center;
        }

        :focus-visible {
            outline: 3px solid var(--yellow);
            outline-offset: 3px;
        }

        @media (max-width: 991.98px) {
            .login-visual {
                display: none;
            }

            .login-content {
                padding: 2rem 1.5rem;
            }

            .mobile-brand {
                display: inline-flex;
                align-items: center;
                gap: .65rem;
                margin-bottom: 3rem;
                color: var(--green);
                font: 700 1.15rem Georgia, "Times New Roman", serif;
                text-decoration: none;
            }

            .mobile-brand img {
                width: 44px;
                height: 44px;
                border-radius: 50%;
            }
        }
    </style>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body>
    <main class="container-fluid p-0">
        <div class="row g-0 login-layout">
            <div class="col-lg-6 login-visual">
                <a class="visual-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan">
                    <span>Pulang Perlahan</span>
                </a>
                <div class="visual-copy">
                    <h1>Faith, at your own pace.</h1>
                    <p>Kenal Islam lebih dekat lewat ayat, hadits, dan pembahasan yang dibuat mudah dipahami.</p>
                    <p class="photo-credit">Foto: <a href="https://unsplash.com/photos/u-96fWorEz4" target="_blank"
                            rel="noopener noreferrer">Jimmy Woo / Unsplash</a></p>
                </div>
            </div>
            <div class="col-lg-6 login-content">
                <div class="login-form">
                    <a class="mobile-brand" href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan">
                        <span>Pulang Perlahan</span>
                    </a>
                    <div class="eyebrow">Pulang Perlahan</div>
                    <h2>Selamat datang kembali</h2>
                    <p class="intro">Masuk untuk melanjutkan perjalanan belajarmu.</p>
                    @if (session('success'))
                        <div class="alert alert-success" role="status">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">{!! session('error') !!}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                    @endif
                    <form action="{{ route('doLogin') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Username atau email</label>
                            <input type="text" class="form-control" id="email" name="email_username"
                                value="{{ old('email_username') }}" autocomplete="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password-input" class="form-label">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control pe-5" id="password-input" name="password"
                                    autocomplete="current-password" required>
                                <button class="password-toggle" type="button" id="password-addon"
                                    aria-label="Tampilkan password" aria-pressed="false"><i class="ri-eye-line"
                                        aria-hidden="true"></i></button>
                            </div>
                        </div>
                        @if (config('services.turnstile.site_key'))
                            <div class="mb-3 d-flex justify-content-center">
                                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"
                                    data-theme="light"></div>
                            </div>
                        @endif
                        <button class="btn btn-brand w-100 mt-2" type="submit">Masuk</button>
                    </form>
                    <div class="divider">atau masuk dengan</div>
                    <a class="btn-google" href="{{ route('auth-google') }}"><i class="ri-google-fill"
                            aria-hidden="true"></i> Akun Google</a>
                    <p class="signup-link">Belum memiliki akun? <a href="{{ route('signup') }}">Daftar sekarang</a></p>
                </div>
                <div class="login-footer">&copy; {{ date('Y') }} Pulang Perlahan</div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('password-addon').addEventListener('click', function() {
            var input = document.getElementById('password-input');
            var visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            this.setAttribute('aria-pressed', visible ? 'true' : 'false');
            this.setAttribute('aria-label', visible ? 'Sembunyikan password' : 'Tampilkan password');
            this.querySelector('i').className = visible ? 'ri-eye-off-line' : 'ri-eye-line';
        });
    </script>
</body>

</html>
