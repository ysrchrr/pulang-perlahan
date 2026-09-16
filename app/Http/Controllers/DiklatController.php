<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\PesertaTerpilih;
use App\Models\Ref_Dokumen;
use App\Models\UnggahanDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DiklatController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Diklat Diikuti'
        ];

        return view('diklat.index', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $peserta = Peserta::where('users_id', session('user_id'))->first();
            $data = PesertaTerpilih::with('kegiatan', 'peserta')->where('id_peserta', $peserta->id)->get();
            $requiredDokumenIds = Ref_Dokumen::where('is_active', '1')
                ->where('role_id', 7)
                ->pluck('id')
                ->toArray();
            $requiredDokumenCount = count($requiredDokumenIds);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_kegiatan', function ($row) {
                    return $row->kegiatan->nama_kegiatan ?? '-';
                })
                ->addColumn('periode_pelaksanaan', function ($row) {
                    return dateRangeIndo($row->kegiatan->tanggal_mulai, $row->kegiatan->tanggal_selesai) ?? '-';
                })
                ->addColumn('lokasi_kegiatan', function ($row) {
                    return $row->kegiatan->lokasi_kegiatan ?? '-';
                })
                ->addColumn('status', function ($row) use ($requiredDokumenIds, $requiredDokumenCount) {
                    $uploadedCount = 0;

                    if (!empty($requiredDokumenIds)) {
                        $uploadedCount = UnggahanDokumen::where('id_peserta_terpilih', $row->id)
                            ->whereIn('id_ref_dokumen', $requiredDokumenIds)
                            ->count();
                    }

                    $berkasStatus = $uploadedCount . ' / ' . $requiredDokumenCount . ' Berkas Sudah Diunggah';

                    if ($row->status === '0') {
                        return '<span class="badge bg-warning">Calon Peserta</span><br><small>' . $berkasStatus . '</small>';
                    } elseif ($row->status === '1') {
                        return '<span class="badge bg-success">Peserta Terpilih</span><br><small>' . $berkasStatus . '</small>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('diklat-berkas', enc_id($row->kegiatan->id)) . '" class="btn btn-sm btn-primary"> <i class="fas fa-file-alt"></i> Berkas</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function diklatEnrollment()
    {
        $data = [
            'page_title' => 'Enrollment Diklat'
        ];

        return view('diklat.enrollment', $data);
    }

    public function searchKegiatan(Request $request)
    {
        $kodeKegiatan = trim((string) $request->input('kode_kegiatan'));

        if ($kodeKegiatan === '') {
            return response()->json([
                'status' => false,
                'msg' => 'Kode kegiatan wajib diisi'
            ], 422);
        }

        $kegiatan = Kegiatan::where('kode_kegiatan', $kodeKegiatan)->first();

        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'msg' => 'Kegiatan tidak ditemukan'
            ], 404);
        }

        if ($kegiatan->status_pendaftaran !== '1') {
            return response()->json([
                'status' => false,
                'msg' => 'Pendaftaran ditutup'
            ], 422);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => enc_id($kegiatan->id),
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'deskripsi' => $kegiatan->deskripsi ?: '-',
                'periode' => dateRangeIndo($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai) ?: '-',
                'lokasi_kegiatan' => $kegiatan->lokasi_kegiatan ?: '-',
                'banner_img' => is_array($kegiatan->banner_img) ? array_values(array_filter($kegiatan->banner_img)) : [],
            ]
        ]);
    }

    function doEnroll(Request $request)
    {
        try {
            $peserta = Peserta::where('users_id', session('user_id'))->first();
            $idKegiatan = dec_id($request->input('id'));

            $checkExisting = PesertaTerpilih::where('id_peserta', $peserta->id)
                ->where('id_kegiatan', $idKegiatan)
                ->first();

            if ($checkExisting) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Anda sudah terdaftar di kegiatan ini'
                ], 422);
            } else {
                PesertaTerpilih::create([
                    'id_peserta' => $peserta->id,
                    'id_kegiatan' => $idKegiatan,
                    'status' => '0', //calon peserta
                    'created_by' => session('user_id'),
                ]);

                return response()->json([
                    'status' => true,
                    'msg' => 'Berhasil mendaftar ke kegiatan ini, silakan tunggu konfirmasi dari panitia'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Gagal mendaftar ke kegiatan ini: ' . $e->getMessage()
            ]);
        }
    }

    public function berkasPendaftaran($id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);
        $kegiatan = Kegiatan::find($idKegiatan);

        if (!$kegiatan) {
            abort(404, 'Kegiatan tidak ditemukan');
        }

        $peserta = Peserta::where('users_id', session('user_id'))->first();
        $pesertaTerpilih = null;
        $unggahanDokumen = [];

        if ($peserta) {
            $pesertaTerpilih = PesertaTerpilih::where('id_peserta', $peserta->id)
                ->where('id_kegiatan', $kegiatan->id)
                ->first();
        }

        if (!$pesertaTerpilih) {
            abort(403, 'Anda tidak terdaftar pada kegiatan ini');
        }

        if ($pesertaTerpilih) {
            $unggahanDokumen = UnggahanDokumen::where('id_peserta_terpilih', $pesertaTerpilih->id)
                ->get()
                ->keyBy('id_ref_dokumen')
                ->toArray();
        }

        $data = [
            'page_title' => 'Berkas Pendaftaran - ' . $kegiatan->nama_kegiatan,
            'kegiatan' => $kegiatan,
            'id_kegiatan_enc' => $id_kegiatan,
            'ref_dokumen' => Ref_Dokumen::where('is_active', '1')->where('role_id', 7)->get(),
            'unggahan_dokumen' => $unggahanDokumen,
        ];

        return view('diklat.berkas', $data);
    }

    public function uploadBerkasPendaftaran(Request $request, $id_kegiatan)
    {
        $request->validate([
            'id_ref_dokumen' => 'required|integer|exists:ref_dokumen,id',
            'file_upload' => 'required|file|max:5120',
        ]);

        try {
            $idKegiatan = dec_id($id_kegiatan);
            $kegiatan = Kegiatan::findOrFail($idKegiatan);
            $peserta = Peserta::where('users_id', session('user_id'))->firstOrFail();
            $pesertaTerpilih = PesertaTerpilih::where('id_peserta', $peserta->id)
                ->where('id_kegiatan', $kegiatan->id)
                ->firstOrFail();
            $refDokumen = Ref_Dokumen::findOrFail($request->input('id_ref_dokumen'));
            $unggahanDokumen = UnggahanDokumen::where('id_peserta_terpilih', $pesertaTerpilih->id)
                ->where('id_ref_dokumen', $refDokumen->id)
                ->first();

            if ($unggahanDokumen && $unggahanDokumen->path_dokumen && Storage::disk('public')->exists($unggahanDokumen->path_dokumen)) {
                Storage::disk('public')->delete($unggahanDokumen->path_dokumen);
            }

            $file = $request->file('file_upload');
            $slug = $refDokumen->slug ?: Str::slug($refDokumen->nama_dokumen);
            $filename = 'berkas_' . $pesertaTerpilih->id . '_' . $refDokumen->id . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $pathDokumen = $file->storeAs('diklat/berkas/' . $idKegiatan . '/' . $slug, $filename, 'public');

            if (!$unggahanDokumen) {
                $unggahanDokumen = new UnggahanDokumen();
                $unggahanDokumen->id_peserta_terpilih = $pesertaTerpilih->id;
                $unggahanDokumen->id_ref_dokumen = $refDokumen->id;
            }

            $unggahanDokumen->slug = $slug;
            $unggahanDokumen->path_dokumen = $pathDokumen;
            $unggahanDokumen->save();

            return response()->json([
                'status' => true,
                'msg' => 'Berkas berhasil diunggah',
                'data' => [
                    'path_dokumen' => $pathDokumen,
                    'url' => asset('storage/' . $pathDokumen),
                ]
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
}
