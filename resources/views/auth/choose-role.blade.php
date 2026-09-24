<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Pilih peran untuk melanjutkan di Pulang Perlahan.">
    <title>Pilih Peran | Pulang Perlahan</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/pulang-perlahan-colored.png') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
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
        a { color: var(--green); }
        a:hover { color: var(--dark-green); }
        .role-layout, .role-visual, .role-content { min-height: 100vh; min-height: 100dvh; }
        .role-visual {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: clamp(2rem, 5vw, 4.5rem);
            color: #fff;
            background: var(--green) url("{{ asset('assets/images/login-mosque.jpg') }}") center / cover no-repeat;
            isolation: isolate;
        }
        .role-visual::before {
            position: absolute;
            z-index: -1;
            inset: 0;
            background: linear-gradient(180deg, rgba(36, 48, 43, .75), rgba(36, 48, 43, .32) 42%, rgba(36, 48, 43, .9));
            content: "";
        }
        .visual-brand { display: inline-flex; align-items: center; gap: .85rem; color: #fff; text-decoration: none; }
        .visual-brand:hover, .photo-credit a { color: #fff; }
        .visual-brand img { width: 54px; height: 54px; border-radius: 50%; }
        .visual-brand span { font: 700 1.3rem Georgia, "Times New Roman", serif; }
        .visual-copy { max-width: 560px; }
        .visual-copy h1 {
            margin: 0 0 1.25rem;
            color: #fff;
            font: 700 clamp(2.4rem, 4.2vw, 4.5rem)/1.08 Georgia, "Times New Roman", serif;
            letter-spacing: -.035em;
        }
        .visual-copy p { max-width: 440px; margin: 0; color: #fff; font-size: 1.05rem; line-height: 1.7; }
        .visual-copy .photo-credit { margin-top: 2rem; font-size: .75rem; }
        .role-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem clamp(1.5rem, 6vw, 6rem);
        }
        .role-form { width: 100%; max-width: 430px; margin: auto; }
        .mobile-brand { display: none; }
        .eyebrow { margin-bottom: .85rem; color: var(--green); font-size: .78rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .eyebrow::before { display: inline-block; width: 26px; height: 2px; margin-right: .6rem; background: var(--yellow); content: ""; vertical-align: middle; }
        .role-form h2 { margin-bottom: .6rem; color: var(--green); font: 700 clamp(2.2rem, 3vw, 3rem)/1.1 Georgia, "Times New Roman", serif; }
        .intro { margin-bottom: 2rem; color: var(--muted); line-height: 1.6; }
        .form-label { margin-bottom: .5rem; font-weight: 600; }
        .form-select {
            min-height: 52px;
            padding: .8rem 2.5rem .8rem 1rem;
            color: var(--ink);
            background-color: #fff;
            border: 1px solid #87938c;
            border-radius: .6rem;
        }
        .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 .22rem rgba(230, 182, 102, .35); }
        .btn-brand {
            min-height: 52px;
            color: #fff;
            background: var(--green);
            border: 1px solid var(--green);
            border-radius: 999px;
            font-weight: 700;
        }
        .btn-brand:hover { color: #fff; background: var(--dark-green); border-color: var(--dark-green); }
        .logout-link { margin: 2rem 0 0; text-align: center; }
        .logout-link a { font-weight: 700; text-underline-offset: 3px; }
        .role-footer { padding-top: 2rem; color: var(--muted); font-size: .82rem; text-align: center; }
        :focus-visible { outline: 3px solid var(--yellow); outline-offset: 3px; }
        @media (max-width: 991.98px) {
            .role-visual { display: none; }
            .role-content { padding: 2rem 1.5rem; }
            .mobile-brand {
                display: inline-flex;
                align-items: center;
                gap: .65rem;
                margin-bottom: 3rem;
                color: var(--green);
                font: 700 1.15rem Georgia, "Times New Roman", serif;
                text-decoration: none;
            }
            .mobile-brand img { width: 44px; height: 44px; border-radius: 50%; }
        }
    </style>
</head>
<body>
    <main class="container-fluid p-0">
        <div class="row g-0 role-layout">
            <div class="col-lg-6 role-visual">
                <a class="visual-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan">
                    <span>Pulang Perlahan</span>
                </a>
                <div class="visual-copy">
                    <h1>Faith, at your own pace.</h1>
                    <p>Kenal Islam lebih dekat lewat ayat, hadits, dan pembahasan yang dibuat mudah dipahami.</p>
                    <p class="photo-credit">Foto: <a href="https://unsplash.com/photos/u-96fWorEz4" target="_blank" rel="noopener noreferrer">Jimmy Woo / Unsplash</a></p>
                </div>
            </div>
            <div class="col-lg-6 role-content">
                <div class="role-form">
                    <a class="mobile-brand" href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan">
                        <span>Pulang Perlahan</span>
                    </a>
                    <div class="eyebrow">Pulang Perlahan</div>
                    <h2>Pilih peran</h2>
                    <p class="intro">Pilih peran yang ingin digunakan untuk melanjutkan.</p>

                    @if (session('success'))
                        <div class="alert alert-success" role="status">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">{!! session('error') !!}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('choose-role-set') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="role" class="form-label">Peran</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih peran</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['slug_name'] }}" {{ old('role') === $role['slug_name'] ? 'selected' : '' }}>{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-brand w-100 mt-2">Lanjutkan</button>
                    </form>
                    <p class="logout-link"><a href="{{ route('logout') }}">Keluar dari akun</a></p>
                </div>
                <div class="role-footer">&copy; {{ date('Y') }} Pulang Perlahan</div>
            </div>
        </div>
    </main>
</body>
</html>
