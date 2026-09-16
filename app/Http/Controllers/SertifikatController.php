<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateSertifikatJob;
use App\Models\Kegiatan;
use App\Models\PegawaiTerpilih;
use App\Models\PesertaTerpilih;
use App\Models\SettingSertifikat;
use App\Models\V_pegawai_terpilih;
use App\Models\V_peserta_terpilih;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index($id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);

        $data = [
            'page_title' => 'Sertifikat',
            'id_kegiatan' => $id_kegiatan,
            'kegiatan' => Kegiatan::where('id', $idKegiatan)->first(),
            'list_peran' => [
                $this->buildPeranItem($idKegiatan, 'narasumber', 'Narasumber'),
                $this->buildPeranItem($idKegiatan, 'peserta', 'Peserta'),
            ],
        ];

        return view('sertifikat.index', $data);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
            'peran' => 'required|in:narasumber,peserta',
        ]);

        $idKegiatan = dec_id($request->id_kegiatan);
        $peran = $request->peran;

        try {
            Kegiatan::findOrFail($idKegiatan);
            $setting = SettingSertifikat::with(['template_sertifikat', 'penandatangan'])
                ->where('id_kegiatan', $idKegiatan)
                ->first();

            if (!$setting) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Setting sertifikat belum dibuat'
                ], 422);
            }

            if (!$setting->template_sertifikat || !$setting->template_sertifikat->bg_path) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Template sertifikat belum lengkap'
                ], 422);
            }

            if (!Storage::disk('public')->exists($setting->template_sertifikat->bg_path)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Background template sertifikat tidak ditemukan'
                ], 422);
            }

            if (!$setting->penandatangan) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Penandatangan sertifikat belum dipilih'
                ], 422);
            }

            $query = $this->getGenerateQuery($idKegiatan, $peran);
            $count = $query->count();

            if ($count < 1) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Data untuk generate sertifikat tidak ditemukan'
                ], 422);
            }

            $isProcessing = (clone $query)
                ->where('is_generate_sertifikat', 'processing')
                ->exists();

            if ($isProcessing) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Generate sertifikat sedang berjalan'
                ], 422);
            }

            (clone $query)->update([
                'is_generate_sertifikat' => 'processing',
                'file_sertifikat' => null,
            ]);

            GenerateSertifikatJob::dispatch($idKegiatan, $peran);

            return response()->json([
                'status' => true,
                'msg' => 'Generate sertifikat masuk queue background'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function preview(Request $request, $id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);
        $peran = $request->input('peran', 'peserta');

        if (!in_array($peran, ['narasumber', 'peserta'])) {
            abort(404);
        }

        $setting = SettingSertifikat::with(['kegiatan', 'template_sertifikat', 'penandatangan'])
            ->where('id_kegiatan', $idKegiatan)
            ->firstOrFail();

        if (!$setting->template_sertifikat || !$setting->template_sertifikat->bg_path) {
            abort(404, 'Template sertifikat belum lengkap');
        }

        if (!Storage::disk('public')->exists($setting->template_sertifikat->bg_path)) {
            abort(404, 'Background template sertifikat tidak ditemukan');
        }

        if (!$setting->penandatangan) {
            abort(404, 'Penandatangan sertifikat belum dipilih');
        }

        $items = collect([$this->buildPreviewItem($idKegiatan, $peran, $setting)]);

        return Pdf::loadView($this->getViewName($peran), [
            'items' => $items,
            'setting' => $setting,
            'bg_path' => Storage::disk('public')->path($setting->template_sertifikat->bg_path),
            'signature_path' => $this->getPublicStoragePath($setting->penandatangan->path_signature),
            'cap_path' => $this->getPublicStoragePath($setting->penandatangan->path_cap),
            'tanggal_penandatangan_formatted' => $this->formatTanggalIndonesia($setting->tanggal_penandatangan),
        ])->setPaper('a4', 'landscape')
            ->stream('preview-sertifikat-' . $peran . '.pdf');
    }

    private function buildPeranItem($idKegiatan, $key, $label)
    {
        $query = $this->getGenerateQuery($idKegiatan, $key);
        $jumlah = (clone $query)->count();
        $record = (clone $query)->orderBy('id', 'asc')->first();
        $sertifikat = '-';

        if ($record) {
            $status = $record->is_generate_sertifikat;
            $path = $record->file_sertifikat;

            if ($status === 'processing') {
                $sertifikat = [
                    'type' => 'text',
                    'label' => 'Sedang diproses',
                ];
            } elseif ($path && Storage::disk('public')->exists($path)) {
                $sertifikat = [
                    'type' => 'link',
                    'label' => 'Buka PDF',
                    'url' => asset('storage/' . $path),
                ];
            } elseif ($status === 'failed') {
                $sertifikat = [
                    'type' => 'text',
                    'label' => 'Gagal generate',
                ];
            }
        }

        return [
            'peran' => $label,
            'jumlah' => $jumlah,
            'sertifikat' => $sertifikat,
            'key' => $key,
            'can_generate' => $jumlah > 0,
            'is_processing' => $record?->is_generate_sertifikat === 'processing',
        ];
    }

    private function getGenerateQuery($idKegiatan, $peran)
    {
        if ($peran === 'narasumber') {
            return PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                ->where('id_jabatan_kegiatan', '1');
        }

        return PesertaTerpilih::where('id_kegiatan', $idKegiatan);
    }

    private function buildPreviewItem($idKegiatan, $peran, $setting)
    {
        if ($peran === 'narasumber') {
            $record = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                ->where('id_jabatan_kegiatan', '1')
                ->orderBy('id', 'asc')
                ->first();

            $item = V_pegawai_terpilih::where('id_kegiatan', $idKegiatan)
                ->where('id_jabatan_kegiatan', '1')
                ->orderBy('nama', 'asc')
                ->first();

            if ($item) {
                return (object) [
                    'nama' => $item->nama,
                    'nomor_sertifikat' => $record?->no_sertifikat,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'lokasi_kegiatan' => $item->lokasi_kegiatan ?? $setting->kegiatan->lokasi_kegiatan ?? '-',
                ];
            }

            return (object) [
                'nama' => 'NAMA NARASUMBER',
                'nomor_sertifikat' => '000/PGP-BGTKJAMBI/VI/2026',
                'nama_kegiatan' => $setting->kegiatan->nama_kegiatan ?? 'Nama Kegiatan',
                'tanggal_mulai' => $setting->kegiatan->tanggal_mulai ?? now()->toDateString(),
                'tanggal_selesai' => $setting->kegiatan->tanggal_selesai ?? now()->toDateString(),
                'lokasi_kegiatan' => $setting->kegiatan->lokasi_kegiatan ?? '-',
            ];
        }

        $record = PesertaTerpilih::where('id_kegiatan', $idKegiatan)
            ->orderBy('id', 'asc')
            ->first();

        $item = V_peserta_terpilih::where('id_kegiatan', $idKegiatan)
            ->orderBy('nama_lengkap', 'asc')
            ->first();

        if ($item) {
            return (object) [
                'nama' => $item->nama_lengkap,
                'unit_kerja' => $item->unit_kerja,
                'nomor_sertifikat' => $record?->no_sertifikat,
                'nama_kegiatan' => $item->nama_kegiatan,
                'tanggal_mulai' => $item->tanggal_mulai,
                'tanggal_selesai' => $item->tanggal_selesai,
                'nama_kelas' => $item->nama_kelas,
                'lokasi_kegiatan' => $item->lokasi_kegiatan ?? $setting->kegiatan->lokasi_kegiatan ?? '-',
            ];
        }

        return (object) [
            'nama' => 'NAMA PESERTA',
            'unit_kerja' => 'UNIT KERJA',
            'nomor_sertifikat' => '000/PGP-BGTKJAMBI/VI/2026',
            'nama_kegiatan' => $setting->kegiatan->nama_kegiatan ?? 'Nama Kegiatan',
            'tanggal_mulai' => $setting->kegiatan->tanggal_mulai ?? now()->toDateString(),
            'tanggal_selesai' => $setting->kegiatan->tanggal_selesai ?? now()->toDateString(),
            'nama_kelas' => null,
            'lokasi_kegiatan' => $setting->kegiatan->lokasi_kegiatan ?? '-',
        ];
    }

    private function getViewName($peran)
    {
        if ($peran === 'narasumber') {
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
