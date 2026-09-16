<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Peserta;
use App\Models\Ref_Golongan;
use App\Models\Ref_Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $role_id;
    protected $users_id;

    protected function validatePeserta(Request $request)
    {
        return $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:50',
            'nip' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:30',
            'alamat' => 'required|string',
            'unit_kerja' => 'nullable|string|max:255',
            'id_golongan' => 'nullable',
            'npwp' => 'nullable|string|max:50',
        ]);
    }

    protected function validatePegawai(Request $request)
    {
        return $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:50',
            'nip' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:30',
            'alamat' => 'required|string',
            'id_jabatan' => 'nullable',
            'id_golongan' => 'nullable',
            'instansi_pendidikan' => 'nullable|string|max:255',
            'jenjang' => 'nullable|string|max:20',
            'tahun_lulus' => 'nullable|string|max:4',
        ]);
    }

    protected function validatePassword(Request $request)
    {
        return $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:8|confirmed',
        ]);
    }

    public function __construct()
    {
        $this->role_id = session('role_id');
        $this->users_id = session('user_id');
    }

    public function index()
    {
        if ($this->role_id == 7) {

            $data = [
                'page_title' => 'Profil Peserta',
                'peserta' => Peserta::where('users_id', $this->users_id)->first(),
                'ref_pagol' => Ref_Golongan::all()
            ];

            return view('profile.profile-peserta', $data);
        } else {
            $data = [
                'page_title' => 'Profil Pegawai',
                'pegawai' => Pegawai::where('users_id', $this->users_id)->first(),
                'ref_pagol' => Ref_Golongan::all(),
                'ref_jabatan' => Ref_Jabatan::all()
            ];
            return view('profile.profile-pegawai', $data);
        }
    }

    public function updatePeserta(Request $request)
    {
        $validated = $this->validatePeserta($request);

        $peserta = Peserta::where('users_id', $this->users_id)->first();

        if (!$peserta) {
            return redirect()->back()->withInput()->with('error', 'Data peserta tidak ditemukan');
        }

        $peserta->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'nip' => $validated['nip'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'unit_kerja' => $validated['unit_kerja'] ?? null,
            'id_golongan' => $validated['id_golongan'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePegawai(Request $request)
    {
        $validated = $this->validatePegawai($request);

        $pegawai = Pegawai::where('users_id', $this->users_id)->first();

        if (!$pegawai) {
            return redirect()->back()->withInput()->with('error', 'Data pegawai tidak ditemukan');
        }

        $pegawai->update([
            'nama' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'nip' => $validated['nip'] ?? null,
            'jk' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'id_jabatan' => $validated['id_jabatan'] ?? null,
            'id_golongan' => $validated['id_golongan'] ?? null,
            'instansi_pendidikan' => $validated['instansi_pendidikan'] ?? null,
            'jenjang' => $validated['jenjang'] ?? null,
            'tahun_lulus' => $validated['tahun_lulus'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $validated = $this->validatePassword($request);
        $user = User::find($this->users_id);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User tidak ditemukan');
        }

        if (!Hash::check($validated['password_lama'], $user->password)) {
            return redirect()->back()->withInput()->with('error', 'Password lama tidak sesuai');
        }

        $user->update([
            'password' => $validated['password_baru'],
        ]);

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui');
    }
}
