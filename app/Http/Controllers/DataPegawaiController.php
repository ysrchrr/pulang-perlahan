<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Ref_Golongan;
use App\Models\Ref_Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class DataPegawaiController extends Controller
{
    private function validatePegawai(Request $request)
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jk' => 'nullable|in:L,P',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:30',
            'id_jabatan' => 'nullable',
            'id_golongan' => 'nullable',
            'instansi_pendidikan' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:4',
            'jenjang' => 'nullable|string|max:20',
        ]);
    }

    public function index()
    {
        $data = [
            'page_title' => 'Data Pegawai'
        ];

        return view('data_pegawai.index', $data);
    }

    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/Template-Import-Pegawai.xlsx');

        if (!File::exists($filePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        return response()->download($filePath, 'Template-Import-Pegawai.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $sheet = IOFactory::load($request->file('file')->getPathname())->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();

            $inserted = 0;
            $skipped = [];

            for ($row = 3; $row <= $highestRow; $row++) {
                $nama = trim((string) $sheet->getCell('A' . $row)->getValue());
                $nip = trim((string) $sheet->getCell('B' . $row)->getValue());
                $nik = trim((string) $sheet->getCell('C' . $row)->getValue());
                $tempatLahir = trim((string) $sheet->getCell('D' . $row)->getValue());
                $tanggalLahir = trim((string) $sheet->getCell('E' . $row)->getFormattedValue());
                $jk = strtoupper(trim((string) $sheet->getCell('F' . $row)->getValue()));
                $email = strtolower(trim((string) $sheet->getCell('G' . $row)->getValue()));
                $noHp = trim((string) $sheet->getCell('H' . $row)->getValue());
                $idJabatan = trim((string) $sheet->getCell('I' . $row)->getValue());
                $idGolongan = trim((string) $sheet->getCell('J' . $row)->getValue());
                $instansiPendidikan = trim((string) $sheet->getCell('K' . $row)->getValue());
                $tahunLulus = trim((string) $sheet->getCell('L' . $row)->getValue());
                $jenjang = strtoupper(trim((string) $sheet->getCell('M' . $row)->getValue()));

                if ($nama === '' && $email === '' && $nip === '' && $nik === '') {
                    continue;
                }

                if ($email === '') {
                    $skipped[] = "Baris {$row} ({$nama}): email kosong.";
                    continue;
                }

                if (User::where('email', $email)->exists()) {
                    $skipped[] = "Baris {$row} ({$nama}): email {$email} sudah digunakan, silakan perbaiki email.";
                    continue;
                }

                try {
                    DB::transaction(function () use (
                        $nama,
                        $nip,
                        $nik,
                        $tempatLahir,
                        $tanggalLahir,
                        $jk,
                        $email,
                        $noHp,
                        $idJabatan,
                        $idGolongan,
                        $instansiPendidikan,
                        $tahunLulus,
                        $jenjang,
                        &$inserted
                    ) {
                        $pegawai = Pegawai::create([
                            'nama' => $nama,
                            'nip' => $nip !== '' ? $nip : null,
                            'nik' => $nik !== '' ? $nik : null,
                            'tempat_lahir' => $tempatLahir !== '' ? $tempatLahir : null,
                            'tanggal_lahir' => $tanggalLahir !== '' ? $tanggalLahir : null,
                            'jk' => in_array($jk, ['L', 'P']) ? $jk : null,
                            'email' => $email,
                            'no_hp' => $noHp !== '' ? $noHp : null,
                            'id_jabatan' => $idJabatan !== '' ? $idJabatan : null,
                            'id_golongan' => $idGolongan !== '' ? $idGolongan : null,
                            'instansi_pendidikan' => $instansiPendidikan !== '' ? $instansiPendidikan : null,
                            'tahun_lulus' => $tahunLulus !== '' ? $tahunLulus : null,
                            'jenjang' => $jenjang !== '' ? $jenjang : null,
                            'created_by' => session('user_id'),
                        ]);

                        User::createFromPegawai($pegawai, 6);
                        $inserted++;
                    });
                } catch (\Exception $e) {
                    $skipped[] = "Baris {$row} ({$nama}): {$e->getMessage()}";
                }
            }

            $message = "Berhasil import {$inserted} data.";
            if (count($skipped) > 0) {
                $message .= ' ' . count($skipped) . ' data dilewati: ' . implode(' | ', $skipped);
            }

            return response()->json([
                'status' => true,
                'msg' => $message,
                'inserted' => $inserted,
                'skipped' => $skipped
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Pegawai::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    $nama = $row->nama ?? '-';

                    $no_hp = '<span class="badge rounded-pill bg-success-subtle text-success">' . $row->no_hp . '</span>';
                    $email = '<span class="badge rounded-pill bg-danger-subtle text-danger">' . $row->instansi_pendidikan . '</span>';

                    return $nama . '<br>' . $no_hp . $email;
                })
                ->editColumn('nip', function ($row) {
                    $nip = trim((string) ($row->nip ?? ''));
                    return ($nip === '' || $nip === '0') ? '-' : $nip;
                })
                ->addColumn('nik', function ($row) {
                    return maskString($row->nik);
                })
                ->addColumn('creator', function ($row) {
                    return $row->creator ? $row->creator->name : '-';
                })
                ->addColumn('keaktifan', function ($row) {
                    $checked = (string) ($row->is_active ?? '0') === '1' ? 'checked' : '';
                    $encId = enc_id($row->id);

                    return  '<div class="d-flex justify-content-center align-items-center">
                                <div class="form-check form-switch form-switch-md mb-0" style="padding-left:0;">
                                <input type="checkbox" class="form-check-input switch-keaktifan m-0" id="customSwitchsizemd-' . $row->id . '" data-id="' . $encId . '" ' . $checked . '>
                                </div>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    if ($row->users_id == null) {
                        $btnUser = '<button class="btn btn-sm btn-primary" onclick="createAccount(this, \'' . enc_id($row->id) . '\')">
                            <i class="fa-solid fa-user-plus"></i> Buat Akun
                        </button>';
                    } else {
                        $btnUser = '<button class="btn btn-sm btn-success" onclick="sendAccount(\'' . enc_id($row->id) . '\')">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Akun
                        </button>';
                    }
                    return $btnUser . '
                    <button class="btn btn-sm btn-warning" onclick="toEdit(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                    ';
                })
                ->rawColumns(['nama', 'action', 'creator', 'keaktifan'])
                ->make(true);
        }
    }

    public function create()
    {
        $data = [
            'page_title' => 'Tambah Data Pegawai',
            'ref_golongan' => Ref_Golongan::all(),
            'ref_jabatan' => Ref_Jabatan::all(),
            'pegawai' => null,
            'is_edit' => false,
        ];

        return view('data_pegawai.create', $data);
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail(dec_id($id));
        $data = [
            'page_title' => 'Edit Data Pegawai',
            'ref_golongan' => Ref_Golongan::all(),
            'ref_jabatan' => Ref_Jabatan::all(),
            'pegawai' => $pegawai,
            'is_edit' => true,
            'enc_id' => $id,
        ];

        return view('data_pegawai.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePegawai($request);

        try {
            DB::transaction(function () use ($validated) {
                $pegawai = Pegawai::create([
                    'nama' => $validated['nama'],
                    'nip' => $validated['nip'] ?? null,
                    'nik' => $validated['nik'] ?? null,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'jk' => $validated['jk'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                    'id_jabatan' => $validated['id_jabatan'] ?? null,
                    'id_golongan' => $validated['id_golongan'] ?? null,
                    'instansi_pendidikan' => $validated['instansi_pendidikan'] ?? null,
                    'tahun_lulus' => $validated['tahun_lulus'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                    'created_by' => session('user_id'),
                ]);

                User::createFromPegawai($pegawai, 6);
            });

            return redirect()->route('data-pegawai')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validated = $this->validatePegawai($request);

        try {
            DB::transaction(function () use ($request, $validated) {
                $pegawai = Pegawai::findOrFail(dec_id($request->input('id')));
                $pegawai->update([
                    'nama' => $validated['nama'],
                    'nip' => $validated['nip'] ?? null,
                    'nik' => $validated['nik'] ?? null,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'jk' => $validated['jk'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                    'id_jabatan' => $validated['id_jabatan'] ?? null,
                    'id_golongan' => $validated['id_golongan'] ?? null,
                    'instansi_pendidikan' => $validated['instansi_pendidikan'] ?? null,
                    'tahun_lulus' => $validated['tahun_lulus'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                ]);

                if (!empty($pegawai->users_id)) {
                    $user = User::find($pegawai->users_id);
                    if ($user) {
                        $newEmail = $validated['email'] ?? null;

                        if (!empty($newEmail)) {
                            $emailUsed = User::where('email', $newEmail)
                                ->where('id', '!=', $user->id)
                                ->exists();

                            if ($emailUsed) {
                                throw new \Exception('Email sudah digunakan user lain.');
                            }
                        }

                        $user->update([
                            'name' => $validated['nama'],
                            'email' => $newEmail,
                        ]);
                    }
                }
            });

            return redirect()->route('data-pegawai')->with('success', 'Berhasil mengubah data');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        $id = dec_id($request->input('id'));
        $data = Pegawai::find($id);

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
            $data = Pegawai::findOrFail(dec_id($request->input('id')));
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

    public function updateKeaktifan(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'is_active' => 'required|in:0,1',
        ]);

        try {
            $pegawai = Pegawai::findOrFail(dec_id($validated['id']));
            $pegawai->is_active = (string) $validated['is_active'];
            $pegawai->save();

            return response()->json([
                'status' => true,
                'msg' => 'Status keaktifan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function createAccount(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        try {
            $pegawai = Pegawai::findOrFail(dec_id($validated['id']));

            if (!empty($pegawai->users_id)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Pegawai ini sudah punya akun.'
                ]);
            }

            User::createFromPegawai($pegawai, 6);

            return response()->json([
                'status' => true,
                'msg' => 'Akun berhasil dibuat dan role berhasil disinkronkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
