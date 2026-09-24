<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Memahami Islam dengan cara yang dekat, ringan, dan tetap bersumber jelas.">
    <title>Pulang Perlahan — Faith, at your own pace.</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/pulang-perlahan-colored.png') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --green: #34443d;
            --dark-green: #293730;
            --yellow: #e6b666;
            --soft-yellow: #f8edda;
            --paper: #fbfaf7;
            --ink: #24302b;
            --muted: #68716d;
            --line: #dfe3df;
        }
        html { scroll-behavior: smooth; }
        body {
            color: var(--ink);
            background: var(--paper);
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.7;
            overflow-x: hidden;
        }
        h1, h2, h3, .brand {
            color: var(--green);
            font-family: Georgia, "Times New Roman", serif;
        }
        h1 {
            max-width: 780px;
            font-size: clamp(2.7rem, 6vw, 5.5rem);
            line-height: 1.03;
            letter-spacing: -.045em;
        }
        h2 {
            font-size: clamp(2rem, 4vw, 3.6rem);
            line-height: 1.12;
            letter-spacing: -.035em;
        }
        .navbar {
            margin: .75rem auto 0;
            background: rgba(255, 255, 255, .62);
            border: 1px solid rgba(255, 255, 255, .8);
            border-radius: 1.25rem;
            box-shadow: 0 12px 35px rgba(52, 68, 61, .1), inset 0 1px 0 rgba(255, 255, 255, .85);
            backdrop-filter: blur(20px) saturate(145%);
            -webkit-backdrop-filter: blur(20px) saturate(145%);
            transition: margin .3s ease, border-radius .3s ease, box-shadow .3s ease;
        }
        .navbar.scrolled {
            margin-top: 0;
            border-radius: 0 0 1.25rem 1.25rem;
            box-shadow: 0 8px 28px rgba(52, 68, 61, .14);
        }
        .navbar-brand { display: flex; gap: .75rem; align-items: center; }
        .navbar-brand img { width: 44px; height: 44px; border-radius: 50%; }
        .brand { font-size: 1.2rem; font-weight: 700; }
        .nav-link { color: var(--green) !important; font-size: .92rem; font-weight: 600; }
        .btn { border-radius: 999px; padding: .72rem 1.25rem; font-weight: 700; }
        .btn-brand, .btn-brand:hover, .btn-brand:focus {
            color: #fff;
            background: var(--green);
            border-color: var(--green);
        }
        .btn-brand:hover { background: var(--dark-green); }
        .btn-outline-brand { color: var(--green); border-color: var(--green); }
        .btn-outline-brand:hover { color: #fff; background: var(--green); }
        .hero { min-height: 94vh; padding: 10rem 0 6rem; overflow: hidden; }
        .hero-copy, .section-lead {
            max-width: 690px;
            color: var(--muted);
            font-size: 1.06rem;
        }
        .hero-art { position: relative; z-index: 0; max-width: 440px; margin: auto; }
        .hero-art::before {
            position: absolute;
            inset: 8% -7% -7% 10%;
            z-index: -1;
            background: var(--yellow);
            border-radius: 48% 52% 45% 55%;
            content: "";
            opacity: .3;
            transform: rotate(-7deg);
        }
        .hero-art img {
            width: 100%;
            border-radius: 48% 48% 2rem 2rem;
            box-shadow: 0 30px 70px rgba(52, 68, 61, .2);
            animation: float 6s ease-in-out infinite;
        }
        .eyebrow {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--green);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .eyebrow::before {
            display: inline-block;
            width: 28px;
            height: 2px;
            margin-right: .65rem;
            background: var(--yellow);
            content: "";
            vertical-align: middle;
        }
        .section { padding: 6.5rem 0; }
        .section-soft { background: #f1f3ef; }
        .section-dark { color: rgba(255, 255, 255, .78); background: var(--green); }
        .section-dark h2, .section-dark h3, .section-dark .eyebrow { color: #fff; }
        .chips { display: flex; flex-wrap: wrap; gap: .75rem; }
        .chips a {
            padding: .65rem 1rem;
            color: var(--green);
            background: rgba(255, 255, 255, .58);
            border: 1px solid rgba(255, 255, 255, .88);
            border-radius: 999px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .9), 0 8px 24px rgba(52, 68, 61, .06);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            font-weight: 600;
            text-decoration: none;
            transition: .2s ease;
        }
        .chips a:hover {
            background: var(--soft-yellow);
            border-color: var(--yellow);
            transform: translateY(-2px);
        }
        .content-card {
            height: 100%;
            padding: 2rem;
            background: rgba(255, 255, 255, .64);
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 1.25rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .95), 0 18px 45px rgba(52, 68, 61, .08);
            backdrop-filter: blur(18px) saturate(130%);
            -webkit-backdrop-filter: blur(18px) saturate(130%);
            transition: transform .35s ease, box-shadow .35s ease;
        }
        .content-card:hover {
            box-shadow: inset 0 1px 0 #fff, 0 24px 55px rgba(52, 68, 61, .14);
            transform: translateY(-6px);
        }
        .content-card .number, .flow-number {
            color: var(--yellow);
            font-size: .82rem;
            font-weight: 800;
            letter-spacing: .1em;
        }
        .content-card h3 { margin: 1.2rem 0 .75rem; font-size: 1.8rem; }
        .text-link {
            color: var(--green);
            font-weight: 800;
            text-decoration-color: var(--yellow);
            text-underline-offset: .25rem;
        }
        .flow-item { padding: 1.4rem 0; border-bottom: 1px solid rgba(255, 255, 255, .16); }
        .flow-item:last-child { border: 0; }
        .flow-item h3 {
            margin: .3rem 0;
            font-family: inherit;
            font-size: 1.12rem;
            font-weight: 750;
        }
        .principle {
            padding: 2rem;
            color: var(--green);
            background: var(--yellow);
            border-radius: 1.25rem;
            font: 700 clamp(1.5rem, 3vw, 2.2rem)/1.3 Georgia, serif;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .4), 0 20px 50px rgba(22, 30, 26, .2);
        }
        .feature-icon {
            display: grid;
            width: 48px;
            height: 48px;
            color: var(--green);
            background: var(--yellow);
            border-radius: 50%;
            font-size: 1.25rem;
            place-items: center;
        }
        .trust-highlight {
            color: var(--green);
            font: 700 clamp(1.4rem, 3vw, 2.1rem)/1.35 Georgia, serif;
        }
        .closing { padding: 7rem 0; text-align: center; }
        .closing h2, .closing p { max-width: 700px; margin-inline: auto; }
        footer {
            padding: 4.5rem 0 2rem;
            color: rgba(255, 255, 255, .68);
            background: var(--dark-green);
        }
        footer h3, footer a { color: #fff; }
        footer a { text-decoration: none; }
        footer li { margin-bottom: .45rem; }
        .footer-logo { width: 76px; height: 76px; border-radius: 50%; }
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .75s ease, transform .75s cubic-bezier(.22, 1, .36, 1);
        }
        .reveal.is-visible { opacity: 1; transform: none; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-1deg); }
            50% { transform: translateY(-16px) rotate(1deg); }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation: none !important; transition: none !important; }
            .reveal { opacity: 1; transform: none; }
        }
        @media (max-width: 991.98px) {
            .navbar { margin: .5rem; }
            .navbar.scrolled { margin: 0; }
            .navbar-collapse { padding: 1rem 0; }
            .hero { padding-top: 8rem; text-align: center; }
            .hero h1, .hero-copy { margin-inline: auto; }
            .hero-art { max-width: 310px; margin-top: 3.5rem; }
            .section { padding: 5rem 0; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan">
                <span class="brand">Pulang Perlahan</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Buka navigasi">
                <i class="ri-menu-line fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="#situations">Situations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#explore">Explore</a></li>
                    <li class="nav-item"><a class="nav-link" href="#topics">Topics</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sources">Sources</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-brand btn-sm" href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero d-flex align-items-center" id="home">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <span class="eyebrow">A space to learn, reflect, and grow.</span>
                        <h1>Pelan-pelan, nggak harus langsung sempurna.</h1>
                        <p class="hero-copy mt-4">Kenal Islam lebih dekat lewat ayat, hadits, dan pembahasan yang dibuat
                            lebih mudah dipahami — tanpa terasa seperti sedang duduk di kelas.</p>
                        <p class="hero-copy">Belajar dari sumbernya, pahami konteksnya, lalu bawa maknanya ke kehidupan
                            sehari-hari.</p>
                        <div class="d-flex flex-wrap gap-2 mt-4 justify-content-center justify-content-lg-start">
                            <a href="#situations" class="btn btn-brand">Mulai dari yang kamu rasakan</a>
                            <a href="#explore" class="btn btn-outline-brand">Explore Islam</a>
                        </div>
                        <p class="small mt-4 mb-0 text-muted">Faith, at your own pace.</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="hero-art">
                            <img src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Pulang Perlahan">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-soft" id="situations">
            <div class="container">
                <div class="row g-5 align-items-end mb-5">
                    <div class="col-lg-7">
                        <span class="eyebrow">Start where you are</span>
                        <h2>Lagi ngerasain apa?</h2>
                    </div>
                    <div class="col-lg-5">
                        <p class="section-lead mb-0">Mulai dari apa yang sedang kamu alami, lalu temukan ayat, hadits,
                            dan pembahasan yang relevan untuk menemanimu memahami keadaan itu.</p>
                    </div>
                </div>
                <div class="chips">
                    @foreach (['Lagi overthinking', 'Takut sama masa depan', 'Ngerasa jauh dari Allah', 'Messed up again', 'Lagi patah hati', 'Ngerasa sendirian', 'Lagi kehilangan arah', 'Pengen mulai berubah', 'Pengen belajar bersyukur', 'Pengen mulai sedekah'] as $situation)
                        <a href="#explore">{{ $situation }}</a>
                    @endforeach
                </div>
                <a href="#explore" class="text-link d-inline-block mt-4">Lihat semua situasi <i class="ri-arrow-right-line"></i></a>
            </div>
        </section>

        <section class="section" id="explore">
            <div class="container">
                <span class="eyebrow">Learn without feeling overwhelmed</span>
                <h2>Islam, explained for real life.</h2>
                <p class="section-lead mt-3 mb-5">Nggak cuma menampilkan dalil lalu selesai. Lihat sumbernya, pahami
                    artinya, dan tangkap kenapa hal itu relevan dengan kehidupanmu.</p>
                <div class="row g-4">
                    @foreach ([
                        ['01 / SOURCE', "Qur'an", 'Baca ayat dan terjemahannya, lalu pahami pesannya dengan bahasa yang lebih approachable.', "Explore Qur'an"],
                        ['02 / SOURCE', 'Hadith', 'Kenalan dengan hadits dan pesan Rasulullah ﷺ melalui referensi yang jelas dan penjelasan yang mudah diikuti.', 'Explore Hadith'],
                        ['03 / LEARN', 'Guidance', 'Pembahasan tentang iman, ibadah, hubungan, kehidupan, dan berbagai hal yang mungkin sedang kamu hadapi.', 'Explore Guidance']
                    ] as $item)
                        <div class="col-md-4">
                            <article class="content-card">
                                <span class="number">{{ $item[0] }}</span>
                                <h3>{{ $item[1] }}</h3>
                                <p class="text-muted">{{ $item[2] }}</p>
                                <a href="#" class="text-link">{{ $item[3] }} <i class="ri-arrow-right-line"></i></a>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section section-dark">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-5">
                        <span class="eyebrow">Read beyond the translation</span>
                        <h2>Bukan cuma baca. Coba pahami.</h2>
                        <p class="mt-4">Satu ayat atau hadits bisa terasa berbeda ketika kita mulai memahami konteks dan pesannya.</p>
                        <div class="principle mt-5">“Bahasanya santai. Sources-nya nggak santai.”</div>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        @foreach ([
                            ['01', 'The Source', 'Mulai dari ayat, hadits, atau sumber Islam yang menjadi dasarnya.'],
                            ['02', 'The Meaning', 'Baca terjemahan yang jelas dan memiliki sumber.'],
                            ['03', 'Make it connect', 'Pahami maksudnya dengan bahasa yang lebih dekat dengan keseharian.'],
                            ['04', 'Why this hits different', 'Lihat bagaimana pesan itu bisa relate dengan hidup kita.'],
                            ['05', 'Take a small step', 'Mulai dari satu langkah kecil yang bisa dilakukan.']
                        ] as $step)
                            <div class="flow-item">
                                <span class="flow-number">{{ $step[0] }}</span>
                                <h3>{{ $step[1] }}</h3>
                                <p class="mb-0">{{ $step[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="topics">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-5">
                        <span class="eyebrow">Learn what matters to you</span>
                        <h2>Mau belajar tentang apa?</h2>
                        <p class="section-lead mt-3">Explore Islam berdasarkan topik yang ingin kamu pahami lebih dalam.</p>
                        <a href="#" class="text-link">Explore all topics <i class="ri-arrow-right-line"></i></a>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="chips">
                            @foreach (['Iman', 'Taubat', 'Shalat', 'Sedekah', 'Sabar', 'Syukur', 'Doa', 'Relationships', 'Self-growth', 'Rezeki', 'Akhlak', 'Kehidupan sehari-hari'] as $topic)
                                <a href="#">{{ $topic }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-soft">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <span class="eyebrow">Start small. Stay curious. Keep going.</span>
                        <h2>Kamu nggak harus tahu semuanya untuk mulai.</h2>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <p>Mungkin kamu baru mulai belajar.</p>
                        <p>Mungkin banyak istilah Islam yang masih terasa asing.</p>
                        <p>Mungkin kamu pernah mencoba berubah, lalu berhenti lagi.</p>
                        <p>Atau mungkin kamu cuma penasaran dan ingin mengenal Islam lebih dekat.</p>
                        <p class="fw-bold mt-4">That's okay.</p>
                        <p class="mb-0">Pulang Perlahan dibuat sebagai tempat untuk belajar tanpa harus berpura-pura sudah tahu semuanya.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <span class="eyebrow">Make it personal</span>
                        <h2>Ada yang kena banget? Simpan.</h2>
                        <p class="section-lead mt-3">Login untuk menyimpan konten yang ingin kamu baca lagi dan menulis
                            reflection pribadi untuk dirimu sendiri.</p>
                        <a href="{{ route('signup') }}" class="btn btn-brand mt-3">Create your space</a>
                        <p class="small text-muted mt-3">Membaca tetap bisa dilakukan tanpa login.</p>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        @foreach ([
                            ['ri-bookmark-line', 'Save', 'Simpan ayat, hadits, dan guidance yang ingin kamu kembali baca.'],
                            ['ri-quill-pen-line', 'Reflect', 'Tulis apa yang kamu rasakan atau pelajari tanpa harus membagikannya.']
                        ] as $feature)
                            <div class="content-card mb-3">
                                <span class="feature-icon"><i class="{{ $feature[0] }}"></i></span>
                                <h3>{{ $feature[1] }}</h3>
                                <p class="text-muted mb-0">{{ $feature[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-soft" id="sources">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-5">
                        <span class="eyebrow">Sources matter</span>
                        <h2>Relatable bukan berarti asal ngomong.</h2>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <p>Kami ingin pembahasannya terasa dekat tanpa mengaburkan mana sumber asli, mana terjemahan, dan mana penjelasan.</p>
                        <p>Ayat tetap ayat. Hadits tetap hadits. Terjemahan tetap diberi sumber. Penjelasan dibuat sebagai penjelasan — bukan menggantikan teks aslinya.</p>
                        <p>Kalau ada perbedaan pendapat, konteks tersebut seharusnya dijelaskan, bukan disembunyikan.</p>
                        <p class="trust-highlight mt-4 mb-0">Understand the source. Respect the context. Then make it connect.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="closing">
            <div class="container">
                <span class="eyebrow">You can start from anywhere.</span>
                <h2>Nggak perlu buru-buru. Yang penting mulai.</h2>
                <p class="section-lead mt-3">Satu ayat. Satu hadits. Satu hal baru yang kamu pahami hari ini.<br>Maybe that's enough for now.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="#situations" class="btn btn-brand">Mulai perlahan</a>
                    <a href="#explore" class="btn btn-outline-brand">Explore Qur'an</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <img class="footer-logo mb-3" src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan">
                    <h3 class="h4 mb-2">Pulang Perlahan</h3>
                    <p class="mb-1">Faith, at your own pace.</p>
                    <p>Tempat untuk memahami dan mengenal Islam, one step at a time.</p>
                </div>
                <div class="col-6 col-lg-2 offset-lg-1">
                    <h3 class="h6">Explore</h3>
                    <ul class="list-unstyled">
                        <li><a href="#explore">Qur'an</a></li><li><a href="#explore">Hadith</a></li>
                        <li><a href="#explore">Guidance</a></li><li><a href="#topics">Topics</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h3 class="h6">Pulang Perlahan</h3>
                    <ul class="list-unstyled">
                        <li><a href="#home">About</a></li><li><a href="#sources">Sources</a></li><li><a href="#sources">Disclaimer</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h3 class="h6">Account</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}">Saved</a></li><li><a href="{{ route('login') }}">Reflections</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <p class="small mb-0">Pulang Perlahan — Learn. Understand. Reflect. Grow.</p>
        </div>
    </footer>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var navbar = document.querySelector('.navbar');
            var sections = document.querySelectorAll('main section, footer .row');
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            sections.forEach(function (section) {
                section.classList.add('reveal');
                observer.observe(section);
            });

            function updateNavbar() {
                navbar.classList.toggle('scrolled', window.scrollY > 24);
            }

            updateNavbar();
            window.addEventListener('scroll', updateNavbar, { passive: true });
        });
    </script>
</body>
</html>
