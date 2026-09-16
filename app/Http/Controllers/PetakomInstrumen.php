<?php

namespace App\Http\Controllers;

use App\Models\PetakomEnrollment;
use App\Models\PetakomJawaban;
use App\Models\PetakomMasterInstrumen;
use App\Models\PetakomSoalInstrumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetakomInstrumen extends Controller
{
    public function index($_id_instrumen)
    {
        $id_instrumen = dec_id($_id_instrumen);
        $id_users = Auth::guard('petakom')->id();
        $petakomInstrumen = PetakomMasterInstrumen::with('ref_jenjang', 'ref_mapel')->findOrFail($id_instrumen);
        $soalInstrumen = PetakomSoalInstrumen::where('id_instrumen', $id_instrumen)->whereIn('question_type', ['likert', 'information'])->get();
        $jawabanSoal = PetakomJawaban::where('id_users', $id_users)
            ->whereIn('id_soal', $soalInstrumen->pluck('id'))
            ->pluck('value', 'id_soal');

        $data = [
            'page_title' => 'Isi Instrumen : ' . $petakomInstrumen->nama_instrumen . ' ' . $petakomInstrumen->ref_mapel->nama_mapel . ' ' . $petakomInstrumen->ref_jenjang->nama_jenjang,
            'petakom_instrumen' => $petakomInstrumen,
            'soal_instrumen' => $soalInstrumen,
            'jawaban_soal' => $jawabanSoal,
            'id_instrumen_enc' => $_id_instrumen
        ];

        return view('petakom.form', $data);
    }

    public function autosave(Request $request, $_id_instrumen)
    {
        $id_instrumen = dec_id($_id_instrumen);
        $id_users = Auth::guard('petakom')->id();

        $validated = $request->validate([
            'id_soal' => 'required|integer',
            'value' => 'required'
        ]);

        $soal = PetakomSoalInstrumen::where('id', $validated['id_soal'])
            ->where('id_instrumen', $id_instrumen)
            ->where('question_type', 'likert')
            ->firstOrFail();

        $value = (int) $validated['value'];
        $minValue = min((int) $soal->min_value, (int) $soal->max_value);
        $maxValue = max((int) $soal->min_value, (int) $soal->max_value);

        if ($value < $minValue || $value > $maxValue) {
            return response()->json([
                'success' => false,
                'message' => 'Nilai jawaban tidak valid'
            ], 422);
        }

        PetakomJawaban::updateOrCreate(
            [
                'id_soal' => $soal->id,
                'id_users' => $id_users
            ],
            [
                'value' => (string) $value
            ]
        );

        $enrollment = PetakomEnrollment::firstOrCreate([
            'id_instrumen' => $id_instrumen,
            'id_users' => $id_users
        ], [
            'status' => '1'
        ]);

        if ((string) $enrollment->status !== '2') {
            $enrollment->status = '1';
            $enrollment->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Jawaban tersimpan'
        ]);
    }

    public function selesai($_id_instrumen)
    {
        $id_instrumen = dec_id($_id_instrumen);
        $id_users = Auth::guard('petakom')->id();
        $idSoalLikert = PetakomSoalInstrumen::where('id_instrumen', $id_instrumen)
            ->where('question_type', 'likert')
            ->pluck('id');

        $totalSoal = $idSoalLikert->count();
        $totalJawaban = PetakomJawaban::where('id_users', $id_users)
            ->whereIn('id_soal', $idSoalLikert)
            ->distinct('id_soal')
            ->count('id_soal');

        if ($totalJawaban < $totalSoal) {
            return response()->json([
                'success' => false,
                'total_soal' => $totalSoal,
                'total_jawaban' => $totalJawaban,
                'message' => 'Masih ada ' . ($totalSoal - $totalJawaban) . ' soal yang belum dijawab.'
            ], 422);
        }

        PetakomEnrollment::updateOrCreate(
            [
                'id_instrumen' => $id_instrumen,
                'id_users' => $id_users
            ],
            [
                'status' => '2'
            ]
        );

        return response()->json([
            'success' => true,
            'total_soal' => $totalSoal,
            'total_jawaban' => $totalJawaban,
            'message' => 'Instrumen selesai disimpan.'
        ]);
    }
}
