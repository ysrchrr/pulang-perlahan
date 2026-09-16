<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengaduan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KategoriExport;
use App\Exports\KategoriTemplateExport;
use App\Imports\KategoriImport;

class KategoriPengaduanController extends Controller
{
    public function index(){
        $data = [
            'page_title' => 'Kategori Pengaduan'
        ];

        return view('kategori_pengaduan.index', $data);
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data = KategoriPengaduan::query();

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('dibuat_oleh', function ($row){
                return $row->creator->name;
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-warning" onclick="toEdit(\''.enc_id($row->id).'\')">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\''.enc_id($row->id).'\')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                    ';
            })
            ->rawColumns(['action', 'dibuat_oleh'])
            ->make(true);
        }
    }

    public function storeKategoriPengaduan(Request $request){
        try {
            $id = $request->id_kategori ? dec_id($request->id_kategori) : null;

            KategoriPengaduan::updateOrCreate(
                ['id' => $id],
                [
                    'nama_kategori' => $request->nama_kategori,
                    'created_by' => session('user_id')
                ]
            );

            return response()->json(['status' => true,'msg' => 'Berhasil menyimpan data']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetailKategoriPengaduan(Request $request){
        $id = dec_id($request->input('id'));

        $data = KategoriPengaduan::find($id);

        if($data){
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function delete(Request $request){
        try {
            $product = KategoriPengaduan::findOrFail(dec_id($request->input('id')));
            $product->delete();

            return response()->json(['status' => true,'msg' => 'Berhasil hapus data']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function export()
    {
        return Excel::download(new KategoriExport, 'data_kategori_pengaduan.xlsx');
    }

    public function template()
    {
        return Excel::download(new KategoriTemplateExport, 'template_kategori_pengaduan.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file_excel');
            if ($file) {
                Excel::import(new KategoriImport, $file);
                return response()->json(['status' => true, 'msg' => 'Berhasil import data']);
            }
            return response()->json(['status' => false, 'msg' => 'File tidak ditemukan'], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'msg' => $e->getMessage()], 500);
        }
    }
}
