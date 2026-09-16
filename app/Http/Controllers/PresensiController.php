<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiPesertaExport;
use App\Models\AbsensiPeserta;
use App\Models\Kegiatan;
use App\Models\KegiatanKelas;
use App\Models\V_peserta_terpilih;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    public function index($id_kelas)
    {
        $idKelas = dec_id($id_kelas);
        $kelas = KegiatanKelas::where('id', $idKelas)->firstOrFail();
        $kegiatan = Kegiatan::where('id', $kelas->id_kegiatan)->firstOrFail();

        $tanggalMulai = Carbon::parse($kegiatan->tanggal_mulai)->startOfDay();
        $tanggalSelesai = Carbon::parse($kegiatan->tanggal_selesai)->startOfDay();
        $tanggalRange = [];

        for ($tanggal = $tanggalMulai->copy(); $tanggal->lte($tanggalSelesai); $tanggal->addDay()) {
            $tanggalRange[] = $tanggal->format('Y-m-d');
        }

        $peserta = V_peserta_terpilih::where('id_kegiatan', $kelas->id_kegiatan)
            ->where('id_kelas', $kelas->id)
            ->orderBy('nama_lengkap')
            ->get();

        $absensi = AbsensiPeserta::where('id_kegiatan', $kelas->id_kegiatan)
            ->where('id_kelas', $kelas->id)
            ->get();

        $absensiMap = [];

        foreach ($absensi as $item) {
            $tanggal = $item->tanggal instanceof Carbon
                ? $item->tanggal->format('Y-m-d')
                : Carbon::parse($item->tanggal)->format('Y-m-d');

            $absensiMap[$item->id_peserta_terpilih][$tanggal] = $item;
        }

        $data = [
            'page_title' => 'Absensi Peserta',
            'id_kelas' => $id_kelas,
            'kelas' => $kelas,
            'kegiatan' => $kegiatan,
            'id_kegiatan' => enc_id($kegiatan->id),
            'tanggal_range' => $tanggalRange,
            'peserta' => $peserta,
            'absensi_map' => $absensiMap,
        ];

        return view('kelas.presensi', $data);
    }

    public function generateAbsensi(Request $request, $id_kelas)
    {
        try {
            $idKelas = dec_id($id_kelas);
            $kelas = KegiatanKelas::where('id', $idKelas)->firstOrFail();
            $kegiatan = Kegiatan::where('id', $kelas->id_kegiatan)->firstOrFail();

            $tanggalMulai = Carbon::parse($kegiatan->tanggal_mulai)->startOfDay();
            $tanggalSelesai = Carbon::parse($kegiatan->tanggal_selesai)->startOfDay();
            $tanggalRange = [];

            for ($tanggal = $tanggalMulai->copy(); $tanggal->lte($tanggalSelesai); $tanggal->addDay()) {
                $tanggalRange[] = $tanggal->format('Y-m-d');
            }

            $peserta = V_peserta_terpilih::where('id_kegiatan', $kelas->id_kegiatan)
                ->where('id_kelas', $kelas->id)
                ->get();

            $existing = AbsensiPeserta::where('id_kegiatan', $kelas->id_kegiatan)
                ->where('id_kelas', $kelas->id)
                ->whereIn('tanggal', $tanggalRange)
                ->get()
                ->keyBy(function ($item) {
                    return $item->id_peserta_terpilih . '|' . Carbon::parse($item->tanggal)->format('Y-m-d');
                });

            $inserted = DB::transaction(function () use ($peserta, $tanggalRange, $existing, $kelas) {
                $inserted = 0;

                foreach ($peserta as $item) {
                    foreach ($tanggalRange as $tanggal) {
                        $key = $item->id . '|' . $tanggal;

                        if ($existing->has($key)) {
                            continue;
                        }

                        AbsensiPeserta::create([
                            'id_peserta_terpilih' => $item->id,
                            'id_kegiatan' => $kelas->id_kegiatan,
                            'id_kelas' => $kelas->id,
                            'tanggal' => $tanggal,
                            'presensi' => '1',
                        ]);

                        $inserted++;
                    }
                }

                return $inserted;
            });

            return response()->json([
                'status' => true,
                'msg' => 'Generate absensi selesai. ' . $inserted . ' data baru ditambahkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAbsensi(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
            'id_peserta_terpilih' => 'required',
            'tanggal' => 'required|date',
            'presensi' => 'required|in:0,1',
        ]);

        try {
            $idKelas = dec_id($request->id_kelas);
            $idPesertaTerpilih = dec_id($request->id_peserta_terpilih);
            $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $kelas = KegiatanKelas::where('id', $idKelas)->firstOrFail();
            V_peserta_terpilih::where('id', $idPesertaTerpilih)
                ->where('id_kelas', $idKelas)
                ->firstOrFail();

            $absensi = AbsensiPeserta::updateOrCreate(
                [
                    'id_peserta_terpilih' => $idPesertaTerpilih,
                    'id_kegiatan' => $kelas->id_kegiatan,
                    'id_kelas' => $idKelas,
                    'tanggal' => $tanggal,
                ],
                [
                    'presensi' => $request->presensi,
                ]
            );

            return response()->json([
                'status' => true,
                'msg' => 'Presensi berhasil disimpan',
                'data' => $absensi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportAbsensi($id_kelas)
    {
        try {
            $idKelas = dec_id($id_kelas);
            $kelas = KegiatanKelas::where('id', $idKelas)->firstOrFail();
            $kegiatan = Kegiatan::where('id', $kelas->id_kegiatan)->firstOrFail();

            $tanggalMulai = Carbon::parse($kegiatan->tanggal_mulai)->startOfDay();
            $tanggalSelesai = Carbon::parse($kegiatan->tanggal_selesai)->startOfDay();
            $tanggalRange = [];

            for ($tanggal = $tanggalMulai->copy(); $tanggal->lte($tanggalSelesai); $tanggal->addDay()) {
                $tanggalRange[] = $tanggal->format('Y-m-d');
            }

            $peserta = V_peserta_terpilih::where('id_kegiatan', $kelas->id_kegiatan)
                ->where('id_kelas', $kelas->id)
                ->orderBy('nama_lengkap')
                ->get();

            $absensi = AbsensiPeserta::where('id_kegiatan', $kelas->id_kegiatan)
                ->where('id_kelas', $kelas->id)
                ->whereIn('tanggal', $tanggalRange)
                ->get();

            $namaKelas = preg_replace('/[^a-z0-9]+/i', '_', strtolower($kelas->nama_kelas));
            $namaFile = 'rekap_absensi_' . trim($namaKelas, '_') . '.xlsx';

            return Excel::download(
                new AbsensiPesertaExport($kelas, $kegiatan, $tanggalRange, $peserta, $absensi),
                $namaFile
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
