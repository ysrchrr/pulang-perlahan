<?php

namespace App\Http\Controllers;

use App\Models\PetakomEnrollment;
use App\Models\PetakomJawaban;
use App\Models\PetakomMasterInstrumen;
use App\Models\PetakomSoalInstrumen;
use App\Models\ProfileUserPetakom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetakomInstrumenListController extends Controller
{
    public function index()
    {
        $idUsers = Auth::guard('petakom')->id();
        $profile = ProfileUserPetakom::where('id_users', $idUsers)->first();
        $profileBelumLengkap = empty($profile?->id_jenjang) || empty($profile?->id_mapel);
        $instrumenAvailable = collect();

        if (!$profileBelumLengkap) {
            $instrumenAvailable = PetakomMasterInstrumen::with('ref_jenjang', 'ref_mapel')
                ->withCount([
                    'soalInstrumen as total_soal_likert' => function ($query) {
                        $query->where('question_type', 'likert');
                    }
                ])
                ->where('mapel', $profile->id_mapel)
                ->where('jenjang', $profile->id_jenjang)
                ->get();

            $idInstrumen = $instrumenAvailable->pluck('id');
            $enrollments = PetakomEnrollment::where('id_users', $idUsers)
                ->whereIn('id_instrumen', $idInstrumen)
                ->get()
                ->keyBy('id_instrumen');

            $idSoalLikert = PetakomSoalInstrumen::whereIn('id_instrumen', $idInstrumen)
                ->where('question_type', 'likert')
                ->pluck('id');

            $jumlahJawaban = PetakomJawaban::selectRaw('petakom_soal_instrumen.id_instrumen, COUNT(DISTINCT petakom_jawaban_user.id_soal) as total_jawaban')
                ->join('petakom_soal_instrumen', 'petakom_soal_instrumen.id', '=', 'petakom_jawaban_user.id_soal')
                ->where('petakom_jawaban_user.id_users', $idUsers)
                ->whereIn('petakom_jawaban_user.id_soal', $idSoalLikert)
                ->groupBy('petakom_soal_instrumen.id_instrumen')
                ->pluck('total_jawaban', 'id_instrumen');

            $instrumenAvailable->each(function ($item) use ($enrollments, $jumlahJawaban) {
                $item->enrollment_status = (string) ($enrollments[$item->id]->status ?? '0');
                $item->total_jawaban_likert = (int) ($jumlahJawaban[$item->id] ?? 0);
            });
        }

        $data = [
            'page_title' => 'List Instrumen Petakom',
            'profile_belum_lengkap' => $profileBelumLengkap,
            'instrumen' => $instrumenAvailable
        ];

        return view('dashboard_petakom.index', $data);
    }

    public function enrollment(Request $request)
    {
        $idUsers = Auth::guard('petakom')->id();

        $request->validate([
            'id_instrumen' => 'required',
        ]);

        $idInstrumen = dec_id($request->id_instrumen);

        if (!$idInstrumen) {
            abort(404);
        }

        PetakomMasterInstrumen::findOrFail($idInstrumen);

        PetakomEnrollment::firstOrCreate([
            'id_instrumen' => $idInstrumen,
            'id_users' => $idUsers,
        ], [
            'status' => '0',
        ]);

        return redirect()->route('petakom-instrumen', ['id_instrumen' => enc_id($idInstrumen)]);
    }
}
