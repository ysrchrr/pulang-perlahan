<?php

namespace App\Http\Controllers;

use App\Models\ProfileUserPetakom;
use App\Models\RefJenjangPetakom;
use App\Models\RefMapelPetakom;
use App\Models\RefWilayahPetakom;
use App\Models\UserPetakom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfilePetakomController extends Controller
{
    protected $users_id;

    public function __construct()
    {
        $this->users_id = session('user_id');
    }

    public function index()
    {
        $profile = ProfileUserPetakom::with('users')->where('id_users', $this->users_id)->first();

        $data = [
            'page_title' => 'Profil',
            'profile' => $profile,
            'user' => $profile?->users ?? UserPetakom::find($this->users_id),
            'ref_jenjang' => RefJenjangPetakom::get(),
            'ref_mapel' => RefMapelPetakom::get(),
            'ref_wilayah_petakom' => RefWilayahPetakom::get()
        ];

        return view('profile.profile-petakom', $data);
    }

    public function storePeserta(Request $request)
    {
        $profile = ProfileUserPetakom::where('id_users', $this->users_id)->first();
        $nuptkUniqueRule = Rule::unique('profile_user_petakom', 'nuptk')->whereNull('deleted_at');

        if ($profile) {
            $nuptkUniqueRule->ignore($profile->id);
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nuptk' => [
                'nullable',
                'digits:18',
                $nuptkUniqueRule,
            ],
            'jk' => 'required|in:L,P',
            'sekolah' => 'nullable|string|max:255',
            'npsn' => 'nullable|string|max:255',
            'id_jenjang' => 'nullable|exists:ref_jenjang_petakom,id',
            'id_mapel' => 'nullable|exists:ref_mapel_petakom,id',
            'wilayah_petakom' => 'nullable|exists:ref_wilayah_petakom,id',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = UserPetakom::find($this->users_id);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User tidak ditemukan');
        }

        $user->update([
            'name' => $validated['nama_lengkap'],
        ]);

        ProfileUserPetakom::updateOrCreate(
            ['id_users' => $this->users_id],
            [
                'jk' => $validated['jk'] ?? null,
                'nuptk' => $validated['nuptk'] ?? null,
                'sekolah' => $validated['sekolah'] ?? null,
                'npsn' => $validated['npsn'] ?? null,
                'id_jenjang' => $validated['id_jenjang'] ?? null,
                'id_mapel' => $validated['id_mapel'] ?? null,
                'wilayah_petakom' => $validated['wilayah_petakom'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]
        );

        return redirect()->route('profile-petakom')->with('success', 'Profil berhasil diperbarui');
    }

    public function checkNuptk(Request $request)
    {
        $request->validate([
            'nuptk' => 'required|digits_between:9,18',
        ]);

        $profile = ProfileUserPetakom::with('users')
            ->where('nuptk', $request->nuptk)
            ->where('id_users', '!=', $this->users_id)
            ->first();

        return response()->json([
            'exists' => (bool) $profile,
            'email' => $profile ? $this->maskEmail($profile->users?->email) : null,
        ]);
    }

    private function maskEmail(?string $email): ?string
    {
        if (!$email || !str_contains($email, '@')) {
            return $email;
        }

        [$name, $domain] = explode('@', $email, 2);

        if (strlen($name) <= 2) {
            return substr($name, 0, 1) . '***@' . $domain;
        }

        return substr($name, 0, 1) . '***' . substr($name, -1) . '@' . $domain;
    }
}
