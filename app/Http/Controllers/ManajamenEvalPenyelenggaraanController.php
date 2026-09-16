<?php

namespace App\Http\Controllers;

use App\Models\MasterTemplateEvalPenyelenggaraan;
use App\Models\SoalEvaluasiPenyelenggaraan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManajamenEvalPenyelenggaraanController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Template Evaluasi Penyelenggaraan'
        ];

        return view('manajemen_eval_penyelenggaraan.index', $data);
    }

    public function create()
    {
        return $this->index();
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterTemplateEvalPenyelenggaraan::withCount('soalPenyelenggaraan');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_soal', function ($row) {
                    return '<span class="badge bg-info">' . (int) $row->soal_penyelenggaraan_count . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a class="btn btn-sm btn-primary" href="' . route('eval-penyelenggaraan-soal', ['id_template' => enc_id($row->id)]) . '">
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
        $template = MasterTemplateEvalPenyelenggaraan::findOrFail(dec_id($id_template));

        $data = [
            'page_title' => 'Kelola Soal Evaluasi Penyelenggaraan',
            'template' => $template,
        ];

        return view('manajemen_eval_penyelenggaraan.soal', $data);
    }

    public function getSoalData(Request $request, $id_template)
    {
        if ($request->ajax()) {
            $templateId = dec_id($id_template);
            $data = SoalEvaluasiPenyelenggaraan::where('id_template', $templateId);

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
            $soal = $id ? SoalEvaluasiPenyelenggaraan::findOrFail($id) : new SoalEvaluasiPenyelenggaraan();

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
        $data = SoalEvaluasiPenyelenggaraan::find($id);

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
            $data = SoalEvaluasiPenyelenggaraan::findOrFail(dec_id($request->input('id')));
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
            $template = $id ? MasterTemplateEvalPenyelenggaraan::findOrFail($id) : new MasterTemplateEvalPenyelenggaraan();

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
        $data = MasterTemplateEvalPenyelenggaraan::find($id);

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
            $data = MasterTemplateEvalPenyelenggaraan::findOrFail(dec_id($request->input('id')));
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
