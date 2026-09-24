<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Pulang Perlahan, tempat memahami Islam dengan cara yang dekat, ringan, dan bersumber jelas.">
    <title>Pulang Perlahan</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/pulang-perlahan-colored.png') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --green: #30473f;
            --deep: #22352f;
            --soft: #dce5df;
            --gold: #e8b85f;
            --gold-soft: #f5e6c4;
            --ink: #1e2c27;
            --muted: #5e6b66;
            --paper: #fbfaf6;
            --line: #d9dfdb;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 84px;
        }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.7;
        }

        h1,
        h2,
        h3,
        .brand {
            color: var(--deep);
            font-family: Georgia, "Times New Roman", serif;
            letter-spacing: -.025em;
        }

        h1 {
            font-size: clamp(2.7rem, 6vw, 5.6rem);
            line-height: 1.02;
        }

        h2 {
            font-size: clamp(2rem, 4vw, 3.65rem);
            line-height: 1.12;
        }

        h3 {
            font-size: 1.5rem;
        }

        a:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }

        .wrap {
            width: min(1120px, calc(100% - 32px));
            margin-inline: auto;
        }

        .section {
            padding: 104px 0;
        }

        .label {
            display: block;
            margin-bottom: 18px;
            color: var(--green);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .copy {
            max-width: 680px;
            color: var(--muted);
            font-size: 1.06rem;
        }

        .navbar-pp {
            position: sticky;
            z-index: 10;
            top: 0;
            background: rgba(251, 250, 246, .72);
            border-bottom: 1px solid rgba(255, 255, 255, .72);
            box-shadow: 0 8px 32px rgba(34, 53, 47, .08);
            backdrop-filter: blur(18px) saturate(150%);
            -webkit-backdrop-filter: blur(18px) saturate(150%);
        }

        .nav-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--deep);
            font-size: 1.45rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            background: var(--green) url('{{ asset('assets/images/pulang-perlahan-colored.png') }}') center 27% / 88px auto no-repeat;
            border-radius: 50%;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .25);
        }

        .nav-links {
            display: flex;
            gap: 28px;
            margin-left: auto;
        }

        .nav-links a {
            color: var(--ink);
            font-size: .92rem;
            font-weight: 650;
            text-decoration: none;
        }

        .nav-links a:hover,
        .text-link:hover {
            color: var(--green);
        }

        .btn-pp {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 750;
            text-decoration: none;
            transition: transform 160ms ease, background 160ms ease, color 160ms ease;
        }

        .btn-pp:hover {
            transform: translateY(-2px);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--deep);
        }

        .btn-gold:hover {
            background: #f0c979;
            color: var(--deep);
        }

        .btn-light {
            border-color: rgba(255, 255, 255, .55);
            color: #fff;
        }

        .btn-light:hover {
            background: #fff;
            color: var(--deep);
        }

        .btn-outline {
            border-color: var(--green);
            color: var(--green);
        }

        .btn-outline:hover {
            background: var(--green);
            color: #fff;
        }

        .hero {
            position: relative;
            overflow: hidden;
            background: var(--green);
            color: #fff;
        }

        .hero::before,
        .hero::after {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 42% 58% 65% 35% / 38% 42% 58% 62%;
            content: "";
            filter: blur(24px);
            opacity: .18;
            pointer-events: none;
            animation: liquid 13s ease-in-out infinite alternate;
        }

        .hero::before {
            top: -180px;
            right: 9%;
            background: var(--gold);
        }

        .hero::after {
            bottom: -250px;
            left: 28%;
            background: #b9d6ca;
            animation-delay: -6s;
        }

        .hero-grid {
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 76px);
            display: grid;
            grid-template-columns: 1.25fr .75fr;
            align-items: center;
            gap: 56px;
            padding: 72px 0;
        }

        .hero-grid>div {
            animation: rise-in 700ms ease both;
        }

        .hero h1,
        .hero .label {
            color: #fff;
        }

        .hero-copy {
            max-width: 700px;
            color: #e3e9e5;
            font-size: clamp(1.05rem, 1.6vw, 1.22rem);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 34px;
        }

        .micro {
            margin-top: 24px;
            color: #d1dbd5;
            font-size: .9rem;
        }

        .logo-glass {
            position: relative;
            display: grid;
            place-items: center;
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, .26);
            border-radius: 44% 56% 48% 52% / 52% 45% 55% 48%;
            background: linear-gradient(145deg, rgba(255, 255, 255, .16), rgba(255, 255, 255, .04));
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .28), 0 24px 70px rgba(13, 28, 23, .26);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
            animation: logo-float 6s ease-in-out infinite;
        }

        .hero-logo {
            width: min(100%, 440px);
            aspect-ratio: 1;
            object-fit: contain;
            border-radius: 40%;
        }

        .two-col {
            display: grid;
            grid-template-columns: .8fr 1.2fr;
            gap: 88px;
            align-items: start;
        }

        .question {
            margin-top: 28px;
            color: var(--deep);
            font: 1.55rem Georgia, serif;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tag {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            background: rgba(255, 255, 255, .7);
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(34, 53, 47, .07);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: var(--ink);
            font-weight: 650;
            text-decoration: none;
            transition: transform 160ms ease, background 160ms ease;
        }

        .tag:hover {
            background: var(--soft);
            border-color: var(--green);
            color: var(--deep);
        }

        .text-link {
            display: inline-block;
            margin-top: 28px;
            color: var(--green);
            font-weight: 750;
            text-underline-offset: 5px;
        }

        .white {
            background: #fff;
            border-block: 1px solid var(--line);
        }

        .section-head {
            max-width: 720px;
            margin-bottom: 56px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--line);
            border: 1px solid var(--line);
        }

        .content-item {
            min-height: 330px;
            display: flex;
            flex-direction: column;
            padding: 38px;
            background: #fff;
            transition: transform 220ms ease, box-shadow 220ms ease;
        }

        .content-item:hover {
            z-index: 1;
            transform: translateY(-8px);
            box-shadow: 0 22px 54px rgba(34, 53, 47, .14);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            margin-top: 38px;
            border-radius: 16px;
            background: var(--soft);
            color: var(--green);
            font-size: 1.5rem;
        }

        .feature-icon+h3 {
            margin-top: 18px;
        }

        .content-item:nth-child(2) {
            background: var(--soft);
        }

        .content-item h3 {
            margin: 54px 0 14px;
        }

        .content-item p {
            color: var(--muted);
        }

        .content-item .text-link {
            margin-top: auto;
        }

        .deep {
            background: var(--deep);
            color: #fff;
        }

        .deep h2,
        .deep h3,
        .deep .label {
            color: #fff;
        }

        .deep-intro {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 72px;
            margin-bottom: 72px;
        }

        .deep-intro p,
        .flow p {
            color: #d6dfda;
        }

        .flow {
            border-top: 1px solid rgba(255, 255, 255, .22);
        }

        .flow-row {
            display: grid;
            grid-template-columns: 72px .55fr 1fr;
            gap: 24px;
            padding: 25px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .22);
        }

        .flow-row span {
            color: var(--gold);
            font-weight: 750;
        }

        .flow-row p {
            margin: 0;
        }

        .principle {
            margin: 56px 0 0;
            padding-left: 22px;
            border-left: 4px solid var(--gold);
            font: clamp(1.4rem, 3vw, 2.2rem) Georgia, serif;
        }

        .topics {
            display: grid;
            grid-template-columns: .85fr 1.15fr;
            gap: 72px;
            align-items: center;
        }

        .topic-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-top: 1px solid var(--line);
        }

        .topic-list a {
            min-height: 58px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--line);
            color: var(--ink);
            font-weight: 650;
            text-decoration: none;
        }

        .topic-list a:nth-child(odd) {
            margin-right: 24px;
        }

        .beginner {
            background: var(--gold-soft);
        }

        .beginner-inner {
            max-width: 850px;
        }

        .beginner-lines {
            margin: 36px 0;
            color: #44514c;
            font-size: 1.12rem;
        }

        .beginner-lines p {
            margin-bottom: 8px;
        }

        .mantra {
            color: var(--deep);
            font-weight: 800;
        }

        .personal {
            display: grid;
            grid-template-columns: 1fr .82fr;
            gap: 80px;
            align-items: center;
        }

        .feature-list {
            margin-top: 40px;
            border-top: 1px solid var(--line);
        }

        .feature {
            padding: 24px 0;
            border-bottom: 1px solid var(--line);
        }

        .feature strong {
            display: block;
            color: var(--deep);
            font: 1.25rem Georgia, serif;
        }

        .panel {
            padding: 42px;
            background: linear-gradient(145deg, rgba(255, 255, 255, .75), rgba(220, 229, 223, .72));
            border: 1px solid rgba(255, 255, 255, .88);
            border-radius: 20px;
            box-shadow: 0 22px 60px rgba(34, 53, 47, .12);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
        }

        .panel p {
            color: var(--muted);
        }

        .note {
            font-size: .88rem;
        }

        .trust {
            display: grid;
            grid-template-columns: .85fr 1.15fr;
            gap: 88px;
        }

        .highlight {
            padding: 34px;
            background: #fff;
            border: 1px solid var(--line);
            color: var(--deep);
            font: clamp(1.35rem, 2.5vw, 2rem)/1.35 Georgia, serif;
        }

        .closing {
            background: var(--green);
            color: #fff;
            text-align: center;
        }

        .closing h2,
        .closing .label {
            color: #fff;
        }

        .closing-inner {
            max-width: 760px;
            margin-inline: auto;
        }

        .closing .actions {
            justify-content: center;
        }

        .footer {
            padding: 72px 0 28px;
            background: #192822;
            color: #c9d2cd;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr repeat(3, .6fr);
            gap: 56px;
        }

        .footer .brand,
        .footer h3 {
            color: #fff;
        }

        .footer h3 {
            margin-bottom: 18px;
            font-family: inherit;
            font-size: .9rem;
            letter-spacing: .06em;
        }

        .footer-links {
            display: grid;
            gap: 10px;
        }

        .footer-links a {
            color: #c9d2cd;
            text-decoration: none;
        }

        .footer-links a:hover {
            color: var(--gold);
        }

        .footer-bottom {
            margin-top: 64px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, .16);
            font-size: .86rem;
        }

        .icon-title {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .icon-title i {
            color: var(--gold);
            font-size: 1.25rem;
        }

        @keyframes rise-in {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes logo-float {

            0%,
            100% {
                transform: translateY(0) rotate(-1deg);
            }

            50% {
                transform: translateY(-12px) rotate(1deg);
            }
        }

        @keyframes liquid {
            to {
                transform: translate(35px, 24px) rotate(18deg);
                border-radius: 58% 42% 38% 62% / 55% 62% 38% 45%;
            }
        }

        @media (max-width: 992px) {
            .section {
                padding: 80px 0;
            }

            .nav-links {
                display: none;
            }

            .nav-inner .btn-pp {
                margin-left: auto;
            }

            .hero-grid,
            .two-col,
            .deep-intro,
            .topics,
            .personal,
            .trust {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .hero-grid {
                min-height: auto;
            }

            .hero-logo {
                width: min(72vw, 420px);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .wrap {
                width: calc(100% - 24px);
            }

            .section {
                padding: 64px 0;
            }

            .brand {
                font-size: 1.15rem;
            }

            .nav-inner {
                gap: 12px;
            }

            .nav-inner .btn-pp {
                min-height: 44px;
                padding-inline: 14px;
            }

            .hero-grid {
                padding: 58px 0;
            }

            .actions .btn-pp {
                width: 100%;
            }

            .content-item,
            .panel,
            .highlight {
                padding: 28px;
            }

            .flow-row {
                grid-template-columns: 46px 1fr;
            }

            .flow-row p {
                grid-column: 2;
            }

            .topic-list,
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .topic-list a:nth-child(odd) {
                margin-right: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
    <header class="navbar-pp">
        <div class="wrap nav-inner">
            <a class="brand" href="#beranda"><span class="brand-mark" aria-hidden="true"></span>Pulang Perlahan</a>
            <nav class="nav-links" aria-label="Navigasi utama">
                <a href="#situasi">Situasi</a><a href="#belajar">Belajar</a><a href="#topik">Topik</a><a
                    href="#sumber">Sumber</a>
            </nav>
            <a class="btn-pp btn-outline" href="{{ route('login') }}"><i class="ri-user-line"
                    aria-hidden="true"></i>Masuk</a>
        </div>
    </header>
    <main>
        <section class="hero" id="beranda">
            <div class="wrap hero-grid">
                <div>
                    <span class="label">A space to learn, reflect, and grow.</span>
                    <h1>Pelan-pelan, nggak harus langsung sempurna.</h1>
                    <p class="hero-copy mt-4">Kenal Islam lebih dekat lewat ayat, hadits, dan pembahasan yang dibuat
                        lebih mudah dipahami tanpa terasa seperti sedang duduk di kelas.</p>
                    <p class="hero-copy">Belajar dari sumbernya, pahami konteksnya, lalu bawa maknanya ke kehidupan
                        sehari-hari.</p>
                    <div class="actions"><a class="btn-pp btn-gold" href="#situasi"><i class="ri-heart-3-line"
                                aria-hidden="true"></i>Mulai dari yang kamu rasakan</a><a class="btn-pp btn-light"
                            href="#belajar"><i class="ri-compass-3-line" aria-hidden="true"></i>Explore Islam</a></div>
                    <p class="micro">Faith, at your own pace.</p>
                </div>
                <div class="logo-glass"><img class="hero-logo"
                        src="{{ asset('assets/images/pulang-perlahan-colored.png') }}" alt="Logo Pulang Perlahan"></div>
            </div>
        </section>
        <section class="section" id="situasi">
            <div class="wrap two-col">
                <div>
                    <span class="label">Start where you are</span>
                    <h2>Lagi ngerasain apa?</h2>
                    <p class="copy mt-4">Kadang kita nggak datang dengan pertanyaan tentang fiqih, tafsir, atau nama
                        sebuah surah. Kadang pertanyaannya jauh lebih sederhana.</p>
                    <p class="question">“Kenapa aku ngerasa begini?”</p>
                    <p class="copy mt-4">Mulai dari apa yang sedang kamu alami, lalu temukan ayat, hadits, dan
                        pembahasan yang relevan untuk menemanimu memahami keadaan itu.</p>
                </div>
                <div>
                    <div class="tags">
                        @foreach (['Lagi overthinking', 'Takut sama masa depan', 'Ngerasa jauh dari Allah', 'Messed up again', 'Lagi patah hati', 'Ngerasa sendirian', 'Lagi kehilangan arah', 'Pengen mulai berubah', 'Pengen belajar bersyukur', 'Pengen mulai sedekah'] as $situation)
                            <a class="tag" href="#belajar"><i class="ri-sparkling-line"
                                    aria-hidden="true"></i>{{ $situation }}</a>
                        @endforeach
                    </div>
                    <a class="text-link" href="#topik">Lihat semua situasi</a>
                </div>
            </div>
        </section>
        <section class="section white" id="belajar">
            <div class="wrap">
                <div class="section-head">
                    <span class="label">Learn without feeling overwhelmed</span>
                    <h2>Islam, explained for real life.</h2>
                    <p class="copy mt-4">Nggak cuma menampilkan dalil lalu selesai. Pulang Perlahan membantu kamu
                        melihat sumbernya, memahami artinya, dan menangkap kenapa hal itu relevan dengan kehidupanmu.
                    </p>
                </div>
                <div class="content-grid">
                    <article class="content-item"><span>01</span><i class="ri-book-open-line feature-icon"
                            aria-hidden="true"></i>
                        <h3>Qur’an</h3>
                        <p>Baca ayat dan terjemahannya, lalu pahami pesan yang dibawanya dengan bahasa yang lebih
                            approachable.</p><a class="text-link" href="#cara-memahami">Explore Qur’an</a>
                    </article>
                    <article class="content-item"><span>02</span><i class="ri-double-quotes-l feature-icon"
                            aria-hidden="true"></i>
                        <h3>Hadith</h3>
                        <p>Kenalan dengan hadits dan pesan Rasulullah ﷺ melalui referensi yang jelas dan penjelasan yang
                            lebih mudah diikuti.</p><a class="text-link" href="#cara-memahami">Explore Hadith</a>
                    </article>
                    <article class="content-item"><span>03</span><i class="ri-road-map-line feature-icon"
                            aria-hidden="true"></i>
                        <h3>Guidance</h3>
                        <p>Pembahasan tentang iman, ibadah, hubungan, kehidupan, dan berbagai hal yang mungkin sedang
                            kamu hadapi.</p><a class="text-link" href="#topik">Explore Guidance</a>
                    </article>
                </div>
            </div>
        </section>
        <section class="section deep" id="cara-memahami">
            <div class="wrap">
                <div class="deep-intro">
                    <div><span class="label">Read beyond the translation</span>
                        <h2>Bukan cuma baca. Coba pahami.</h2>
                    </div>
                    <p>Satu ayat atau hadits bisa terasa berbeda ketika kita mulai memahami konteks dan pesannya. Karena
                        itu, konten di Pulang Perlahan dirancang untuk membawa kamu melalui beberapa lapisan pemahaman.
                    </p>
                </div>
                <div class="flow">
                    @foreach ([['The Source', 'Mulai dari ayat, hadits, atau sumber Islam yang menjadi dasarnya.'], ['The Meaning', 'Baca terjemahan yang jelas dan memiliki sumber.'], ['Make it connect', 'Pahami maksudnya dengan bahasa yang lebih dekat dengan keseharian.'], ['Why this hits different', 'Berhenti sebentar dan lihat bagaimana pesan itu bisa relate dengan hidup kita.'], ['Take a small step', 'Nggak harus berubah semuanya hari ini. Mulai dari satu langkah kecil yang bisa dilakukan.']] as $i => $step)
                        <div class="flow-row"><span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $step[0] }}</h3>
                            <p>{{ $step[1] }}</p>
                        </div>
                    @endforeach
                </div>
                <blockquote class="principle">Bahasanya santai. Sources-nya nggak santai.</blockquote>
            </div>
        </section>
        <section class="section" id="topik">
            <div class="wrap topics">
                <div><span class="label">Learn what matters to you</span>
                    <h2>Mau belajar tentang apa?</h2>
                    <p class="copy mt-4">Kalau kamu datang karena memang ingin belajar, explore Islam berdasarkan topik
                        yang ingin kamu pahami lebih dalam.</p><a class="text-link" href="#mulai">Explore all
                        topics</a>
                </div>
                <div class="topic-list">
                    @foreach (['Iman', 'Taubat', 'Shalat', 'Sedekah', 'Sabar', 'Syukur', 'Doa', 'Relationships', 'Self-growth', 'Rezeki', 'Akhlak', 'Kehidupan sehari-hari'] as $topic)
                        <a href="#mulai">{{ $topic }}</a>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="section beginner">
            <div class="wrap beginner-inner">
                <h2>Kamu nggak harus tahu semuanya untuk mulai.</h2>
                <div class="beginner-lines">
                    <p>Mungkin kamu baru mulai belajar.</p>
                    <p>Mungkin banyak istilah Islam yang masih terasa asing.</p>
                    <p>Mungkin kamu pernah mencoba berubah, lalu berhenti lagi.</p>
                    <p>Atau mungkin kamu cuma penasaran dan ingin mengenal Islam lebih dekat.</p>
                </div>
                <p class="copy">That’s okay. Pulang Perlahan dibuat sebagai tempat untuk belajar tanpa harus
                    berpura-pura sudah tahu semuanya.</p>
                <p class="mantra mt-4">Start small. Stay curious. Keep going.</p>
            </div>
        </section>
        <section class="section">
            <div class="wrap personal">
                <div><span class="label">Make it personal</span>
                    <h2>Ada yang kena banget? Simpan.</h2>
                    <p class="copy mt-4">Beberapa ayat, hadits, atau pembahasan mungkin datang di waktu yang tepat.
                        Login untuk menyimpan konten yang ingin kamu baca lagi dan menulis reflection pribadi untuk
                        dirimu sendiri.</p>
                    <div class="feature-list">
                        <div class="feature"><strong class="icon-title"><i class="ri-bookmark-line"
                                    aria-hidden="true"></i>Save</strong>Simpan ayat, hadits, dan guidance yang ingin
                            kamu kembali baca.</div>
                        <div class="feature"><strong class="icon-title"><i class="ri-quill-pen-line"
                                    aria-hidden="true"></i>Reflect</strong>Tulis apa yang kamu rasakan atau pelajari
                            tanpa harus membagikannya ke siapa pun.</div>
                    </div>
                </div>
                <aside class="panel">
                    <h3 class="icon-title"><i class="ri-user-heart-line" aria-hidden="true"></i>Ruang untuk prosesmu
                        sendiri.</h3>
                    <p class="mt-3">Buat akun untuk menyimpan bacaan dan reflection dalam satu tempat.</p><a
                        class="btn-pp btn-gold mt-3" href="{{ route('signup') }}"><i class="ri-add-circle-line"
                            aria-hidden="true"></i>Create your space</a>
                    <p class="note mt-3">Membaca tetap bisa dilakukan tanpa login.</p>
                </aside>
            </div>
        </section>
        <section class="section white" id="sumber">
            <div class="wrap trust">
                <div><span class="label">Sources matter</span>
                    <h2>Relatable bukan berarti asal ngomong.</h2>
                </div>
                <div>
                    <p class="copy">Kami ingin pembahasannya terasa dekat tanpa mengaburkan mana sumber asli, mana
                        terjemahan, dan mana penjelasan.</p>
                    <p class="copy">Ayat tetap ayat. Hadits tetap hadits. Terjemahan tetap diberi sumber. Penjelasan
                        dibuat sebagai penjelasan, bukan menggantikan teks aslinya.</p>
                    <p class="copy">Kalau ada perbedaan pendapat dalam suatu pembahasan, konteks tersebut seharusnya
                        dijelaskan, bukan disembunyikan.</p>
                    <p class="highlight mt-5">Understand the source. Respect the context. Then make it connect.</p>
                </div>
            </div>
        </section>
        <section class="section closing" id="mulai">
            <div class="wrap closing-inner"><span class="label">You can start from anywhere.</span>
                <h2>Nggak perlu buru-buru. Yang penting mulai.</h2>
                <p class="mt-4">Satu ayat. Satu hadits. Satu hal baru yang kamu pahami hari ini.<br>Maybe that’s
                    enough for now.</p>
                <div class="actions"><a class="btn-pp btn-gold" href="#situasi"><i class="ri-footprint-line"
                            aria-hidden="true"></i>Mulai perlahan</a><a class="btn-pp btn-light" href="#belajar"><i
                            class="ri-book-open-line" aria-hidden="true"></i>Explore Qur’an</a></div>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="wrap">
            <div class="footer-grid">
                <div><a class="brand" href="#beranda"><span class="brand-mark" aria-hidden="true"></span>Pulang
                        Perlahan</a>
                    <p class="mt-3 mb-1">Faith, at your own pace.</p>
                    <p>Tempat untuk memahami dan mengenal Islam, one step at a time.</p>
                </div>
                <div>
                    <h3>Explore</h3>
                    <div class="footer-links"><a href="#belajar">Qur’an</a><a href="#belajar">Hadith</a><a
                            href="#topik">Guidance</a><a href="#topik">Topics</a><a href="#situasi">Situations</a>
                    </div>
                </div>
                <div>
                    <h3>Pulang Perlahan</h3>
                    <div class="footer-links"><a href="#beranda">About</a><a href="#sumber">Sources</a><a
                            href="#sumber">Disclaimer</a></div>
                </div>
                <div>
                    <h3>Account</h3>
                    <div class="footer-links"><a href="{{ route('login') }}">Saved</a><a
                            href="{{ route('login') }}">Reflections</a><a href="{{ route('login') }}">Login</a>
                    </div>
                </div>
            </div>
            <p class="footer-bottom mb-0">Pulang Perlahan. Learn. Understand. Reflect. Grow.</p>
        </div>
    </footer>
</body>

</html>
