<?php

namespace App\Http\Controllers;

use App\Models\MasterPenandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ManajemenPenandatanganController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Penandatangan'
        ];

        return view('manajemen_penandatangan.index', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterPenandatangan::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->nama_penandatangan . ($row->nip ? '<br><span class="badge bg-primary">NIP: ' . $row->nip . '</span>' : '') . ($row->jabatan ? '<br><span class="badge bg-success">Jabatan: ' . $row->jabatan ?? '-' . '</span>' : '');
                })
                ->addColumn('cap', function ($row) {
                    if (!$row->path_cap) {
                        return '-';
                    }

                    return '<a href="' . asset('storage/' . $row->path_cap) . '" target="_blank">
                        <img src="' . asset('storage/' . $row->path_cap) . '" alt="Cap" style="max-height:60px; max-width:120px;">
                    </a>';
                })
                ->addColumn('signature', function ($row) {
                    if (!$row->path_signature) {
                        return '-';
                    }

                    return '<a href="' . asset('storage/' . $row->path_signature) . '" target="_blank">
                        <img src="' . asset('storage/' . $row->path_signature) . '" alt="Signature" style="max-height:60px; max-width:120px;">
                    </a>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-warning" onclick="toEdit(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                    ';
                })
                ->rawColumns(['nama', 'cap', 'signature', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penandatangan' => 'nullable',
            'nama_penandatangan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'path_cap' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'path_signature' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        try {
            $id = $request->id_penandatangan ? dec_id($request->id_penandatangan) : null;
            $penandatangan = $id ? MasterPenandatangan::findOrFail($id) : new MasterPenandatangan();

            $penandatangan->nama_penandatangan = $request->nama_penandatangan;
            $penandatangan->nip = $request->nip;
            $penandatangan->jabatan = $request->jabatan;
            if (!$penandatangan->exists) {
                $penandatangan->created_by = session('user_id');
            }

            if ($request->hasFile('path_cap')) {
                if ($penandatangan->path_cap && Storage::disk('public')->exists($penandatangan->path_cap)) {
                    Storage::disk('public')->delete($penandatangan->path_cap);
                }

                $penandatangan->path_cap = $this->uploadFile($request->file('path_cap'), 'cap');
            }

            if ($request->hasFile('path_signature')) {
                if ($penandatangan->path_signature && Storage::disk('public')->exists($penandatangan->path_signature)) {
                    Storage::disk('public')->delete($penandatangan->path_signature);
                }

                $penandatangan->path_signature = $this->uploadFile($request->file('path_signature'), 'signature');
            }

            $penandatangan->save();

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
        $id = dec_id($request->input('id'));
        $data = MasterPenandatangan::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data,
                'cap_url' => $data->path_cap ? asset('storage/' . $data->path_cap) : null,
                'signature_url' => $data->path_signature ? asset('storage/' . $data->path_signature) : null,
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
            $data = MasterPenandatangan::findOrFail(dec_id($request->input('id')));

            if ($data->path_cap && Storage::disk('public')->exists($data->path_cap)) {
                Storage::disk('public')->delete($data->path_cap);
            }

            if ($data->path_signature && Storage::disk('public')->exists($data->path_signature)) {
                Storage::disk('public')->delete($data->path_signature);
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

    private function uploadFile($file, $prefix)
    {
        $filename = $prefix . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('penandatangan', $filename, 'public');
    }
}
