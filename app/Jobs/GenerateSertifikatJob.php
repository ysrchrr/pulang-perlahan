<?php

namespace App\Jobs;

use App\Models\PegawaiTerpilih;
use App\Models\PesertaTerpilih;
use App\Models\SettingSertifikat;
use App\Models\V_peserta_terpilih;
use App\Models\V_pegawai_terpilih;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateSertifikatJob implements ShouldQueue
{
    use Queueable;

    public $tries = 1;

    protected $idKegiatan;
    protected $peran;

    public function __construct($idKegiatan, $peran)
    {
        $this->idKegiatan = $idKegiatan;
        $this->peran = $peran;
    }

    public function handle(): void
    {
        $setting = SettingSertifikat::with(['kegiatan', 'template_sertifikat', 'penandatangan'])
            ->where('id_kegiatan', $this->idKegiatan)
            ->firstOrFail();

        if (
            !$setting->template_sertifikat ||
            !$setting->template_sertifikat->bg_path ||
            !Storage::disk('public')->exists($setting->template_sertifikat->bg_path)
        ) {
            throw new \RuntimeException('Template sertifikat tidak valid');
        }

        if (!$setting->penandatangan) {
            throw new \RuntimeException('Penandatangan sertifikat tidak ditemukan');
        }

        $items = $this->getItems();

        if ($items->isEmpty()) {
            throw new \RuntimeException('Data sertifikat tidak ditemukan');
        }

        $filePath = $this->buildFilePath();
        $pdf = Pdf::loadView($this->getViewName(), [
            'items' => $items,
            'setting' => $setting,
            'bg_path' => Storage::disk('public')->path($setting->template_sertifikat->bg_path),
            'signature_path' => $this->getPublicStoragePath($setting->penandatangan->path_signature),
            'cap_path' => $this->getPublicStoragePath($setting->penandatangan->path_cap),
            'tanggal_penandatangan_formatted' => $this->formatTanggalIndonesia($setting->tanggal_penandatangan),
        ])->setPaper('a4', 'landscape');

        Storage::disk('public')->put($filePath, $pdf->output());

        $this->getBaseQuery()->update([
            'file_sertifikat' => $filePath,
            'is_generate_sertifikat' => 'done',
        ]);
    }

    public function failed(?\Throwable $exception): void
    {
        $this->getBaseQuery()->update([
            'file_sertifikat' => null,
            'is_generate_sertifikat' => 'failed',
        ]);
    }

    private function getItems()
    {
        if ($this->peran === 'narasumber') {
            $records = PegawaiTerpilih::where('id_kegiatan', $this->idKegiatan)
                ->where('id_jabatan_kegiatan', '1')
                ->get()
                ->keyBy('id');

            return V_pegawai_terpilih::where('id_kegiatan', $this->idKegiatan)
                ->where('id_jabatan_kegiatan', '1')
                ->orderBy('nama', 'asc')
                ->get()
                ->map(function ($item) use ($records, $setting) {
                    $record = $records->get($item->id);

                    return (object) [
                        'nama' => $item->nama,
                        'nomor_sertifikat' => $record?->no_sertifikat,
                        'nama_kegiatan' => $item->nama_kegiatan,
                        'tanggal_mulai' => $item->tanggal_mulai,
                        'tanggal_selesai' => $item->tanggal_selesai,
                        'lokasi_kegiatan' => $item->lokasi_kegiatan ?? $setting->kegiatan->lokasi_kegiatan ?? '-'
                    ];
                });
        }

        $records = PesertaTerpilih::where('id_kegiatan', $this->idKegiatan)
            ->get()
            ->keyBy('id');

        return V_peserta_terpilih::where('id_kegiatan', $this->idKegiatan)
            ->orderBy('nama_lengkap', 'asc')
            ->get()
            ->map(function ($item) use ($records, $setting) {
                $record = $records->get($item->id);

                return (object) [
                    'nama' => $item->nama_lengkap,
                    'unit_kerja' => $item->unit_kerja,
                    'nomor_sertifikat' => $record?->no_sertifikat,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'nama_kelas' => $item->nama_kelas,
                    'lokasi_kegiatan' => $item->lokasi_kegiatan ?? $setting->kegiatan->lokasi_kegiatan ?? '-'
                ];
            });
    }

    private function getBaseQuery()
    {
        if ($this->peran === 'narasumber') {
            return PegawaiTerpilih::where('id_kegiatan', $this->idKegiatan)
                ->where('id_jabatan_kegiatan', '1');
        }

        return PesertaTerpilih::where('id_kegiatan', $this->idKegiatan);
    }

    private function buildFilePath()
    {
        return 'sertifikat/' . $this->idKegiatan . '/' . $this->peran . '/sertifikat_' . $this->peran . '_' . time() . '_' . Str::random(8) . '.pdf';
    }

    private function getViewName()
    {
        if ($this->peran === 'narasumber') {
            return 'sertifikat.sertifikat-narasumber';
        }

        return 'sertifikat.sertifikat-peserta';
    }

    private function getPublicStoragePath($path)
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->path($path);
    }

    private function formatTanggalIndonesia($tanggal)
    {
        if (!$tanggal) {
            return '';
        }

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
            12 => 'Desember',
        ];

        $parsed = Carbon::parse($tanggal);

        return $parsed->format('d') . ' ' . $bulan[(int) $parsed->format('n')] . ' ' . $parsed->format('Y');
    }
}
