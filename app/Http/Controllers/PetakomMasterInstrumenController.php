<?php

namespace App\Http\Controllers;

use App\Models\PetakomMasterInstrumen;
use App\Models\PetakomSoalInstrumen;
use App\Models\RefJenjangPetakom;
use App\Models\RefMapelPetakom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PetakomMasterInstrumenController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Manajemen Master Instrumen Petakom',
            'list_jenjang' => RefJenjangPetakom::orderBy('nama_jenjang')->get(),
            'list_mapel' => RefMapelPetakom::orderBy('nama_mapel')->get(),
        ];

        return view('petakom_master_instrumen.index', $data);
    }

    public function create()
    {
        return $this->index();
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = PetakomMasterInstrumen::withCount('soalInstrumen');
            $listJenjang = RefJenjangPetakom::pluck('nama_jenjang', 'id');
            $listMapel = RefMapelPetakom::pluck('nama_mapel', 'id');

            if ($request->filled('filter_jenjang')) {
                $data->where('jenjang', $request->filter_jenjang);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_soal', function ($row) {
                    return (int) $row->soal_instrumen_count;
                })
                ->editColumn('mapel', function ($row) use ($listMapel) {
                    return $listMapel[$row->mapel] ?? $row->mapel;
                })
                ->editColumn('jenjang', function ($row) use ($listJenjang) {
                    return $listJenjang[$row->jenjang] ?? $row->jenjang;
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a class="btn btn-sm btn-primary" href="' . route('petakom-master-instrumen-soal', ['id_template' => enc_id($row->id)]) . '">
                        <i class="fa-solid fa-list"></i> Kelola Soal
                    </a>
                    <button class="btn btn-sm btn-warning" onclick="toEdit(\'' . enc_id($row->id) . '\')">
                        <i class="fas fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="toRemove(\'' . enc_id($row->id) . '\')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                    ';
                })
                ->rawColumns(['jumlah_soal', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_template' => 'nullable',
            'nama_template' => 'required|string|max:255',
            'mapel' => 'required|integer|exists:ref_mapel_petakom,id',
            'jenjang' => 'required|integer|exists:ref_jenjang_petakom,id',
        ]);

        try {
            $id = $request->id_template ? dec_id($request->id_template) : null;
            $template = $id ? PetakomMasterInstrumen::findOrFail($id) : new PetakomMasterInstrumen();

            $template->nama_instrumen = $request->nama_template;
            $template->mapel = $request->mapel;
            $template->jenjang = $request->jenjang;
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
        $data = PetakomMasterInstrumen::find($id);

        if ($data) {
            $data->mapel = $this->resolveMapelValue($data->mapel);
            $data->jenjang = $this->resolveJenjangValue($data->jenjang);

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
            $data = PetakomMasterInstrumen::findOrFail(dec_id($request->input('id')));
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

    public function soal($id_template)
    {
        $template = PetakomMasterInstrumen::findOrFail(dec_id($id_template));

        $data = [
            'page_title' => 'Kelola Soal Instrumen Petakom',
            'template' => $template,
            'id_template' => $id_template,
        ];

        return view('petakom_master_instrumen.soal', $data);
    }

    public function getSoalData(Request $request, $id_template)
    {
        if ($request->ajax()) {
            $templateId = dec_id($id_template);
            $data = PetakomSoalInstrumen::where('id_instrumen', $templateId);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('question_type_label', function ($row) {
                    $types = [
                        'short_answer' => 'Jawaban Singkat',
                        'select_option' => 'Pilihan Dropdown',
                        'information' => 'Informasi',
                        'likert' => 'Skala Likert',
                    ];

                    return $types[$row->question_type] ?? $row->question_type;
                })
                ->addColumn('question_text', function ($row) {
                    return Str::limit($row->question ?: $row->sub_question, 80);
                })
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
            'question_type' => 'required|in:short_answer,information,likert,select_option',
            'question' => 'required|string',
            'sub_question' => 'required_if:question_type,information|nullable|string',
            'option' => 'required_if:question_type,select_option|nullable|string',
            'min_value' => 'required_if:question_type,likert|nullable|numeric',
            'max_value' => 'required_if:question_type,likert|nullable|numeric',
            'likert_text' => 'required_if:question_type,likert|nullable|string|max:255',
        ]);

        try {
            $id = $request->id_soal ? dec_id($request->id_soal) : null;
            $templateId = dec_id($request->id_template);
            $soal = $id ? PetakomSoalInstrumen::findOrFail($id) : new PetakomSoalInstrumen();

            $soal->id_instrumen = $templateId;
            $soal->question_type = $request->question_type;
            $soal->question = $request->question;
            $soal->sub_question = $request->question_type === 'information' ? $request->sub_question : null;
            $soal->option = $request->question_type === 'select_option' ? $this->formatOption($request->option) : null;
            $soal->min_value = $request->question_type === 'likert' ? $request->min_value : null;
            $soal->max_value = $request->question_type === 'likert' ? $request->max_value : null;
            $soal->likert_text = $request->question_type === 'likert' ? $request->likert_text : null;
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
        $data = PetakomSoalInstrumen::find($id);

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
            $data = PetakomSoalInstrumen::findOrFail(dec_id($request->input('id')));
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

    public function downloadTemplateSoal()
    {
        $filePath = storage_path('app/templates/contoh_import_soal_petakom.xlsx');

        if (!File::exists($filePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        return response()->download($filePath, 'contoh_import_soal_petakom.xlsx');
    }

    public function importSoal(Request $request, $id_template)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $idInstrumen = dec_id($id_template);
            PetakomMasterInstrumen::findOrFail($idInstrumen);

            $spreadsheet = IOFactory::load($request->file('file_excel')->getPathname());
            $sheet = $spreadsheet->getSheetByName('Soal') ?? $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            $inserted = 0;
            $skipped = [];
            $allowedTypes = ['short_answer', 'information', 'likert', 'select_option'];

            for ($row = 2; $row <= $highestRow; $row++) {
                $questionType = strtolower($this->getCellValue($sheet, 'A' . $row));
                $question = $this->getCellValue($sheet, 'B' . $row);
                $subQuestion = $this->getCellValue($sheet, 'C' . $row);
                $option = $this->getCellValue($sheet, 'D' . $row);
                $minValue = $this->getCellValue($sheet, 'E' . $row);
                $maxValue = $this->getCellValue($sheet, 'F' . $row);

                if ($questionType === '' && $question === '' && $subQuestion === '' && $option === '') {
                    continue;
                }

                if (!in_array($questionType, $allowedTypes)) {
                    $skipped[] = "Baris {$row}: question_type tidak valid.";
                    continue;
                }

                if ($question === '') {
                    $skipped[] = "Baris {$row}: question wajib diisi.";
                    continue;
                }

                if ($questionType === 'information' && $subQuestion === '') {
                    $skipped[] = "Baris {$row}: sub_question wajib untuk information.";
                    continue;
                }

                if ($questionType === 'select_option' && $option === '') {
                    $skipped[] = "Baris {$row}: option wajib untuk select_option.";
                    continue;
                }

                if ($questionType === 'likert' && ($minValue === '' || $maxValue === '')) {
                    $skipped[] = "Baris {$row}: min_value dan max_value wajib untuk likert.";
                    continue;
                }

                if ($questionType === 'likert' && (!is_numeric($minValue) || !is_numeric($maxValue))) {
                    $skipped[] = "Baris {$row}: min_value dan max_value harus angka.";
                    continue;
                }

                PetakomSoalInstrumen::create([
                    'id_instrumen' => $idInstrumen,
                    'question_type' => $questionType,
                    'question' => $question,
                    'sub_question' => $questionType === 'information' ? $subQuestion : null,
                    'option' => $questionType === 'select_option' ? $this->formatOption($option) : null,
                    'min_value' => $questionType === 'likert' ? $minValue : null,
                    'max_value' => $questionType === 'likert' ? $maxValue : null,
                ]);

                $inserted++;
            }

            $msg = "Berhasil import {$inserted} soal.";

            if (count($skipped) > 0) {
                $msg .= ' Dilewati: ' . implode(' ', array_slice($skipped, 0, 5));
            }

            return response()->json([
                'status' => true,
                'msg' => $msg,
                'skipped' => $skipped,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    private function formatOption($value)
    {
        $decoded = json_decode((string) $value, true);

        if (is_array($decoded)) {
            $options = array_filter(array_map(function ($item) {
                return trim((string) $item);
            }, $decoded));

            return json_encode(array_values($options));
        }

        $options = array_filter(array_map('trim', explode(',', (string) $value)));

        return json_encode(array_values($options));
    }

    private function getCellValue($sheet, $cell)
    {
        return trim((string) $sheet->getCell($cell)->getFormattedValue());
    }

    private function resolveMapelValue($value)
    {
        if (RefMapelPetakom::where('id', $value)->exists()) {
            return $value;
        }

        $mapel = RefMapelPetakom::where('nama_mapel', $value)->first();

        return $mapel?->id ?? $value;
    }

    private function resolveJenjangValue($value)
    {
        if (RefJenjangPetakom::where('id', $value)->exists()) {
            return $value;
        }

        $jenjang = RefJenjangPetakom::where('nama_jenjang', $value)->first();

        return $jenjang?->id ?? $value;
    }
}
