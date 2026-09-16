<?php

namespace App\Http\Controllers;

use App\Models\MasterTemplateSertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ManajemenTemplateSertifikatController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Template Sertifikat'
        ];

        return view('manajemen_template_sertifikat.index', $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterTemplateSertifikat::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <a class="btn btn-sm btn-primary" href="' . route('template-sertifikat-preview', enc_id($row->id)) . '" target="_blank">
                        <i class="fa-solid fa-magnifying-glass"></i> Preview
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

    public function preview(Request $request, $id)
    {
        $template = MasterTemplateSertifikat::findOrFail(dec_id($id));

        if (!$template->bg_path || !Storage::disk('public')->exists($template->bg_path)) {
            abort(404, 'Background sertifikat tidak ditemukan');
        }

        $data = [
            'bg_path' => Storage::disk('public')->path($template->bg_path),
            'nama_peserta' => $request->input('nama', 'NAMA PESERTA'),
        ];

        return Pdf::loadView('manajemen_template_sertifikat.preview', $data)
            ->setPaper('a4', 'landscape')
            ->stream('preview-sertifikat.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_template' => 'nullable',
            'nama_template' => 'required|string|max:255',
            'bg_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $id = $request->id_template ? dec_id($request->id_template) : null;
            $template = $id ? MasterTemplateSertifikat::findOrFail($id) : new MasterTemplateSertifikat();

            $template->nama_template = $request->nama_template;

            if (!$template->exists) {
                $template->created_by = session('user_id');
            }

            if ($request->hasFile('bg_path')) {
                if ($template->bg_path && Storage::disk('public')->exists($template->bg_path)) {
                    Storage::disk('public')->delete($template->bg_path);
                }

                $template->bg_path = $this->uploadFile($request->file('bg_path'));
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
        $data = MasterTemplateSertifikat::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'data' => $data,
                'bg_path' => $data->bg_path ? asset('storage/' . $data->bg_path) : null,
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
            $data = MasterTemplateSertifikat::findOrFail(dec_id($request->input('id')));

            if ($data->bg_path && Storage::disk('public')->exists($data->bg_path)) {
                Storage::disk('public')->delete($data->bg_path);
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

    private function uploadFile($file)
    {
        $filename = 'template_sertifikat_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('template_sertifikat', $filename, 'public');
    }
}
