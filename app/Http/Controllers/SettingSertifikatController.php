<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\MasterPenandatangan;
use App\Models\MasterTemplateSertifikat;
use App\Models\PegawaiTerpilih;
use App\Models\PesertaTerpilih;
use App\Models\SettingSertifikat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SettingSertifikatController extends Controller
{
    public function settingSertifikat($id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);

        $data = [
            'page_title' => 'Konfigurasi Sertifikat',
            'id_kegiatan' => $id_kegiatan,
            'kegiatan' => Kegiatan::where('id', $idKegiatan)->first(),
            'list_template' => MasterTemplateSertifikat::all(),
            'list_penandatangan' => MasterPenandatangan::all(),
            'current_setting' => SettingSertifikat::where('id_kegiatan', $idKegiatan)->first(),
            'nomor_sertifikat_range' => $this->getPesertaNomorSertifikatRange($idKegiatan),
            'nomor_sertifikat_narasumber_range' => $this->getNarasumberNomorSertifikatRange($idKegiatan),
        ];

        return view('setting_sertifikat.index', $data);
    }

    public function store(Request $request)
    {
        $idKegiatan = dec_id($request->id_kegiatan);

        $request->validate([
            'id_kegiatan' => 'required',
            'id_template_sertifikat' => [
                'required',
                Rule::exists('master_template_sertifikat', 'id')->whereNull('deleted_at'),
            ],
            'is_cetak_kelas' => 'required|in:0,1',
            'id_penandatangan' => [
                'required',
                Rule::exists('master_penandatangan', 'id')->whereNull('deleted_at'),
            ],
            'kota_pelaksanaan' => 'required|string|max:255',
            'tanggal_penandatangan' => 'required|date',
        ]);

        try {
            Kegiatan::findOrFail($idKegiatan);

            $setting = SettingSertifikat::firstOrNew([
                'id_kegiatan' => $idKegiatan,
            ]);

            $setting->id_template_sertifikat = $request->id_template_sertifikat;
            $setting->is_cetak_kelas = $request->is_cetak_kelas;
            $setting->id_penandatangan = $request->id_penandatangan;
            $setting->kota_pelaksanaan = $request->kota_pelaksanaan;
            $setting->tanggal_penandatangan = $request->tanggal_penandatangan;
            $setting->save();

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil menyimpan setting sertifikat'
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

    public function generateNomorSertifikat(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
        ]);

        $idKegiatan = dec_id($request->id_kegiatan);

        try {
            Kegiatan::findOrFail($idKegiatan);

            $setting = SettingSertifikat::where('id_kegiatan', $idKegiatan)->first();

            if (!$setting || !$setting->tanggal_penandatangan) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Tanggal Penandatangan harus disii'
                ], 422);
            }

            $tanggalPenandatangan = Carbon::parse($setting->tanggal_penandatangan);
            $tahun = $tanggalPenandatangan->format('Y');
            $bulanRomawi = $this->getBulanRomawi((int) $tanggalPenandatangan->format('n'));

            $pesertaTanpaNomor = PesertaTerpilih::where('id_kegiatan', $idKegiatan)
                ->where(function ($query) {
                    $query->whereNull('no_sertifikat')
                        ->orWhere('no_sertifikat', '');
                })
                ->orderBy('id', 'asc')
                ->get();

            $narasumberTanpaNomor = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                ->where('id_jabatan_kegiatan', '1')
                ->where(function ($query) {
                    $query->whereNull('no_sertifikat')
                        ->orWhere('no_sertifikat', '');
                })
                ->orderBy('id', 'asc')
                ->get();

            if ($pesertaTanpaNomor->isEmpty() && $narasumberTanpaNomor->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'msg' => 'Tidak ada nomor sertifikat yang perlu digenerate',
                    'nomor_sertifikat_range' => $this->getPesertaNomorSertifikatRange($idKegiatan),
                    'nomor_sertifikat_narasumber_range' => $this->getNarasumberNomorSertifikatRange($idKegiatan),
                ]);
            }

            DB::transaction(function () use ($pesertaTanpaNomor, $narasumberTanpaNomor, $tahun, $bulanRomawi) {
                $nomorTerakhirPeserta = PesertaTerpilih::where('no_sertifikat', 'like', '%/' . $tahun)
                    ->lockForUpdate()
                    ->selectRaw('MAX(CAST(SUBSTRING_INDEX(no_sertifikat, "/", 1) AS UNSIGNED)) as nomor_terakhir')
                    ->value('nomor_terakhir');

                $nomorTerakhirNarasumber = PegawaiTerpilih::where('id_jabatan_kegiatan', '1')
                    ->where('no_sertifikat', 'like', '%/' . $tahun)
                    ->lockForUpdate()
                    ->selectRaw('MAX(CAST(SUBSTRING_INDEX(no_sertifikat, "/", 1) AS UNSIGNED)) as nomor_terakhir')
                    ->value('nomor_terakhir');

                $nomorUrut = max((int) $nomorTerakhirPeserta, (int) $nomorTerakhirNarasumber);

                foreach ($pesertaTanpaNomor as $peserta) {
                    $nomorUrut++;
                    $peserta->no_sertifikat = $this->formatNomorSertifikat($nomorUrut, $bulanRomawi, $tahun);
                    $peserta->save();
                }

                foreach ($narasumberTanpaNomor as $narasumber) {
                    $nomorUrut++;
                    $narasumber->no_sertifikat = $this->formatNomorSertifikat($nomorUrut, $bulanRomawi, $tahun);
                    $narasumber->save();
                }
            });

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil generate nomor sertifikat',
                'nomor_sertifikat_range' => $this->getPesertaNomorSertifikatRange($idKegiatan),
                'nomor_sertifikat_narasumber_range' => $this->getNarasumberNomorSertifikatRange($idKegiatan),
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

    private function getPesertaNomorSertifikatRange($idKegiatan)
    {
        $nomorSertifikat = PesertaTerpilih::where('id_kegiatan', $idKegiatan)
            ->whereNotNull('no_sertifikat')
            ->where('no_sertifikat', '!=', '')
            ->orderBy('id', 'asc')
            ->pluck('no_sertifikat');

        return $this->formatNomorSertifikatRange($nomorSertifikat);
    }

    private function getNarasumberNomorSertifikatRange($idKegiatan)
    {
        $nomorSertifikat = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
            ->where('id_jabatan_kegiatan', '1')
            ->whereNotNull('no_sertifikat')
            ->where('no_sertifikat', '!=', '')
            ->orderBy('id', 'asc')
            ->pluck('no_sertifikat');

        return $this->formatNomorSertifikatRange($nomorSertifikat);
    }

    private function formatNomorSertifikatRange($nomorSertifikat)
    {
        if ($nomorSertifikat->isEmpty()) {
            return '';
        }

        $nomorPertama = $nomorSertifikat->first();
        $nomorTerakhir = $nomorSertifikat->last();

        if ($nomorPertama === $nomorTerakhir) {
            return $nomorPertama;
        }

        return $nomorPertama . ' s.d ' . $nomorTerakhir;
    }

    private function formatNomorSertifikat($nomorUrut, $bulanRomawi, $tahun)
    {
        return str_pad($nomorUrut, 5, '0', STR_PAD_LEFT) . '/BGTK_Jambi/' . $bulanRomawi . '/' . $tahun;
    }

    private function getBulanRomawi($bulan)
    {
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $bulanRomawi[$bulan] ?? '';
    }
}
