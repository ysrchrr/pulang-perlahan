<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDetailsMail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SuperadminUserController extends Controller
{
    private function getDefaultPassword()
    {
        return config('app.default_password');
    }

    public function index()
    {
        $data = [
            'page_title' => 'Manajemen User',
            'ref_roles' => Roles::get()
        ];

        return view('superadmin.manajemen-user', $data);
    }

    public function getData(Request $request)
    {
        $data = User::with('roles')->where('id', '!=', 1)->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                $nama = $row->name ?? '-';

                $roles = $row->roles->map(function ($r) {
                    return '<span class="badge rounded-pill bg-primary me-1">'
                        . $r->role_name .
                        '</span>';
                })->implode(' ');

                return $nama . '<br>' . $roles;
            })
            ->addColumn('last_login', function ($row) {
                if ($row->last_login == null) {
                    return '<span class="text-danger">Belum pernah</span>';
                } else {
                    return datetimeIndoFull($row->last_login);
                }
            })
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-dark" onclick="impersonate(`' . enc_id($row->id) . '`)"><i class="fa-solid fa-fingerprint"></i></button>
                <button class="btn btn-primary" onclick="sendAccount(`' . enc_id($row->id) . '`)"><i class="fa-solid fa-paper-plane"></i></button>
                <button class="btn btn-warning" onclick="modalUser(`' . enc_id($row->id) . '`)"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btn btn-info" onclick="confirmResetPwd(`' . enc_id($row->id) . '`)"><i class="fa-solid fa-lock-open"></i></button>
                <button class="btn btn-danger" onclick="toRemove(`' . enc_id($row->id) . '`)">
                    <i class="fa-solid fa-trash"></i>
                </button>
                
                ';
            })
            ->rawColumns(['name', 'last_login', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = $request->input('data');

        if (empty($data['id_user'])) {

            //username checking
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Username sudah digunakan'
                ]);
            }

            $newUser = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => bcrypt($this->getDefaultPassword()),
            ]);

            $assignedRoles = $data['roles'] ?? [];
            if (!empty($assignedRoles)) {
                foreach ($assignedRoles as $role_id) {
                    UserRole::create([
                        'users_id' => $newUser->id,
                        'roles_id' => $role_id
                    ]);
                }
            }

            $msg = 'User berhasil ditambahkan';
        } else {
            $realId = dec_id($data['id_user']);
            $newUser = User::where('id', $realId)->update([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => bcrypt($this->getDefaultPassword()),
            ]);

            $assignedRoles = $data['roles'] ?? [];
            if (!empty($assignedRoles)) {

                UserRole::where('users_id', $realId)->delete();

                foreach ($assignedRoles as $role_id) {
                    UserRole::create([
                        'users_id' => $realId,
                        'roles_id' => $role_id
                    ]);
                }
            }

            $msg = 'User berhasil diupdate';
        }

        return response()->json([
            'status' => true,
            'message' => $msg
        ]);
    }

    public function setIsActive(Request $request)
    {
        try {
            $data = $request->input('data');

            User::where('id', $data['id'])->update([
                'is_active' => $data['is_active']
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Status user berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getDetail(Request $request)
    {
        $user_id = dec_id($request->input('user_id'));

        $user = User::with('roles')->find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function delete(Request $request)
    {
        $user_id = dec_id($request->input('user_id'));

        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ]);
        }

        UserRole::where('users_id', $user_id)->delete();
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $user_id = dec_id($request->input('user_id'));

        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ]);
        }

        $defaultPassword = $this->getDefaultPassword();

        $user->update([
            'password' => bcrypt($defaultPassword)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password user berhasil direset.'
        ]);
    }

    public function impersonate(Request $request)
    {
        try {
            $user_id = dec_id($request->user_id);
            $targetUser = User::find($user_id);

            if (!$targetUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan.'
                ]);
            }

            // Simpan ID peng-impersonate (admin)
            $impersonatorId = session('user_id');

            // Login sebagai target user
            Auth::login($targetUser);

            // Setup session (sama kayak AuthController)
            $rolesData = UserRole::where('users_id', $targetUser->id)
                ->with('role:id,role_name,slug_name,is_active')
                ->get()
                ->filter(function ($item) {
                    return $item->role->is_active == 1;
                })
                ->map(function ($item) {
                    return [
                        'id' => $item->role->id,
                        'slug_name' => $item->role->slug_name,
                        'name' => $item->role->role_name,
                    ];
                })
                ->toArray();

            if (empty($rolesData)) {
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'User target tidak memiliki role aktif.'
                ]);
            }

            session([
                'user_id' => $targetUser->id,
                'user_name' => $targetUser->name,
                'user_email' => $targetUser->email,
                'avatar' => $targetUser->avatar,
                'roles' => $rolesData,
                'role_id' => null,
                'role_name' => null,
                'impersonator_id' => $impersonatorId, // Tandai sedang impersonate
            ]);

            // Auto select role if only 1 role
            if (count($rolesData) === 1) {
                session([
                    'role_id'   => $rolesData[0]['id'],
                    'role_name' => $rolesData[0]['name'],
                    'role_slug' => $rolesData[0]['slug_name'],
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Impersonate berhasil. Mengalihkan...',
                'redirect' => count($rolesData) === 1 ? route('dashboard') : route('choose-role')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal impersonate: ' . $e->getMessage()
            ]);
        }
    }

    public function stopImpersonate()
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('login');
        }

        $admin = User::find($impersonatorId);

        if (!$admin) {
            Auth::logout();
            return redirect()->route('login');
        }

        // Login balik sebagai admin
        Auth::login($admin);

        // Ambil role admin
        $rolesData = UserRole::where('users_id', $admin->id)
            ->with('role:id,role_name,slug_name,is_active')
            ->get()
            ->filter(function ($item) {
                return $item->role->is_active == 1;
            })
            ->map(function ($item) {
                return [
                    'id' => $item->role->id,
                    'slug_name' => $item->role->slug_name,
                    'name' => $item->role->role_name,
                ];
            })
            ->toArray();

        // Cari role superadmin buat balik ke manajemen user
        $superadminRole = collect($rolesData)->firstWhere('slug_name', 'superadmin');

        session([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
            'avatar' => $admin->avatar,
            'roles' => $rolesData,
            'role_id' => $superadminRole ? $superadminRole['id'] : $rolesData[0]['id'],
            'role_name' => $superadminRole ? $superadminRole['name'] : $rolesData[0]['name'],
            'role_slug' => $superadminRole ? $superadminRole['slug_name'] : $rolesData[0]['slug_name'],
        ]);

        session()->forget('impersonator_id');

        return redirect()->route('manajemen-user');
    }

    public function sendAccount(Request $request)
    {
        try {
            $user_id = dec_id($request->user_id);
            $user = User::find($user_id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan.'
                ]);
            }

            $password = $this->getDefaultPassword();

            $data = [
                'user_name' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'login_url' => route('login')
            ];

            Mail::to($user->email)->send(new AccountDetailsMail($data));

            return response()->json([
                'status' => true,
                'message' => 'Detail akun berhasil dikirim ke ' . $user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ]);
        }
    }
}
