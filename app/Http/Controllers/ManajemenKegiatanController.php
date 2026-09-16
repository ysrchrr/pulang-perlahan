<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanKelas;
use App\Models\KegiatanPeran;
use App\Models\MasterJabatanKegiatan;
use App\Models\MasterJenisKegiatan;
use App\Models\MasterTemplateEvalNarasumber;
use App\Models\MasterTemplateEvalPenyelenggaraan;
use App\Models\MasterTimKerja;
use App\Models\Pegawai;
use App\Models\PegawaiTerpilih;
use App\Models\PesertaTerpilih;
use App\Models\Ref_Dokumen;
use App\Models\UnggahanDokumen;
use App\Models\V_peserta_terpilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ManajemenKegiatanController extends Controller
{
    protected $role_id;

    public function __construct()
    {
        $this->role_id = session('role_id');
    }

    private function generateKodeKegiatan()
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (Kegiatan::where('kode_kegiatan', $code)->exists());

        return $code;
    }

    private function normalizeBannerImages($bannerImages)
    {
        if (is_array($bannerImages)) {
            return array_values(array_filter($bannerImages));
        }

        if (is_string($bannerImages) && $bannerImages !== '') {
            $decoded = json_decode($bannerImages, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
        }

        return [];
    }

    private function uploadBannerImages(array $files)
    {
        $paths = [];

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $filename = 'kegiatan_banner_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $paths[] = $file->storeAs('kegiatan/banner', $filename, 'public');
        }

        return $paths;
    }

    private function deleteBannerImages(array $paths)
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function actionItem($label, $href = 'javascript:void(0)', $class = 'dropdown-item', $onClick = null)
    {
        $attrClick = $onClick ? ' onclick="' . $onClick . '"' : '';
        return '<a class="' . $class . '" href="' . $href . '"' . $attrClick . '>' . $label . '</a>';
    }

    private function actionItemsForKegiatan($status, $roleId, $encId)
    {
        $items = [];

        if ($status === 0) { // Draft
            if ($roleId === 4) {
                $items[] = $this->actionItem('Edit', 'javascript:void(0)', 'dropdown-item', 'toEdit(\'' . $encId . '\')');
                $items[] = $this->actionItem('Penugasan', route('kegiatan-peran', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Kirim Ke Kepegawaian', 'javascript:void(0)', 'dropdown-item', 'kirimKepegawaian(\'' . $encId . '\')');
                $items[] = '<div class="dropdown-divider"></div>';
                $items[] = $this->actionItem('Hapus', 'javascript:void(0)', 'dropdown-item text-danger', 'toRemove(\'' . $encId . '\')');
                return $items;
            }

            return [$this->actionItem('Tidak ada aksi')];
        }

        if ($status === 1) { // Diajukan ke Kepegawaian
            if ($roleId === 3 || $roleId === 4) {
                $items[] = $this->actionItem('Detail', 'javascript:void(0)', 'dropdown-item', 'toDetail(\'' . $encId . '\')');
                $items[] = $this->actionItem('Penugasan', route('kegiatan-peran', ['id_kegiatan' => $encId]));
                return $items;
            }

            return [$this->actionItem('Tidak ada aksi')];
        }

        if ($status === 2) { //Kegiatan Belum Dimulai
            if ($roleId === 4) {
                $items[] = $this->actionItem('Detail', 'javascript:void(0)', 'dropdown-item', 'toDetail(\'' . $encId . '\')');
                $items[] = $this->actionItem('Penugasan', route('kegiatan-peran', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Mulai Kegiatan', 'javascript:void(0)', 'dropdown-item', 'toMulaiKegiatan(\'' . $encId . '\')');
                return $items;
            }

            if ($roleId === 5) { //Panitia
                $items[] = $this->actionItem('Kelas', route('kegiatan-kelas-list', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Buka/Tutup Pendaftaran', 'javascript:void(0)', 'dropdown-item', 'modalStatusPendaftaran(\'' . $encId . '\')');
                $items[] = $this->actionItem('Verifikasi Peserta', route('kegiatan-verifikasi-pendaftaran', ['kode_kegiatan' => $encId]));
                return $items;
            }

            return [$this->actionItem('Tidak ada aksi')];
        }

        if ($status === 4) { //Kegiatan Berlangsung
            if ($roleId === 4) { //Admin Tim Kerja
                $items[] = $this->actionItem('Detail', 'javascript:void(0)', 'dropdown-item', 'toDetail(\'' . $encId . '\')');
                $items[] = $this->actionItem('Penugasan', route('kegiatan-peran', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Sertifikat', route('kegiatan-sertifikat', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Setting Evaluasi', 'javascript:void(0)', 'dropdown-item', 'modalSetEvaluasi(\'' . $encId . '\')');
                $items[] = $this->actionItem('Selesai Kegiatan', 'javascript:void(0)', 'dropdown-item', 'toEndKegiatan(\'' . $encId . '\')');
                return $items;
            }

            if ($roleId === 5) { //Panitia
                $items[] = $this->actionItem('Kelas', route('kegiatan-kelas-list', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Buka/Tutup Pendaftaran', 'javascript:void(0)', 'dropdown-item', 'modalStatusPendaftaran(\'' . $encId . '\')');
                $items[] = $this->actionItem('Setting Sertifikat', route('kegiatan-setting-sertifikat', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Sertifikat', route('kegiatan-sertifikat', ['id_kegiatan' => $encId]));
                $items[] = $this->actionItem('Foto Kegiatan', route('kegiatan-foto', ['id_kegiatan' => $encId]));
                return $items;
            }

            return [$this->actionItem('Tidak ada aksi')];
        }

        return [
            $this->actionItem('Detail', 'javascript:void(0)', 'dropdown-item', 'toDetail(\'' . $encId . '\')')
        ];
    }

    private function renderActionDropdown(array $items)
    {
        return '<div class="btn-group material-shadow">
            <button type="button" class="btn btn-sm btn-primary dropdown-toggle material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
            <div class="dropdown-menu">' . implode('', $items) . '</div>
        </div>';
    }

    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Kegiatan',
            'ref_eval_penyelenggaraan' => MasterTemplateEvalPenyelenggaraan::all(),
            'ref_eval_narasumber' => MasterTemplateEvalNarasumber::all()
        ];

        return view('kegiatan.index', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            /*
            Penjelasan Status Kegiatan
            0 = Draft
            1 = Diajukan ke Kepegawaian / Menunggu Verifikasi Kepegawaian
            2 = Disetujui Kepegawaian / Kegiatan Belum Dimulai / Panitia Open Pendaftaran
            3 = Diminta Revisi oleh Kepegawaian, kemudian diajukan ulang oleh Admin Tim Kerja
            4 = Kegiatan sedang berlangsung
            5 = Kegiatan selesai
            6 = Ditolak Kepegawaian
            */

            $data = Kegiatan::query();

            if ($this->role_id == 4) {
                // Admin Tim Kerja: lihat semua kegiatan
            } elseif ($this->role_id == 3) {
                $data->whereIn('status', ['1', '2', '3', '4', '5']);
            } elseif ($this->role_id == 5) {
                $pegawaiId = Pegawai::where('users_id', session('user_id'))->value('id');

                if (!$pegawaiId) {
                    $data->whereRaw('1 = 0');
                } else {
                    $data->whereExists(function ($query) use ($pegawaiId) {
                        $query->select(DB::raw(1))
                            ->from('pegawai_terpilih')
                            ->whereColumn('pegawai_terpilih.id_kegiatan', 'kegiatan.id')
                            ->where('pegawai_terpilih.id_pegawai', $pegawaiId);
                    });
                }
            } else {
                $data->whereRaw('1 = 0');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    $nama = $row->nama_kegiatan . '<br>';

                    if ($this->role_id === 5) {
                        $kode = '<span class="badge rounded-pill bg-success js-copy-kode-kegiatan" data-copy-text="' . e($row->kode_kegiatan) . '" style="cursor:pointer;">' . $row->kode_kegiatan . ' </span>';
                    } else {
                        $kode = '';
                    }

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
                    $statusKegiatan = statusKegiatan($row->status);
                    $statusPendaftaran = '';

                    if ($this->role_id == 5) {
                        if ($row->status_pendaftaran == '0') {
                            $statusPendaftaran = '<span class="badge rounded-pill bg-danger">Pendaftaran Ditutup</span>';
                        } else {
                            $statusPendaftaran = '<span class="badge rounded-pill bg-success">Pendaftaran Dibuka</span>';
                        }
                    }

                    return $statusKegiatan . '<br>' . $statusPendaftaran;
                })
                ->addColumn('action', function ($row) {
                    $id = enc_id($row->id);
                    $status = (int) $row->status;
                    $items = $this->actionItemsForKegiatan($status, (int) $this->role_id, $id);
                    return $this->renderActionDropdown($items);
                })
                ->rawColumns(['nama', 'informasi', 'action', 'status'])
                ->make(true);
        }
    }

    public function create(Request $request)
    {
        $id = $request->query('id');

        $data = [
            'page_title' => $id ? 'Edit Kegiatan' : 'Tambah Kegiatan',
            'ref_jenis_kegiatan' => MasterJenisKegiatan::all(),
            'ref_timkerja' => MasterTimKerja::all(),
            'id_kegiatan' => $id
        ];


        return view('kegiatan.form', $data);
    }

    public function store(Request $request)
    {
        $newBannerImages = [];
        $oldBannerImages = [];

        try {
            $id = $request->id_kegiatan ? dec_id($request->id_kegiatan) : null;
            $isNew = !$id;

            $rules = [
                'id_penanggungjawab' => 'required',
                'nama_kegiatan' => 'required|string|max:255',
                'jenis_kegiatan' => 'required',
                'sumber_dana' => 'required|string|max:50',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'lokasi_kegiatan' => 'required|string|max:255',
                'kuota_peserta' => 'required|integer|min:1',
                'deskripsi' => 'required|string',
            ];

            if ($isNew) {
                $rules['banner_img'] = 'required|array|min:1';
            } else {
                $rules['banner_img'] = 'nullable|array';
            }

            $rules['banner_img.*'] = 'image|mimes:jpg,jpeg,png|max:2048';

            $request->validate($rules);

            DB::transaction(function () use ($request, $id, $isNew, &$newBannerImages, &$oldBannerImages) {
                $kegiatan = $id ? Kegiatan::findOrFail($id) : new Kegiatan();

                $data = [
                    'id_penanggungjawab' => $request->id_penanggungjawab,
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'id_jenis_kegiatan' => $request->jenis_kegiatan,
                    'sumber_dana' => $request->sumber_dana,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'lokasi_kegiatan' => $request->lokasi_kegiatan,
                    'kuota_peserta' => $request->kuota_peserta,
                    'deskripsi' => $request->deskripsi,
                ];

                if ($isNew) {
                    $data['kode_kegiatan'] = $this->generateKodeKegiatan();
                    $data['status_pendaftaran'] = '0';
                    $data['created_by'] = session('user_id');
                    $data['status'] = '0';
                    $data['banner_img'] = $this->uploadBannerImages($request->file('banner_img', []));
                } else {
                    $data['status'] = in_array((string) $kegiatan->status, ['2', '4', '5', '6'], true) ? $kegiatan->status : '0';
                    if ($request->hasFile('banner_img')) {
                        $oldBannerImages = $this->normalizeBannerImages($kegiatan->banner_img);
                        $newBannerImages = $this->uploadBannerImages($request->file('banner_img', []));
                        $data['banner_img'] = $newBannerImages;
                    }
                }

                $kegiatan->fill($data);
                $kegiatan->save();

                if ($isNew) {
                    KegiatanKelas::create([
                        'id_kegiatan' => $kegiatan->id,
                        'nama_kelas' => 'Kelas A',
                        'kuota_kelas' => 0,
                        'status_pendaftaran' => '0'
                    ]);
                }
            });

            if (!empty($oldBannerImages)) {
                $this->deleteBannerImages($oldBannerImages);
            }

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil menyimpan data'
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            if (!empty($newBannerImages)) {
                $this->deleteBannerImages($newBannerImages);
            }

            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        $id = dec_id($request->input('id'));
        $data = Kegiatan::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function delete(Request $request)
    {
        try {
            $data = Kegiatan::findOrFail(dec_id($request->input('id')));
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

    public function kirimKepegawaian(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $idKegiatan = dec_id($request->input('id'));
            $kegiatan = Kegiatan::findOrFail($idKegiatan);

            $peranList = KegiatanPeran::where('id_kegiatan', $idKegiatan)->get();
            if ($peranList->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Peran kegiatan belum diatur'
                ], 422);
            }

            foreach ($peranList as $peran) {
                $kuota = (int) ($peran->kuota ?? 0);
                $jumlahSekarang = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                    ->where('id_jabatan_kegiatan', $peran->id_peran)
                    ->count();

                if ($kuota !== $jumlahSekarang) {
                    return response()->json([
                        'status' => false,
                        'msg' => 'Kuota pegawai belum terpenuhi untuk peran ' . ($peran->nama_peran ?? '-')
                    ], 422);
                }
            }

            $kegiatan->status = '1';
            $kegiatan->save();

            return response()->json([
                'status' => true,
                'msg' => 'Status kegiatan berhasil dikirim ke kepegawaian'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function kegiatanPeran($id_kegiatan)
    {
        $data = [
            'page_title' => 'Peran Dalam Kegiatan',
            'kegiatan' => Kegiatan::findOrFail(dec_id($id_kegiatan)),
            'id_kegiatan' => $id_kegiatan,
            'ref_jabatan' => MasterJabatanKegiatan::all()
        ];

        return view('kegiatan.kegiatan-peran', $data);
    }

    public function startKegiatan(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $kegiatan = Kegiatan::findOrFail(dec_id($request->input('id')));

            if ((string) $kegiatan->status !== '2') {
                return response()->json([
                    'status' => false,
                    'msg' => 'Status kegiatan tidak valid untuk dimulai'
                ], 422);
            }

            $kegiatan->status = '4';
            $kegiatan->save();

            return response()->json([
                'status' => true,
                'msg' => 'Kegiatan berhasil dimulai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function kegiatanPeranGetData($id_kegiatan, Request $request)
    {
        if ($request->ajax()) {
            $data = KegiatanPeran::query()
                ->where('kegiatan_peran.id_kegiatan', dec_id($id_kegiatan))
                ->leftJoin('kegiatan', 'kegiatan.id', '=', 'kegiatan_peran.id_kegiatan')
                ->select('kegiatan_peran.*', 'kegiatan.status as status_kegiatan');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->jabatan ? $row->jabatan->nama_jabatan : '-';
                })
                ->addColumn('jumlah', function ($row) {
                    $kuota = $row->kuota ?? 0;
                    $jumlahSekarang = PegawaiTerpilih::where('id_kegiatan', $row->id_kegiatan)
                        ->where('id_jabatan_kegiatan', $row->id_peran)
                        ->count();
                    $class = $kuota != $jumlahSekarang ? 'text-danger' : '';

                    return '<span class="' . $class . '">' . $kuota . ' / ' . $jumlahSekarang . ' Orang</span>';
                })
                ->addColumn('action', function ($row) {
                    $action = '
                    <a class="btn btn-sm btn-primary" href="' . route('kegiatan-peran-pegawai', ['id_kegiatan' => enc_id($row->id_kegiatan), 'id_peran' => enc_id($row->id_peran)]) . '">
                        <i class="fa-solid fa-user-plus"></i> Plot Pegawai
                    </a>
                    ';

                    if ((string) $row->status_kegiatan === '0') {
                        $action .= '
                        <button class="btn btn-sm btn-warning" onclick="toEdit(\'' . enc_id($row->id) . '\')">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                        ';
                    }

                    return $action;
                })
                ->rawColumns(['jumlah', 'action'])
                ->make(true);
        }
    }

    public function kegiatanPeranStore(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
            'id_peran' => 'required|exists:ref_jabatan_kegiatan,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        try {
            $id = $request->id_kegiatan_peran ? dec_id($request->id_kegiatan_peran) : null;
            $idKegiatan = dec_id($request->id_kegiatan);

            $data = $id ? KegiatanPeran::findOrFail($id) : new KegiatanPeran();
            $data->id_kegiatan = $idKegiatan;
            $data->id_peran = $request->id_peran;
            $data->nama_peran = $data->jabatan ? $data->jabatan->nama_jabatan : '-';
            $data->kuota = $request->jumlah;
            $data->created_by = session('user_id');
            $data->save();

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil menyimpan data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function kegiatanPeranDetail(Request $request)
    {
        $data = KegiatanPeran::find(dec_id($request->input('id')));

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function kegiatanPeranDelete(Request $request)
    {
        try {
            $data = KegiatanPeran::findOrFail(dec_id($request->input('id')));
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

    public function kegiatanPeranPegawai($id_kegiatan, $id_peran)
    {
        $idKegiatan = dec_id($id_kegiatan);
        $idPeran = dec_id($id_peran);

        $data = [
            'page_title' => 'Plot Pegawai Peran Kegiatan',
            'kegiatan' => Kegiatan::findOrFail($idKegiatan),
            'peran' => KegiatanPeran::where('id_kegiatan', $idKegiatan)
                ->where('id_peran', $idPeran)
                ->firstOrFail(),
            'id_kegiatan' => $id_kegiatan,
            'id_peran' => $id_peran
        ];

        return view('kegiatan.kegiatan-peran-pegawai', $data);
    }

    public function kegiatanPeranPegawaiPilih($id_kegiatan, $id_peran)
    {
        $idKegiatan = dec_id($id_kegiatan);
        $idPeran = dec_id($id_peran);
        $peran = KegiatanPeran::where('id_kegiatan', $idKegiatan)
            ->where('id_peran', $idPeran)
            ->firstOrFail();
        $jumlahTerplot = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
            ->where('id_jabatan_kegiatan', $idPeran)
            ->count();

        $data = [
            'page_title' => 'Pilih Pegawai',
            'kegiatan' => Kegiatan::findOrFail($idKegiatan),
            'peran' => $peran,
            'jumlah_terplot' => $jumlahTerplot,
            'id_kegiatan' => $id_kegiatan,
            'id_peran' => $id_peran
        ];

        return view('kegiatan.kegiatan-peran-pegawai-pilih', $data);
    }

    public function kegiatanPeranPegawaiGetData($id_kegiatan, $id_peran, Request $request)
    {
        if ($request->ajax()) {
            $idKegiatan = dec_id($id_kegiatan);
            $idPeran = dec_id($id_peran);
            $data = PegawaiTerpilih::query()
                ->leftJoin('pegawai', 'pegawai.id', '=', 'pegawai_terpilih.id_pegawai')
                ->leftJoin('ref_jabatan_kegiatan', 'ref_jabatan_kegiatan.id', '=', 'pegawai_terpilih.id_jabatan_kegiatan')
                ->where('pegawai_terpilih.id_kegiatan', $idKegiatan)
                ->where('pegawai_terpilih.id_jabatan_kegiatan', $idPeran)
                ->select('pegawai_terpilih.*', 'pegawai.nama', 'pegawai.nip', 'pegawai.email', 'pegawai.no_hp', 'ref_jabatan_kegiatan.nama_jabatan');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pegawai', function ($row) {
                    return ($row->nama ?? '-') . '<br><small class="text-muted">' . ($row->nip ?? '-') . '</small>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                    ';
                })
                ->addColumn('kontak', function ($row) {
                    return '<div><small class="text-muted">Email:</small> ' . ($row->email ?? '-') . '</div>
                    <div><small class="text-muted">No HP:</small> ' . ($row->no_hp ?? '-') . '</div>';
                })
                ->rawColumns(['pegawai', 'action', 'kontak'])
                ->make(true);
        }
    }

    public function kegiatanPeranPegawaiStore(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required',
            'id_peran' => 'required',
            'id_pegawai' => 'required|array|min:1',
            'id_pegawai.*' => 'exists:pegawai,id'
        ]);

        try {
            $idKegiatan = dec_id($request->id_kegiatan);
            $idPeran = dec_id($request->id_peran);
            $peran = KegiatanPeran::where('id_kegiatan', $idKegiatan)
                ->where('id_peran', $idPeran)
                ->firstOrFail();
            $kuota = (int) ($peran->kuota ?? 0);
            $jumlahTerplot = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                ->where('id_jabatan_kegiatan', $idPeran)
                ->count();

            if ($jumlahTerplot >= $kuota) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Kuota peran sudah penuh'
                ], 422);
            }

            $inserted = 0;
            $sisaKapasitas = $kuota - $jumlahTerplot;

            foreach ($request->id_pegawai as $idPegawai) {
                if ($inserted >= $sisaKapasitas) {
                    break;
                }

                $exists = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                    ->where('id_jabatan_kegiatan', $idPeran)
                    ->where('id_pegawai', $idPegawai)
                    ->exists();

                if ($exists) {
                    continue;
                }

                PegawaiTerpilih::create([
                    'id_pegawai' => $idPegawai,
                    'id_kegiatan' => $idKegiatan,
                    'id_jabatan_kegiatan' => $idPeran,
                    'created_by' => session('user_id')
                ]);

                $inserted++;
            }

            if ($inserted === 0) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Tidak ada data baru, semua pegawai sudah terplot'
                ], 422);
            }

            return response()->json([
                'status' => true,
                'msg' => 'Berhasil plot ' . $inserted . ' pegawai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function kegiatanPeranPegawaiPilihGetData($id_kegiatan, $id_peran, Request $request)
    {
        if ($request->ajax()) {
            $idKegiatan = dec_id($id_kegiatan);
            $kegiatan = Kegiatan::findOrFail($idKegiatan);
            $idPeran = dec_id($id_peran);

            $data = Pegawai::query()
                ->leftJoin('ref_jabatan', 'ref_jabatan.id', '=', 'pegawai.id_jabatan')
                ->where('pegawai.is_active', '1')
                ->select(
                    'pegawai.id',
                    'pegawai.nama',
                    'pegawai.no_hp',
                    'pegawai.nip',
                    'pegawai.instansi_pendidikan',
                    'ref_jabatan.nama as nama_jabatan'
                )
                ->orderBy('pegawai.nama', 'asc');

            return DataTables::of($data)
                ->addColumn('checkbox', function ($row) use ($idKegiatan, $idPeran) {
                    $exists = PegawaiTerpilih::where('id_kegiatan', $idKegiatan)
                        ->where('id_jabatan_kegiatan', $idPeran)
                        ->where('id_pegawai', $row->id)
                        ->exists();

                    if ($exists) {
                        return '<input type="checkbox" disabled checked>';
                    }

                    return '<input type="checkbox" class="pegawai-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('jabatan', function ($row) {
                    return $row->nama_jabatan ?? '-';
                })
                ->addColumn('keterangan', function ($row) use ($kegiatan, $idKegiatan) {
                    $irisan = PegawaiTerpilih::query()
                        ->join('kegiatan', 'kegiatan.id', '=', 'pegawai_terpilih.id_kegiatan')
                        ->where('pegawai_terpilih.id_pegawai', $row->id)
                        ->where('pegawai_terpilih.id_kegiatan', '!=', $idKegiatan)
                        ->whereDate('kegiatan.tanggal_mulai', '<=', $kegiatan->tanggal_selesai)
                        ->whereDate('kegiatan.tanggal_selesai', '>=', $kegiatan->tanggal_mulai)
                        ->select(
                            'kegiatan.id',
                            'kegiatan.nama_kegiatan',
                            'kegiatan.tanggal_mulai',
                            'kegiatan.tanggal_selesai',
                            'kegiatan.lokasi_kegiatan'
                        )
                        ->groupBy(
                            'kegiatan.id',
                            'kegiatan.nama_kegiatan',
                            'kegiatan.tanggal_mulai',
                            'kegiatan.tanggal_selesai',
                            'kegiatan.lokasi_kegiatan'
                        )
                        ->get();

                    $count = $irisan->count();
                    if ($count === 0) {
                        return '';
                    }

                    $details = $irisan->map(function ($item) {
                        return [
                            'nama_kegiatan' => $item->nama_kegiatan,
                            'tanggal_mulai' => $item->tanggal_mulai,
                            'tanggal_selesai' => $item->tanggal_selesai,
                            'lokasi_kegiatan' => $item->lokasi_kegiatan
                        ];
                    })->values()->toJson();

                    return '<span class="badge rounded-pill bg-danger btn-kegiatan-irisan" style="cursor:pointer" data-details=\'' . e($details) . '\'>' . $count . ' Kegiatan Beririsan</span>';
                })
                ->rawColumns(['checkbox', 'keterangan'])
                ->make(true);
        }
    }

    public function kegiatanPeranPegawaiDelete(Request $request)
    {
        try {
            $data = PegawaiTerpilih::with('kegiatan')->findOrFail(dec_id($request->input('id')));

            if ((string) optional($data->kegiatan)->status !== '0') {
                return response()->json([
                    'status' => false,
                    'msg' => 'Status kegiatan sudah diajukan'
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

    public function getStatusPendaftaran(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required_without:id',
            'id' => 'required_without:id_kegiatan'
        ]);

        try {
            $encId = $request->input('id_kegiatan', $request->input('id'));
            $kegiatan = Kegiatan::findOrFail(dec_id($encId));

            return response()->json([
                'status' => true,
                'data' => [
                    'status_pendaftaran' => (string) $kegiatan->status_pendaftaran,
                    'qr_pendaftaran' => $kegiatan->qr_pendaftaran,
                    'url_enrollment' => route('kegiatan-enrollment', ['kode_kegiatan' => $kegiatan->kode_kegiatan])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function storeStatusPendaftaran(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required_without:id',
            'id' => 'required_without:id_kegiatan',
            'status_pendaftaran' => 'required|in:0,1'
        ]);

        try {
            $encId = $request->input('id_kegiatan', $request->input('id'));
            $kegiatan = Kegiatan::findOrFail(dec_id($encId));
            $kegiatan->status_pendaftaran = $request->status_pendaftaran;

            if ((string) $request->status_pendaftaran === '1' && empty($kegiatan->qr_pendaftaran)) {
                $urlEnrollment = route('kegiatan-enrollment', ['kode_kegiatan' => $kegiatan->kode_kegiatan]);
                $kegiatan->qr_pendaftaran = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($urlEnrollment);
            }

            $kegiatan->save();

            return response()->json([
                'status' => true,
                'msg' => 'Status pendaftaran berhasil diperbarui',
                'data' => [
                    'status_pendaftaran' => (string) $kegiatan->status_pendaftaran,
                    'qr_pendaftaran' => $kegiatan->qr_pendaftaran,
                    'url_enrollment' => route('kegiatan-enrollment', ['kode_kegiatan' => $kegiatan->kode_kegiatan])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function verifikasiPendaftaran($id_kegiatan)
    {
        $data = [
            'page_title' => 'Verifikasi Pendaftaran',
            'kegiatan' => Kegiatan::findOrFail(dec_id($id_kegiatan)),
            'id_kegiatan' => $id_kegiatan,
            'calon_peserta_count' => V_peserta_terpilih::where('id_kegiatan', dec_id($id_kegiatan))->where('status', '0')->count(),
            'peserta_count' => V_peserta_terpilih::where('id_kegiatan', dec_id($id_kegiatan))->where('status', '1')->count(),
            'kelas_list' => KegiatanKelas::where('id_kegiatan', dec_id($id_kegiatan))->get()
        ];

        return view('kegiatan.verifikasi-pendaftaran', $data);
    }

    public function getDataPendaftar(Request $request, $id_kegiatan)
    {
        $idKegiatan = dec_id($id_kegiatan);
        if ($request->ajax()) {
            $data = V_peserta_terpilih::where('id_kegiatan', $idKegiatan);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    $nama = $row->nama_lengkap;

                    if (empty($row->id_kelas)) {
                        $kelas = '<span class="badge rounded-pill bg-danger">Belum Mendapat Kelas</span>';
                    } else {
                        $kelas = '<span class="badge rounded-pill bg-success">' . ($row->nama_kelas ?? '-') . '</span>';
                    }

                    return $nama . '<br>' . $kelas;
                })
                ->addColumn('kontak', function ($row) {
                    $email = '<span class="badge rounded-pill bg-secondary">' . $row->email . '</span>';
                    $no_hp = '<span class="badge rounded-pill bg-success">' . $row->no_hp . '</span>';

                    return $email . '<br>' . $no_hp;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == '0') {
                        return '<span class="badge bg-warning">Calon Peserta</span>';
                    } else if ($row->status == '1') {
                        return '<span class="badge bg-success">Peserta</span>';
                    } else if ($row->status == '2') {
                        return '<span class="badge bg-danger">Ditolak</span>';
                    } else if ($row->status == '3') {
                        return '<span class="badge bg-danger">Mengundurkan Diri</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-primary" onclick="getBerkas(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-file-alt"></i> Berkas
                    </button>
                    <button class="btn btn-sm btn-success" onclick="verifikasiPendaftaran(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-check"></i> Verifikasi
                    </button>
                    ';
                })
                ->rawColumns(['nama', 'kontak', 'status', 'action'])
                ->make(true);
        }
    }

    public function getBerkasPendaftar($id_peserta_terpilih)
    {
        try {
            $idPesertaTerpilih = dec_id($id_peserta_terpilih);
            $pesertaTerpilih = PesertaTerpilih::findOrFail($idPesertaTerpilih);
            $unggahanDokumen = UnggahanDokumen::where('id_peserta_terpilih', $pesertaTerpilih->id)
                ->get()
                ->keyBy('id_ref_dokumen');

            $data = Ref_Dokumen::where('is_active', '1')
                ->where('role_id', 7)
                ->get()
                ->map(function ($item) use ($unggahanDokumen) {
                    $dokumen = $unggahanDokumen[$item->id] ?? null;

                    return [
                        'nama_dokumen' => $item->nama_dokumen,
                        'path_dokumen' => $dokumen->path_dokumen ?? null,
                        'url' => !empty($dokumen->path_dokumen) ? asset('storage/' . $dokumen->path_dokumen) : null,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Berkas pendaftaran tidak ditemukan',
            ], 404);
        }
    }

    public function storeVerifikasiPendaftaran(Request $request)
    {
        $validated = $request->validate([
            'id_peserta_terpilih' => 'required',
            'status_pendaftaran' => 'required|in:1,2',
            'kelas_id' => 'nullable'
        ]);

        try {
            $idPesertaTerpilih = dec_id($validated['id_peserta_terpilih']);
            $pesertaTerpilih = PesertaTerpilih::findOrFail($idPesertaTerpilih);

            $pesertaTerpilih->status = $validated['status_pendaftaran'];

            if ($validated['status_pendaftaran'] == '1') {
                $pesertaTerpilih->id_kelas = $validated['kelas_id'] ?: null;
            } else {
                $pesertaTerpilih->id_kelas = null;
            }

            $pesertaTerpilih->verified_by = session('user_id');
            $pesertaTerpilih->verified_at = now();

            $pesertaTerpilih->save();

            return response()->json([
                'status' => true,
                'msg' => 'Verifikasi pendaftaran berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function detailEvaluasi(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $idKegiatan = dec_id($request->input('id'));
            $kegiatan = Kegiatan::findOrFail($idKegiatan);

            return response()->json([
                'status' => true,
                'data' => [
                    'id_eval_penyelenggaraan' => $kegiatan->id_eval_penyelenggaraan,
                    'id_eval_narasumber' => $kegiatan->id_eval_narasumber
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function storeSettingEvaluasi(Request $request)
    {
        $request->validate([
            'id_kegiatan_evaluasi' => 'required',
            'id_eval_penyelenggaraan' => 'nullable|exists:master_template_evaluasi_penyelenggaraan,id',
            'id_eval_narasumber' => 'nullable|exists:master_template_evaluasi_narasumber,id'
        ]);

        try {
            $idKegiatan = dec_id($request->input('id_kegiatan_evaluasi'));
            $kegiatan = Kegiatan::findOrFail($idKegiatan);

            $kegiatan->id_eval_penyelenggaraan = $request->input('id_eval_penyelenggaraan') ?: null;
            $kegiatan->id_eval_narasumber = $request->input('id_eval_narasumber') ?: null;
            $kegiatan->save();

            return response()->json([
                'status' => true,
                'msg' => 'Setting evaluasi berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
