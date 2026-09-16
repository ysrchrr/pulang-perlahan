<?php

use Carbon\Carbon;

function formatRibuan($params)
{
    //Output: 1.000.000
    return number_format($params, 0, ',', '.');
}

function tglIndo($tanggal)
{
    //Mengubah 2024-01-01 menjadi 1 Januari 2024
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    if ($date) {
        return $date->format('j') . ' ' . $bulan[(int)$date->format('n')] . ' ' . $date->format('Y');
    }
    return false;
}

function dayIndo($tanggal)
{
    //Mengubah 2024-01-01 menjadi Senin
    $hari = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu'
    ];

    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    if ($date) {
        return $hari[(int)$date->format('w')];
    }
    return false;
}

function dateRangeIndo($tanggal_awal, $tanggal_akhir)
{
    $tanggalAwalFormatted = tglIndo($tanggal_awal);
    $tanggalAkhirFormatted = tglIndo($tanggal_akhir);

    if (!$tanggalAwalFormatted || !$tanggalAkhirFormatted) {
        return false;
    }

    if ($tanggal_awal === $tanggal_akhir) {
        return $tanggalAwalFormatted;
    }

    return $tanggalAwalFormatted . ' s/d ' . $tanggalAkhirFormatted;
}

function datetimeIndo($tanggalWaktu)
{
    //Mengubah 2024-01-01 18:00:00 menjadi 1 Januari 2024
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $tanggalWaktu);
    if ($dateTime) {
        return $dateTime->format('j') . ' ' . $bulan[(int)$dateTime->format('n')] . ' ' . $dateTime->format('Y');
    }
    return false;
}

function datetimeIndoFull($tanggalWaktu)
{
    //Mengubah 2025-09-24 17:00:00 menjadi 24 September 2025 17.00
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $tanggalWaktu);
    if ($dateTime) {
        $tgl = $dateTime->format('j');
        $bln = $bulan[(int)$dateTime->format('n')];
        $thn = $dateTime->format('Y');
        $jam = $dateTime->format('H');
        $menit = $dateTime->format('i');

        return "$tgl $bln $thn $jam.$menit";
    }
    return false;
}


function getSalamWaktu(): string
{
    // Ambil jam saat ini
    $jam = Carbon::now()->format('H');

    if ($jam >= 5 && $jam < 11) {
        return 'Selamat pagi';
    } elseif ($jam >= 11 && $jam < 15) {
        return 'Selamat siang';
    } elseif ($jam >= 15 && $jam < 18) {
        return 'Selamat sore';
    } else {
        return 'Selamat malam';
    }
}

function break_long_location(string $location, int $breakAfter = 3): string
{
    $words = explode(' ', $location);

    if (count($words) <= $breakAfter) {
        return $location;
    }

    // Sisipkan <br> setelah kata ke-$breakAfter
    $firstPart = implode(' ', array_slice($words, 0, $breakAfter));
    $secondPart = implode(' ', array_slice($words, $breakAfter));

    return $firstPart . '<br>' . $secondPart;
}

function formatPhone($no_hp)
{
    // Hilangkan spasi, strip, atau tanda plus di awal
    $no_hp = preg_replace('/[^0-9]/', '', $no_hp);

    // Jika diawali 0, ganti jadi 62
    if (substr($no_hp, 0, 1) === '0') {
        $no_hp = '62' . substr($no_hp, 1);
    }

    return $no_hp;
}

function getBulan($param)
{
    $bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    // Jika hanya satu bulan (tanpa koma)
    if (!str_contains($param, ',')) {
        return $bulan[$param] ?? 'Bulan tidak valid';
    }

    // Jika lebih dari satu bulan
    $list = explode(',', $param);
    $result = [];

    foreach ($list as $b) {
        $b = str_pad($b, 2, '0', STR_PAD_LEFT); // pastikan dua digit
        $result[] = $bulan[$b] ?? 'Bulan tidak valid';
    }

    return implode(', ', $result);
}

function maskString($value, $startVisible = 2, $endVisible = 2, $maskChar = '*')
{
    if ($value === null) {
        return '';
    }

    $value = (string) $value;
    $length = strlen($value);

    if ($length === 0) {
        return '-';
    }

    if ($length <= ($startVisible + $endVisible)) {
        return str_repeat($maskChar, $length);
    }

    $start = substr($value, 0, $startVisible);
    $end = substr($value, -$endVisible);
    $maskedLength = $length - $startVisible - $endVisible;

    return $start . str_repeat($maskChar, $maskedLength) . $end;
}

function statusKegiatan($status)
{
    switch ((string) $status) {
        case '0':
            return '<span class="badge bg-danger text-white">Draft</span>';
        case '1':
            return '<span class="badge bg-primary">Proses Verifikasi Kepegawaian</span>';
        case '2':
            return '<span class="badge bg-info">Kegiatan Belum Dimulai</span>';
        case '3':
            return '<span class="badge bg-warning">Diminta Revisi Kepegawai</span>';
        case '4':
            return '<span class="badge bg-danger">Kegiatan Berlangsung</span>';
        case '5':
            return '<span class="badge bg-success">Kegiatan Selesai</span>';
        case '6':
            return '<span class="badge bg-dark">Ditolak Kepegawaian</span>';
        default:
            return '-';
    }
}
