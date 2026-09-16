<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        html,
        body {
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111111;
        }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
        }

        .content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
        }

        .title {
            position: absolute;
            top: 30mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 26pt;
            font-weight: 700;
            letter-spacing: 4px;
        }

        .certificate-number {
            position: absolute;
            top: 45mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12pt;
        }

        .intro-text {
            position: absolute;
            top: 55mm;
            left: 32mm;
            width: 233mm;
            text-align: center;
            font-size: 13pt;
        }

        .recipient-name {
            position: absolute;
            top: 70mm;
            left: 22mm;
            width: 253mm;
            text-align: center;
            font-size: 28pt;
            font-weight: 700;
            color: #0e4f8a;
        }

        .unit-kerja {
            position: absolute;
            top: 90mm;
            left: 32mm;
            width: 233mm;
            text-align: center;
            font-size: 13pt;
        }

        .description {
            position: absolute;
            top: 109mm;
            left: 32mm;
            width: 233mm;
            text-align: center;
            font-size: 12pt;
            line-height: 1.7;
        }

        .sign-location {
            position: absolute;
            top: 138mm;
            right: 34mm;
            width: 80mm;
            text-align: center;
            font-size: 10.5pt;
        }

        .cap-image {
            position: absolute;
            top: 146mm;
            right: 72mm;
            width: 32mm;
            height: 32mm;
            object-fit: contain;
            opacity: 0.35;
        }

        .signature-image {
            position: absolute;
            top: 149mm;
            right: 48mm;
            width: 48mm;
            height: 22mm;
            object-fit: contain;
        }

        .sign-title {
            position: absolute;
            top: 144mm;
            right: 34mm;
            width: 80mm;
            text-align: center;
            font-size: 10.5pt;
            line-height: 1.4;
        }

        .sign-name {
            position: absolute;
            top: 171mm;
            right: 34mm;
            width: 80mm;
            text-align: center;
            font-size: 12pt;
            font-weight: 700;
            text-decoration: underline;
        }

        .sign-meta {
            position: absolute;
            top: 178mm;
            right: 34mm;
            width: 80mm;
            text-align: center;
            font-size: 10pt;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    @foreach ($items as $item)
        <div class="page">
            <img class="background" src="{{ $bg_path }}" alt="Background Sertifikat">

            <div class="content">
                <div class="title">SERTIFIKAT</div>
                <div class="certificate-number">
                    Nomor : {{ $item->nomor_sertifikat ?: '-' }}
                </div>
                <div class="intro-text">Balai Guru Tenaga Kependidikan Provinsi Jambi menyatakan bahwa :</div>
                <div class="recipient-name">{{ strtoupper($item->nama) }}</div>
                <div class="unit-kerja">{{ $item->unit_kerja ?: '-' }}</div>
                <div class="description">
                    sebagai Peserta kegiatan <strong>{{ $item->nama_kegiatan }}</strong>
                    yang diselenggarakan pada
                    <strong>{{ dateRangeIndo($item->tanggal_mulai, $item->tanggal_selesai) ?: '-' }}</strong>
                    @if ($setting->is_cetak_kelas == '1' && !empty($item->nama_kelas))
                        dalam kelas <strong>{{ $item->nama_kelas }}</strong>
                    @endif di {{ $item->lokasi_kegiatan ?? '-' }}
                </div>
                <div class="sign-location">
                    {{ $setting->kota_pelaksanaan }}, {{ $tanggal_penandatangan_formatted }}
                </div>
                <div class="sign-title">
                    {{ $setting->penandatangan->jabatan ?: '-' }}
                </div>

                @if ($cap_path)
                    <img class="cap-image" src="{{ $cap_path }}" alt="Cap">
                @endif

                @if ($signature_path)
                    <img class="signature-image" src="{{ $signature_path }}" alt="Tanda Tangan">
                @endif

                <div class="sign-name">{{ $setting->penandatangan->nama_penandatangan }}</div>
                <div class="sign-meta">
                    NIP. {{ $setting->penandatangan->nip ?: '-' }}
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
