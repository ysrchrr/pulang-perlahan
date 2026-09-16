<?php

namespace App\Http\Controllers;

use App\Models\MasterTimKerja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManajemenTimKerjaController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Tim Kerja'
        ];

        return view('manajemen_tim_kerja.index', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterTimKerja::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('creator', function ($row) {
                    return $row->creator ? $row->creator->name : '-';
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
                ->rawColumns(['action', 'creator'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $id = $request->id_tim_kerja ? dec_id($request->id_tim_kerja) : null;

            MasterTimKerja::updateOrCreate(
                ['id' => $id],
                [
                    'nama_tim_kerja' => $request->nama_tim_kerja,
                    'created_by' => session('user_id')
                ]
            );

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

    public function detail(Request $request)
    {
        $id = dec_id($request->input('id'));
        $data = MasterTimKerja::find($id);

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
            $data = MasterTimKerja::findOrFail(dec_id($request->input('id')));
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
}
