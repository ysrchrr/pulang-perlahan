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
            font-family: "DejaVu Serif", serif;
            position: relative;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            object-fit: cover;
        }

        .nama-peserta {
            position: absolute;
            top: 110mm;
            left: 0;
            width: 297mm;
            text-align: center;
            font-size: 30pt;
            font-weight: bold;
            letter-spacing: 1px;
            color: #000;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <img class="background" src="{{ $bg_path }}" alt="Background Sertifikat">
    <div class="nama-peserta">{{ $nama_peserta }}</div>
</body>

</html>
