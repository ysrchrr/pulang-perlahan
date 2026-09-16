<?php

namespace App\Http\Controllers;

use App\Models\MasterTemplateEvalNarasumber;
use App\Models\SoalEvaluasiNarasumber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManajemenEvalNarasumberController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Template Evaluasi Narasumber'
        ];

        return view('manajemen_eval_narasumber.index', $data);
    }

    public function create()
    {
        return $this->index();
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterTemplateEvalNarasumber::withCount('soalNarasumber');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_soal', function ($row) {
                    return '<span class="badge bg-info">' . (int) $row->soal_narasumber_count . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a class="btn btn-sm btn-primary" href="' . route('eval-narasumber-soal', ['id_template' => enc_id($row->id)]) . '">
                        <i class="fa-solid fa-comment-dots"></i> Kelola Soal
                    </a>
                    <button class="btn btn-sm btn-warning" onclick="toEdit(\'' . enc_id($row->id) . '\')">
                        <i class="fas fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                        <i class="fas fa-trash"></i>Hapus
                    </button>
                    ';
                })
                ->rawColumns(['jumlah_soal', 'action'])
                ->make(true);
        }
    }

    public function soal($id_template)
    {
        $template = MasterTemplateEvalNarasumber::findOrFail(dec_id($id_template));

        $data = [
            'page_title' => 'Kelola Soal Evaluasi Narasumber',
            'template' => $template,
        ];

        return view('manajemen_eval_narasumber.soal', $data);
    }

    public function getSoalData(Request $request, $id_template)
    {
        if ($request->ajax()) {
            $templateId = dec_id($id_template);
            $data = SoalEvaluasiNarasumber::where('id_template', $templateId);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-warning" onclick="toEditSoal(\'' . enc_id($row->id) . '\')">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemoveSoal(\'' . enc_id($row->id) . '\')">
                        Hapus
                    </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function storeSoal(Request $request)
    {
        $request->validate([
            'id_soal' => 'nullable',
            'id_template' => 'required',
            'soal' => 'required|string|max:255',
        ]);

        try {
            $id = $request->id_soal ? dec_id($request->id_soal) : null;
            $templateId = dec_id($request->id_template);
            $soal = $id ? SoalEvaluasiNarasumber::findOrFail($id) : new SoalEvaluasiNarasumber();

            $soal->id_template = $templateId;
            $soal->soal = $request->soal;

            if (!$soal->exists) {
                $soal->created_by = session('user_id');
            }

            $soal->save();

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

    public function detailSoal(Request $request)
    {
        $id = dec_id($request->input('id'));
        $data = SoalEvaluasiNarasumber::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg' => 'Data tidak ditemukan'
        ], 404);
    }

    public function deleteSoal(Request $request)
    {
        try {
            $data = SoalEvaluasiNarasumber::findOrFail(dec_id($request->input('id')));
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

    public function store(Request $request)
    {
        $request->validate([
            'id_template' => 'nullable',
            'nama_template' => 'required|string|max:255',
        ]);

        try {
            $id = $request->id_template ? dec_id($request->id_template) : null;
            $template = $id ? MasterTemplateEvalNarasumber::findOrFail($id) : new MasterTemplateEvalNarasumber();

            $template->nama_template = $request->nama_template;

            if (!$template->exists) {
                $template->created_by = session('user_id');
            }

            $template->save();

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
        $data = MasterTemplateEvalNarasumber::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data,
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
            $data = MasterTemplateEvalNarasumber::findOrFail(dec_id($request->input('id')));
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
