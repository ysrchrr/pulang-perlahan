<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanKelas;
use App\Models\Peserta;
use App\Models\PesertaTerpilih;
use App\Models\V_peserta_terpilih;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

class KelasController extends Controller
{
    public function listKelas($id_kegiatan)
    {
        $data = [
            'page_title' => 'Daftar Kelas',
            'id_kegiatan' => $id_kegiatan
        ];

        return view('kelas.index', $data);
    }

    public function exportFormPresensi($id_kegiatan)
    {
        try {
            $idKegiatan = dec_id($id_kegiatan);
            $kegiatan = Kegiatan::findOrFail($idKegiatan);

            $tanggalMulai = Carbon::parse($kegiatan->tanggal_mulai)->startOfDay();
            $tanggalSelesai = Carbon::parse($kegiatan->tanggal_selesai)->startOfDay();
            $tanggalRange = [];

            for ($tanggal = $tanggalMulai->copy(); $tanggal->lte($tanggalSelesai); $tanggal->addDay()) {
                $tanggalRange[] = $tanggal->format('Y-m-d');
            }

            $peserta = V_peserta_terpilih::where('id_kegiatan', $kegiatan->id)
                ->orderBy('nama_lengkap')
                ->get();

            $templatePath = storage_path('app/templates/Template-Presensi.xlsx');
            if (!file_exists($templatePath)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Template Presensi tidak ditemukan'
                ], 404);
            }

            $baseTempPath = storage_path('app/temp');
            if (!File::exists($baseTempPath)) {
                File::makeDirectory($baseTempPath, 0755, true);
            }

            $tempDir = storage_path('app/temp/form_presensi_' . uniqid());
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            $zipName = 'form_absensi_' . preg_replace('/[^a-z0-9]+/i', '_', strtolower($kegiatan->nama_kegiatan ?? $kegiatan->nama ?? 'kegiatan')) . '.zip';
            $zipPath = $baseTempPath . '/' . $zipName;
            $zip = new ZipArchive();
            $generatedFiles = [];

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                File::deleteDirectory($tempDir);

                return response()->json([
                    'status' => false,
                    'msg' => 'Gagal membuat file ZIP'
                ], 500);
            }

            foreach ($tanggalRange as $index => $tanggal) {
                $spreadsheet = IOFactory::load($templatePath);
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A2', $kegiatan->nama_kegiatan ?? $kegiatan->nama ?? '-');
                $sheet->setCellValue('A3', $kegiatan->lokasi_kegiatan ?? '-');
                $sheet->setCellValue('A4', 'HARI/TANGGAL : ' . tglIndo($tanggal));

                $row = 6;
                $nomor = 1;

                foreach ($peserta as $item) {
                    $sheet->setCellValue('A' . $row, $nomor);
                    $sheet->setCellValue('B' . $row, $item->nama_lengkap ?? '-');
                    $sheet->setCellValue('C' . $row, $item->unit_kerja ?? '-');
                    $sheet->setCellValue('D' . $row, $nomor);

                    $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(
                        $row % 2 === 1
                            ? \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
                            : \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                    );

                    $row++;
                    $nomor++;
                }

                $fileName = 'form_absensi_' . ($index + 1) . '_' . $tanggal . '.xlsx';
                $filePath = $tempDir . '/' . $fileName;

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($filePath);

                $zip->addFile($filePath, $fileName);
                $generatedFiles[] = $filePath;

                $spreadsheet->disconnectWorksheets();
                unset($writer, $spreadsheet);
            }

            $zip->close();

            foreach ($generatedFiles as $filePath) {
                @unlink($filePath);
            }

            File::deleteDirectory($tempDir);

            return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getDataKelas(Request $request, $id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);

        if ($request->ajax()) {
            $jumlahPesertaPerKelas = PesertaTerpilih::selectRaw('id_kelas, COUNT(*) as jumlah_peserta')
                ->where('id_kegiatan', $idKegiatan)
                ->where('status', '1')
                ->whereNotNull('id_kelas')
                ->groupBy('id_kelas');

            $data = KegiatanKelas::query()
                ->where('id_kegiatan', $idKegiatan)
                ->leftJoinSub($jumlahPesertaPerKelas, 'peserta_kelas', function ($join) {
                    $join->on('kegiatan_kelas.id', '=', 'peserta_kelas.id_kelas');
                })
                ->select(
                    'kegiatan_kelas.id',
                    'kegiatan_kelas.nama_kelas',
                    'kegiatan_kelas.kuota_kelas',
                    'kegiatan_kelas.status_pendaftaran'
                )
                ->selectRaw('COALESCE(peserta_kelas.jumlah_peserta, 0) as jumlah_peserta');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jumlah_peserta', function ($row) {
                    return (int) $row->jumlah_peserta;
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a href="' . route('kegiatan-kelas-peserta', enc_id($row->id)) . '" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-users"></i> Kelola Peserta
                    </a>
                    <a href="' . route('kegiatan-kelas-presensi', enc_id($row->id)) . '" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-users"></i> Presensi Peserta
                    </a>
                    <button class="btn btn-sm btn-warning" onclick="toEdit(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas' => 'nullable',
            'id_kegiatan' => 'required',
            'nama_kelas' => 'required|string|max:255',
        ]);

        try {
            $idKegiatan = dec_id($request->id_kegiatan);
            $idKelas = $request->id_kelas ? dec_id($request->id_kelas) : null;

            if ($idKelas) {
                $kelas = KegiatanKelas::where('id', $idKelas)
                    ->where('id_kegiatan', $idKegiatan)
                    ->firstOrFail();
            } else {
                $kelas = new KegiatanKelas();
                $kelas->id_kegiatan = $idKegiatan;
            }

            $kelas->nama_kelas = $request->nama_kelas;
            $kelas->save();

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil menyimpan data'
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

    public function detail(Request $request)
    {
        $idKelas = dec_id($request->input('id'));
        $data = KegiatanKelas::find($idKelas);

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => false,
            'msg' => 'Data tidak ditemukan'
        ], 404);
    }

    public function delete(Request $request)
    {
        try {
            $idKelas = dec_id($request->input('id'));
            $data = KegiatanKelas::findOrFail($idKelas);

            $totalPeserta = PesertaTerpilih::where('id_kelas', $data->id)->count();
            if ($totalPeserta > 0) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Kelas tidak bisa dihapus karena masih memiliki peserta'
                ], 422);
            }

            $data->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil hapus data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function kelasPeserta($id_kelas)
    {
        $idKelas = dec_id($id_kelas);
        $kelas = KegiatanKelas::findOrFail($idKelas);
        $kegiatan = Kegiatan::where('id', $kelas->id_kegiatan);

        $data = [
            'page_title' => 'Peserta Kelas ' . $kelas->nama_kelas,
            'id_kelas' => $id_kelas,
            'id_kegiatan' => enc_id($kelas->id_kegiatan),
            'nama_kelas' => $kelas->nama_kelas,
            'kegiatan' => $kegiatan
        ];

        return view('kelas.list-peserta', $data);
    }

    public function getDataPesertaKelas(Request $request, $id_kelas)
    {
        $idKelas = dec_id($id_kelas);
        $kelas = KegiatanKelas::findOrFail($idKelas);

        if ($request->ajax()) {
            $data = V_peserta_terpilih::where('id_kegiatan', $kelas->id_kegiatan)
                ->where('id_kelas', $kelas->id)
                ->where('status', '1');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->nama_lengkap ?? '-';
                })
                ->addColumn('instansi', function ($row) {
                    return $row->unit_kerja ?? '-';
                })
                ->addColumn('kontak', function ($row) {
                    $email = '<span class="badge rounded-pill bg-secondary">' . ($row->email ?? '-') . '</span>';
                    $noHp = '<span class="badge rounded-pill bg-success">' . ($row->no_hp ?? '-') . '</span>';

                    return $email . '<br>' . $noHp;
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge bg-success">Peserta</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a class="btn btn-sm btn-primary" href="' . route('kegiatan-kelas-generate-biodata', ['id_kegiatan' => enc_id($row->id_kegiatan), 'id_peserta' => enc_id($row->users_id)]) . '">
                        <i class="fa-regular fa-id-card"></i> Biodata
                    </a>
                    <button class="btn btn-sm btn-danger" onclick="keluarkanPeserta(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-user-minus"></i> Keluarkan
                    </button>
                    ';
                })
                ->rawColumns(['kontak', 'status', 'action'])
                ->make(true);
        }
    }

    public function getPesertaTersedia($id_kelas)
    {
        try {
            $idKelas = dec_id($id_kelas);
            $kelas = KegiatanKelas::findOrFail($idKelas);

            $data = V_peserta_terpilih::where('id_kegiatan', $kelas->id_kegiatan)
                ->where('status', '1')
                ->whereNull('id_kelas')
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'unit_kerja'])
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'id_enc' => enc_id($item->id),
                        'nama_lengkap' => $item->nama_lengkap,
                        'unit_kerja' => $item->unit_kerja,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function storePeserta(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
            'id_peserta_terpilih' => 'required|array|min:1',
            'id_peserta_terpilih.*' => 'required',
        ]);

        try {
            $idKelas = dec_id($request->id_kelas);
            $kelas = KegiatanKelas::findOrFail($idKelas);

            $idPesertaTerpilih = array_map(function ($item) {
                return dec_id($item);
            }, $request->id_peserta_terpilih);

            $peserta = PesertaTerpilih::whereIn('id', $idPesertaTerpilih)
                ->where('id_kegiatan', $kelas->id_kegiatan)
                ->where('status', '1')
                ->whereNull('id_kelas')
                ->get();

            if ($peserta->count() !== count($idPesertaTerpilih)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Sebagian peserta tidak valid atau sudah masuk kelas lain'
                ], 422);
            }

            PesertaTerpilih::whereIn('id', $peserta->pluck('id'))->update([
                'id_kelas' => $kelas->id
            ]);

            return response()->json([
                'status' => true,
                'msg' => $peserta->count() . ' peserta berhasil ditambahkan ke kelas'
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

    public function deletePeserta(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
            'id_peserta_terpilih' => 'required',
        ]);

        try {
            $idKelas = dec_id($request->id_kelas);
            $idPesertaTerpilih = dec_id($request->id_peserta_terpilih);

            $peserta = PesertaTerpilih::where('id', $idPesertaTerpilih)
                ->where('id_kelas', $idKelas)
                ->firstOrFail();

            $peserta->id_kelas = null;
            $peserta->save();

            return response()->json([
                'status' => true,
                'msg' => 'Peserta berhasil dikeluarkan dari kelas'
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

    public function generateBiodata($_id_kegiatan, $_users_id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail(dec_id($_id_kegiatan));
            $peserta = Peserta::with(['golongan', 'users'])
                ->where('users_id', dec_id($_users_id))
                ->firstOrFail();

            PesertaTerpilih::where('id_kegiatan', $kegiatan->id)
                ->where('id_peserta', $peserta->id)
                ->where('status', '1')
                ->firstOrFail();

            $templatePath = storage_path('app/templates/template_biodata_peserta.docx');

            if (!file_exists($templatePath)) {
                abort(404, 'Template biodata peserta tidak ditemukan.');
            }

            $preparedTemplatePath = $this->prepareBiodataTemplateForPhpWord($templatePath);
            $templateProcessor = new TemplateProcessor($preparedTemplatePath);

            $templateProcessor->setValue('nama_kegiatan', strtoupper($kegiatan->nama_kegiatan ?? '-'));
            $templateProcessor->setValue('peserta_nama', $peserta->nama_lengkap ?? $peserta->nama ?? $peserta->users->name ?? '-');
            $templateProcessor->setValue('peserta_ttl', $this->formatTempatTanggalLahir($peserta));
            $templateProcessor->setValue('peserta_unit_kerja', $peserta->unit_kerja ?? '-');
            $templateProcessor->setValue('peserta_golongan_pangkat', $this->formatGolonganPangkat($peserta));
            $templateProcessor->setValue('peserta_npwp', $peserta->npwp ?? '-');
            $templateProcessor->setValue('peserta_alamat', $peserta->alamat ?? '-');
            $templateProcessor->setValue('peserta_no_hp', $peserta->no_hp ?? '-');
            $templateProcessor->setValue('peserta_email', $peserta->email ?? $peserta->users->email ?? '-');

            $fileName = 'Biodata Peserta ' . preg_replace('/[^a-z0-9]+/i', '_', $peserta->nama_lengkap ?? $peserta->nama ?? 'peserta') . '.docx';
            $outputPath = storage_path('app/temp/' . uniqid('biodata_peserta_') . '.docx');

            if (!File::exists(storage_path('app/temp'))) {
                File::makeDirectory(storage_path('app/temp'), 0755, true);
            }

            $templateProcessor->saveAs($outputPath);

            if (file_exists($preparedTemplatePath)) {
                @unlink($preparedTemplatePath);
            }

            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    private function prepareBiodataTemplateForPhpWord(string $templatePath): string
    {
        if (!File::exists(storage_path('app/temp'))) {
            File::makeDirectory(storage_path('app/temp'), 0755, true);
        }

        $tempTemplatePath = storage_path('app/temp/' . uniqid('template_biodata_') . '.docx');
        copy($templatePath, $tempTemplatePath);

        $zip = new ZipArchive();
        if ($zip->open($tempTemplatePath) !== true) {
            return $templatePath;
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            return $tempTemplatePath;
        }

        $replacements = [
            '$nama_kegiatan' => '${nama_kegiatan}',
            '$peserta-&gt;nama' => '${peserta_nama}',
            '$peserta-&gt;ttl' => '${peserta_ttl}',
            '$peserta-&gt;unit_kerja' => '${peserta_unit_kerja}',
            '$peserta-&gt;ref_golongan-&gt;golongan / $peserta-&gt;ref_golongan-&gt;pangkat' => '${peserta_golongan_pangkat}',
            '$peserta-&gt;npwp' => '${peserta_npwp}',
            '$peserta-&gt;alamat' => '${peserta_alamat}',
            '$peserta-&gt;no_hp' => '${peserta_no_hp}',
            '$peserta-&gt;email' => '${peserta_email}',
            ' MERGEFIELD alamat ' => '',
        ];

        $documentXml = str_replace(array_keys($replacements), array_values($replacements), $documentXml);
        $zip->addFromString('word/document.xml', $documentXml);
        $zip->close();

        return $tempTemplatePath;
    }

    private function formatTempatTanggalLahir(Peserta $peserta): string
    {
        $tempatLahir = $peserta->tempat_lahir ?? null;
        $tanggalLahir = $peserta->tanggal_lahir ? tglIndo($peserta->tanggal_lahir) : null;

        if ($tempatLahir && $tanggalLahir) {
            return $tempatLahir . ', ' . $tanggalLahir;
        }

        return $tempatLahir ?? $tanggalLahir ?? '-';
    }

    private function formatGolonganPangkat(Peserta $peserta): string
    {
        $golongan = $peserta->golongan->golongan ?? null;
        $pangkat = $peserta->golongan->pangkat ?? null;

        if ($golongan && $pangkat) {
            return $golongan . ' / ' . $pangkat;
        }

        return $golongan ?? $pangkat ?? '-';
    }
}
