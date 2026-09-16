<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PegawaiTerpilih;
use App\Models\UserRole;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPenugasanController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Verifikasi Penugasan'
        ];

        return view('verifikasi_penugasan.index', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Kegiatan::whereIn('status', ['1', '2', '3', '6'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_kegiatan', function ($row) {
                    $nama = $row->nama_kegiatan . '<br>';
                    $kode = '<span class="badge rounded-pill bg-success">' . $row->kode_kegiatan . ' </span>';

                    return $nama . $kode;
                })
                ->addColumn('periode_pelaksanaan', function ($row) {
                    return dateRangeIndo($row->tanggal_mulai, $row->tanggal_selesai);
                })
                ->addColumn('jenis_kegiatan', function ($row) {
                    return $row->jenisKegiatan ? $row->jenisKegiatan->nama_jenis_kegiatan : '-';
                })
                ->addColumn('informasi', function ($row) {
                    $peserta = '<span class="badge rounded-pill bg-primary">Kuota Peserta : ' . $row->kuota_peserta . '</span><br>';
                    $jumlahPegawai = PegawaiTerpilih::where('id_kegiatan', $row->id)->count();
                    $pegawai = '<span class="badge rounded-pill bg-info">Pegawai Ditugaskan : ' . $jumlahPegawai . ' </span>';
                    return $peserta . $pegawai;
                })
                ->addColumn('status', function ($row) {
                    return statusKegiatan($row->status);
                })
                ->addColumn('action', function ($row) {
                    $id = enc_id($row->id);
                    $status = (int) $row->status;

                    $suratTugasStatus = [2, 3, 5]; // Status yang menampilkan tombol Surat Tugas

                    if ($status === 1) {
                        return '<a href="' . route('verifikasi-penugasan-process', ['id' => $id]) . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-check"></i> Verifikasi Penugasan
                        </a>';
                    } else if (in_array($status, $suratTugasStatus)) {
                        return '<a href="' . route('verifikasi-penugasan-generate-st', ['id' => $id]) . '" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Surat Tugas
                        </a>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['nama_kegiatan', 'informasi', 'action', 'status'])
                ->make(true);
        }
    }

    public function verifikasiProses($enc_id)
    {
        $data = [
            'page_title' => 'Verifikasi Kegiatan',
            'id_kegiatan' => $enc_id,
            'kegiatan' => Kegiatan::findOrFail(dec_id($enc_id)),
            'list_pegawai_terpilih' => PegawaiTerpilih::with('pegawai', 'jabatan')->where('id_kegiatan', dec_id($enc_id))->get()
        ];

        return view('verifikasi_penugasan.verifikasi', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
            'hasil_keputusan' => 'required'
        ]);

        try {
            if ($request->hasil_keputusan == '6' || $request->hasil_keputusan == '3') { //Tolak atau Revisi update status aja
                $kegiatan = Kegiatan::findOrFail(dec_id($request->id_kegiatan));
                $kegiatan->status = $request->hasil_keputusan;
                if ($request->filled('catatan')) {
                    $kegiatan->catatan_kepegawaian = $request->catatan;
                }
                $kegiatan->save();
            } else { //Kalau disetujui, update status dan synchRolePegawaiTerpilih
                $idKegiatan = dec_id($request->id_kegiatan);
                $listPegawaiTerpilih = PegawaiTerpilih::with('pegawai')->where('id_kegiatan', $idKegiatan)->get();
                $jabatanToRole = [
                    1 => 8, // Narasumber
                    2 => 5, // Panitia
                    3 => 7, // Peserta
                ];
                $jabatanLabel = [
                    1 => 'Narasumber',
                    2 => 'Panitia',
                    3 => 'Peserta',
                ];
                $jumlahAkses = [
                    'Narasumber' => 0,
                    'Panitia' => 0,
                    'Peserta' => 0,
                ];

                foreach ($listPegawaiTerpilih as $pegawaiTerpilih) {
                    $usersId = $pegawaiTerpilih->pegawai->users_id ?? null;
                    $jabatanId = (int) $pegawaiTerpilih->id_jabatan_kegiatan;

                    if (!$usersId || !isset($jabatanToRole[$jabatanId])) {
                        continue;
                    }

                    $userRole = UserRole::firstOrCreate(
                        [
                            'users_id' => $usersId,
                            'roles_id' => $jabatanToRole[$jabatanId],
                        ]
                    );

                    if ($userRole->wasRecentlyCreated) {
                        $jumlahAkses[$jabatanLabel[$jabatanId]]++;
                    }
                }

                $kegiatan = Kegiatan::findOrFail($idKegiatan);
                $kegiatan->status = $request->hasil_keputusan;
                if ($request->filled('catatan')) {
                    $kegiatan->catatan_kepegawaian = $request->catatan;
                }
                $kegiatan->save();

                $msgAkses = sprintf(
                    'Berhasil Verifikasi Penugasan. Akses ditambahkan: %d Narasumber, %d Panitia, %d Peserta.',
                    $jumlahAkses['Narasumber'],
                    $jumlahAkses['Panitia'],
                    $jumlahAkses['Peserta']
                );
            }

            return response()->json([
                'status' => true,
                'msg' => $msgAkses ?? 'Berhasil Verifikasi Penugasan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function generateSuratTugas($enc_id)
    {
        $idKegiatan = dec_id($enc_id);
        $kegiatan = Kegiatan::findOrFail($idKegiatan);

        $listPegawaiTerpilih = PegawaiTerpilih::with(['pegawai.golongan', 'jabatan', 'peran'])
            ->where('id_kegiatan', $idKegiatan)
            ->get();

        $templatePath = storage_path('app/templates/template_surat_tugas.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Template surat tugas tidak ditemukan.');
        }

        $preparedTemplatePath = $this->prepareTemplateForPhpWord($templatePath);
        $templateProcessor = new TemplateProcessor($preparedTemplatePath);
        $templateProcessor->setValue('nama_kegiatan', $kegiatan->nama_kegiatan ?? '-');
        $templateProcessor->setValue('lokasi_kegiatan', $kegiatan->lokasi_kegiatan ?? '-');
        $templateProcessor->setValue(
            'rentang_tanggal_kegiatan',
            dateRangeIndo($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai) ?: '-'
        );

        if ($listPegawaiTerpilih->isEmpty()) {
            $templateProcessor->cloneRow('nama_pegawai_terpilih', 1);
            $templateProcessor->setValue('nomor_pegawai_terpilih#1', '1');
            $templateProcessor->setValue('nama_pegawai_terpilih#1', '-');
            $templateProcessor->setValue('nip_pegawai_terpilih#1', '-');
            $templateProcessor->setValue('golongan_pegawai_terpilih#1', '-');
            $templateProcessor->setValue('jabatan_pada_kegiatan#1', '-');
        } else {
            $templateProcessor->cloneRow('nama_pegawai_terpilih', $listPegawaiTerpilih->count());

            foreach ($listPegawaiTerpilih as $index => $item) {
                $noUrut = $index + 1;
                $templateProcessor->setValue('nomor_pegawai_terpilih#' . $noUrut, (string) $noUrut);
                $templateProcessor->setValue('nama_pegawai_terpilih#' . $noUrut, $item->pegawai->nama ?? '-');
                $templateProcessor->setValue('nip_pegawai_terpilih#' . $noUrut, $item->pegawai->nip ?? '-');
                $templateProcessor->setValue(
                    'golongan_pegawai_terpilih#' . $noUrut,
                    $item->pegawai->golongan->golongan ?? '-'
                );
                $templateProcessor->setValue('jabatan_pada_kegiatan#' . $noUrut, $item->jabatan->nama_jabatan ?? '-');
            }
        }

        $fileName = 'Surat Tugas ' . $kegiatan->kode_kegiatan . '-' . date('his') . '.docx';
        $outputPath = storage_path('app/temp/' . uniqid('surat_tugas_') . '.docx');

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $templateProcessor->saveAs($outputPath);

        if (file_exists($preparedTemplatePath)) {
            @unlink($preparedTemplatePath);
        }

        return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
    }

    private function prepareTemplateForPhpWord(string $templatePath): string
    {
        $tempTemplatePath = storage_path('app/temp/' . uniqid('template_st_') . '.docx');

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

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
            '$lokasi_kegiatan' => '${lokasi_kegiatan}',
            '$rentang_tanggal_kegiatan' => '${rentang_tanggal_kegiatan}',
            '$nama_pegawai_terpilih' => '${nama_pegawai_terpilih}',
            '$nip_pegawai_terpilih' => '${nip_pegawai_terpilih}',
            '$golongan_pegawai_terpilih' => '${golongan_pegawai_terpilih}',
            '$jabatan_pada_kegiatan' => '${jabatan_pada_kegiatan}',
        ];

        $documentXml = str_replace(array_keys($replacements), array_values($replacements), $documentXml);

        if (strpos($documentXml, '${nomor_pegawai_terpilih}') === false) {
            $documentXml = preg_replace_callback(
                '/<w:tr\b[^>]*>.*?\$\{nama_pegawai_terpilih\}.*?<\/w:tr>/s',
                function ($matches) {
                    return preg_replace(
                        '/<w:t>1<\/w:t>/',
                        '<w:t>${nomor_pegawai_terpilih}</w:t>',
                        $matches[0],
                        1
                    );
                },
                $documentXml,
                1
            );
        }

        $zip->addFromString('word/document.xml', $documentXml);
        $zip->close();

        return $tempTemplatePath;
    }
}
