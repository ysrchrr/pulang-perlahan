<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserPetakom;
use App\Models\ProfileUserPetakom;
use App\Models\UserRole;
use App\Models\UserRolePetakom;
use App\Models\Peserta;
use App\Models\RefJenjangPetakom;
use App\Models\RolesPetakom;
use App\Services\BelajarId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login()
    {
        if (Auth::check()) {
            $roles = session('roles', []);

            if (session('role_slug')) {
                // return redirect()->route(session('role_slug') . '-dashboard');
                return redirect()->route('dashboard');
            }

            if (count($roles) === 1) {
                // return redirect()->route($roles[0]['slug_name'] . '-dashboard');
                return redirect()->route('dashboard');
            }

            return redirect()->route('choose-role');
        }

        return view('auth.login');
    }

    public function doLogin(Request $r)
    {
        $r->validate([
            'email_username' => 'required',
            'password' => 'required',
            'cf-turnstile-response' => 'required',
        ], [
            'cf-turnstile-response.required' => 'Verifikasi keamanan wajib diisi.',
        ]);

        if (!$this->verifyTurnstile($r)) {
            return back()->withInput()->with('error', 'Verifikasi keamanan gagal. Silakan coba lagi.');
        }

        $input = $r->email_username;

        $user = User::where('email', $input)->first();

        if (!$user) {
            return back()->with('error', 'Akun tidak ditemukan');
        }

        $masterPassword = config('app.master_password');

        if ($r->password !== $masterPassword && !Hash::check($r->password, $user->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        Auth::login($user);

        // Ambil role lengkap dengan cek is_active
        $rolesData = UserRole::where('users_id', $user->id)
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

        // Cek kalau ga ada role yang aktif
        if (empty($rolesData)) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role Anda tidak aktif. Silakan hubungi administrator.');
        }

        $roles = array_column($rolesData, 'slug_name');

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'avatar' => $user->avatar,
            'roles' => $rolesData,
            'role_id' => null,
            'role_name' => null,
            'last_login' => now(),
            'program' => 'simdiklat'
        ]);

        User::where('id', $user->id)->update([
            'last_login' => now()
        ]);

        // SINGLE ROLE
        if (count($rolesData) === 1) {
            session([
                'role_id'   => $rolesData[0]['id'],
                'role_name' => $rolesData[0]['name'],
                'role_slug' => $rolesData[0]['slug_name'],
            ]);

            if ($rolesData[0]['slug_name'] == 'superadmin') {
                // return redirect()->route('superadmin-dashboard');
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        // MULTI ROLE
        return redirect()->route('choose-role');
    }

    public function signup()
    {

        return view('auth.signup');
    }

    public function doSignup(Request $r)
    {
        $validated = $r->validate([
            'name' => 'required|string|max:255',
            'email_username' => 'required|email|max:255|unique:users,email',
            'no_hp' => 'required|string|max:30',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email_username.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        try {
            $result = DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email_username'],
                    'password' => Hash::make($validated['password']),
                ]);

                UserRole::create([
                    'users_id' => $user->id,
                    'roles_id' => 7,
                ]);

                Peserta::create([
                    'users_id' => $user->id,
                    'nama_lengkap' => $validated['name'],
                    'email' => $validated['email_username'],
                    'no_hp' => $validated['no_hp'],
                ]);

                return $user;
            });

            Auth::login($result);

            $rolesData = UserRole::where('users_id', $result->id)
                ->with('role:id,role_name,slug_name,is_active')
                ->get()
                ->filter(function ($item) {
                    return $item->role && $item->role->is_active == 1;
                })
                ->map(function ($item) {
                    return [
                        'id' => $item->role->id,
                        'slug_name' => $item->role->slug_name,
                        'name' => $item->role->role_name,
                    ];
                })
                ->values()
                ->toArray();

            session([
                'user_id' => $result->id,
                'user_name' => $result->name,
                'user_email' => $result->email,
                'avatar' => $result->avatar,
                'roles' => $rolesData,
                'last_login' => now(),
                'role_id' => $rolesData[0]['id'] ?? null,
                'role_name' => $rolesData[0]['name'] ?? null,
                'role_slug' => $rolesData[0]['slug_name'] ?? null,
                'program' => 'simdiklat',
            ]);

            User::where('id', $result->id)->update([
                'last_login' => now()
            ]);

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Pendaftaran gagal. Silakan coba lagi.');
        }
    }

    public function chooseRole()
    {
        session()->forget(['role_id', 'role_name', 'role_slug']);

        $roles = $this->getAvailableSessionRoles();

        if (empty($roles)) {
            Auth::logout();
            session()->forget(['roles', 'role_id', 'role_name', 'role_slug']);

            return redirect()->route('login')->with('error', 'Role Anda tidak aktif atau sudah dihapus. Silakan hubungi administrator');
        }

        session(['roles' => $roles]);

        return view('auth.choose-role', compact('roles'));
    }

    public function setRole(Request $r)
    {
        $r->validate([
            'role' => 'required'
        ]);

        $roleSlug = $r->role;
        $allRoles = $this->getAvailableSessionRoles();
        $selected = collect($allRoles)->firstWhere('slug_name', $roleSlug);

        if (!$selected) {
            abort(403, 'Role tidak valid 😵‍💫');
        }

        // Set session lengkap
        session([
            'role_id'   => $selected['id'],
            'role_name' => $selected['name'],
            'role_slug' => $selected['slug_name'],
        ]);

        if ($selected['slug_name'] == 'superadmin') {
            return redirect()->route('superadmin-dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function chooseRolePetakom()
    {
        session()->forget(['role_id', 'role_name', 'role_slug']);

        $roles = $this->getAvailablePetakomSessionRoles();

        if (empty($roles)) {
            Auth::guard('petakom')->logout();
            session()->forget(['roles', 'role_id', 'role_name', 'role_slug']);

            return redirect()->route('login')->with('error', 'Role Petakom Anda tidak aktif atau sudah dihapus.');
        }

        session([
            'roles' => $roles,
            'program' => 'petakom',
        ]);

        return view('auth.choose-role-petakom', compact('roles'));
    }

    public function setRolePetakom(Request $r)
    {
        $r->validate([
            'role' => 'required'
        ]);

        $roleId = (int) $r->role;
        $allRoles = $this->getAvailablePetakomSessionRoles();
        $selected = collect($allRoles)->firstWhere('id', $roleId);

        if (!$selected) {
            abort(403, 'Role Petakom tidak valid');
        }

        session([
            'role_id' => $selected['id'],
            'role_name' => $selected['name'],
            'role_slug' => $selected['slug_name'],
            'program' => 'petakom',
        ]);

        if ($selected['id'] === 1) {
            return redirect()->route('petakom-dashboard');
        }

        if ($selected['id'] === 2) {
            return redirect()->route('petakom-instrumen-list');
        }

        return redirect()->route('petakom-instrumen-list');
    }

    private function getAvailableSessionRoles(): array
    {
        $sessionRoles = collect(session('roles', []))
            ->filter(function ($role) {
                return !empty($role['id']) && !empty($role['slug_name']) && !empty($role['name']);
            })
            ->unique('slug_name')
            ->values()
            ->toArray();

        if (!empty($sessionRoles)) {
            return $sessionRoles;
        }

        $userId = Auth::id() ?: session('user_id');

        if (!$userId) {
            return [];
        }

        if (!Auth::check()) {
            $user = User::find($userId);

            if ($user) {
                Auth::login($user);
            }
        }

        return UserRole::query()
            ->where('users_id', $userId)
            ->whereHas('role', function ($query) {
                $query->where('is_active', 1)
                    ->whereNull('deleted_at');
            })
            ->with(['role:id,role_name,slug_name,is_active,deleted_at'])
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->role->id,
                    'slug_name' => $role->role->slug_name,
                    'name' => $role->role->role_name,
                ];
            })
            ->unique('slug_name')
            ->values()
            ->toArray();
    }

    private function getAvailablePetakomSessionRoles(): array
    {
        $sessionRoles = collect(session('roles', []))
            ->filter(function ($role) {
                return !empty($role['id']) && !empty($role['slug_name']) && !empty($role['name']);
            })
            ->unique('id')
            ->values()
            ->toArray();

        if (!empty($sessionRoles) && session('program') === 'petakom') {
            return $sessionRoles;
        }

        $userId = Auth::guard('petakom')->id() ?: session('user_id');

        if (!$userId) {
            return [];
        }

        if (!Auth::guard('petakom')->check()) {
            $user = UserPetakom::find($userId);

            if ($user) {
                Auth::guard('petakom')->login($user);
            }
        }

        return UserRolePetakom::query()
            ->where('users_id', $userId)
            ->whereHas('role', function ($query) {
                $query->where('is_active', 1)
                    ->whereNull('deleted_at');
            })
            ->with(['role:id,role_name,slug_name,is_active,deleted_at'])
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->role->id,
                    'slug_name' => $role->role->slug_name,
                    'name' => $role->role->role_name,
                ];
            })
            ->unique('id')
            ->values()
            ->toArray();
    }

    public function authGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function authGooglePetakom()
    {
        return Socialite::driver('google')
            ->redirectUrl(route('auth-google-petakom-callback'))
            ->redirect();
    }

    public function authGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->resolveGoogleUser($googleUser);

            if (!$user) {
                return redirect()->route('login')->with('error', 'Akun tidak terdaftar');
            }

            return $this->finishGoogleLogin($user);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    public function authGooglePetakomCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('auth-google-petakom-callback'))
                ->user();

            $user = $this->resolveGooglePetakomUser($googleUser);

            $email = Str::lower($googleUser->email);
            $domain = Str::after($email, '@');

            if ($domain === 'belajar.id' || Str::endsWith($domain, '.belajar.id')) {
                $jenis = 'ptk';
                $lookup = BelajarId::get($jenis, $googleUser->email);

                if (!is_object($lookup) || property_exists($lookup, 'error') || !property_exists($lookup, 'data')) {
                    return redirect('/')->with('error', 'Email tidak ditemukan di Belajar ID');
                }

                $this->syncProfilePetakomFromBelajarId($user, $lookup->data);
            } else {
                $this->syncProfilePetakomWithoutBelajarId($user);
            }

            return $this->finishGooglePetakomLogin($user);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    public function assignPetakomAdminByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter email tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = (string) $request->query('token');
        $expectedToken = (string) config('app.petakom_assign_token');

        if ($expectedToken === '' || !hash_equals($expectedToken, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 403);
        }

        $email = Str::lower(trim($request->query('email')));

        $result = DB::transaction(function () use ($email) {
            $user = UserPetakom::where('email', $email)->first();
            $isNewUser = false;

            if (!$user) {
                $isNewUser = true;
                $user = UserPetakom::create([
                    'name' => Str::title(str_replace(['.', '_', '-'], ' ', Str::before($email, '@'))),
                    'email' => $email,
                    'password' => Hash::make(config('app.default_password')),
                ]);
            }

            $userRole = UserRolePetakom::firstOrCreate([
                'users_id' => $user->id,
                'roles_id' => 1,
            ]);

            return [
                'type' => $isNewUser ? 'new_user' : 'existing_user',
                'user' => $user->fresh(),
                'role_created' => $userRole->wasRecentlyCreated,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => $result['type'] === 'new_user'
                ? 'User Petakom baru dibuat dan role admin berhasil di-assign.'
                : 'User Petakom existing diproses untuk assign role admin.',
            'data' => [
                'type' => $result['type'],
                'role_created' => $result['role_created'],
                'user' => [
                    'id' => $result['user']->id,
                    'name' => $result['user']->name,
                    'email' => $result['user']->email,
                ],
                'role' => [
                    'id' => 1,
                    'role_name' => 'Admin Petakom',
                    'slug_name' => 'admin_petakom',
                ],
            ],
        ]);
    }

    private function resolveGoogleUser($googleUser)
    {
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            return null;
        }

        $payload = [
            'last_login' => now(),
        ];

        if (!$user->google_id) {
            $payload['google_id'] = $googleUser->id;
        }

        if (!$user->avatar) {
            $payload['avatar'] = $googleUser->avatar;
        }

        $user->update($payload);

        return $user->fresh();
    }

    private function verifyTurnstile(Request $request): bool
    {
        $secretKey = config('services.turnstile.secret_key');

        if (!$secretKey) {
            return false;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->ok()) {
            return false;
        }

        return (bool) $response->json('success');
    }

    private function resolveGooglePetakomUser($googleUser)
    {
        $user = UserPetakom::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = UserPetakom::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => Hash::make(config('app.default_password')),
                'email_verified_at' => now(),
                'last_login' => now(),
            ]);

            UserRolePetakom::create([
                'users_id' => $user->id,
                'roles_id' => 2,
            ]);

            return $user;
        }

        $payload = [
            'last_login' => now(),
        ];

        if (!$user->google_id) {
            $payload['google_id'] = $googleUser->id;
        }

        if (!$user->avatar) {
            $payload['avatar'] = $googleUser->avatar;
        }

        $user->update($payload);
        $this->ensureDefaultPetakomRole($user);

        return $user->fresh();
    }

    private function ensureDefaultPetakomRole(UserPetakom $user): void
    {
        $hasActiveRole = UserRolePetakom::where('users_id', $user->id)
            ->whereHas('role', function ($query) {
                $query->where('is_active', 1)
                    ->whereNull('deleted_at');
            })
            ->exists();

        if ($hasActiveRole) {
            return;
        }

        $roleId = RolesPetakom::where('slug_name', 'user_petakom')
            ->where('is_active', 1)
            ->value('id') ?: 2;

        UserRolePetakom::firstOrCreate([
            'users_id' => $user->id,
            'roles_id' => $roleId,
        ]);
    }

    private function syncProfilePetakomFromBelajarId(UserPetakom $user, object $data): void
    {
        $jenjangNama = $data->jenjang ?? null;

        $cekJenjang = $jenjangNama
            ? RefJenjangPetakom::where('nama_jenjang', $jenjangNama)->first()
            : null;

        $jenjang = $cekJenjang ? $cekJenjang->id : null;

        ProfileUserPetakom::firstOrCreate(
            ['id_users' => $user->id],
            [
                'ptk_id' => $data->ptk_id ?? null,
                'npsn' => $data->npsn ?? null,
                'sekolah' => $data->school_name ?? null,
                'id_jenjang' => $jenjang,
            ]
        );
    }

    private function syncProfilePetakomWithoutBelajarId(UserPetakom $user): void
    {
        ProfileUserPetakom::firstOrCreate(
            ['id_users' => $user->id],
            [
                'ptk_id' => null,
                'npsn' => null,
                'sekolah' => null,
            ]
        );
    }

    private function finishGoogleLogin(User $user, ?string $redirectTo = null)
    {
        Auth::login($user);

        $rolesData = UserRole::where('users_id', $user->id)
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
            return redirect()->route('login')->with('error', 'Role Anda tidak aktif. Silakan hubungi administrator.');
        }

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'avatar' => $user->avatar,
            'roles' => $rolesData,
            'role_id' => null,
            'role_name' => null,
            'last_login' => now(),
            'program' => 'simdiklat'
        ]);

        if (count($rolesData) === 1) {
            session([
                'role_id'   => $rolesData[0]['id'],
                'role_name' => $rolesData[0]['name'],
                'role_slug' => $rolesData[0]['slug_name'],
            ]);
        }

        if ($redirectTo) {
            return redirect($redirectTo);
        }

        if (count($rolesData) === 1) {
            if ($rolesData[0]['slug_name'] == 'superadmin') {
                return redirect()->route('superadmin-dashboard');
            }

            return redirect()->route('dashboard');
        }

        return redirect()->route('choose-role');
    }

    private function finishGooglePetakomLogin(UserPetakom $user, ?string $redirectTo = null)
    {
        Auth::guard('petakom')->login($user);

        $rolesData = UserRolePetakom::where('users_id', $user->id)
            ->with('role:id,role_name,slug_name,is_active')
            ->get()
            ->filter(function ($item) {
                return $item->role && $item->role->is_active == 1;
            })
            ->map(function ($item) {
                return [
                    'id' => $item->role->id,
                    'slug_name' => $item->role->slug_name,
                    'name' => $item->role->role_name,
                ];
            })
            ->values()
            ->toArray();

        if (empty($rolesData)) {
            Auth::guard('petakom')->logout();
            return redirect()->route('login')->with('error', 'Role Anda tidak aktif. Silakan hubungi administrator.');
        }

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'avatar' => $user->avatar,
            'roles' => $rolesData,
            'role_id' => null,
            'role_name' => null,
            'last_login' => now(),
            'program' => 'petakom',
        ]);

        if (count($rolesData) === 1) {
            session([
                'role_id'   => $rolesData[0]['id'],
                'role_name' => $rolesData[0]['name'],
                'role_slug' => $rolesData[0]['slug_name'],
            ]);
        }

        if ($redirectTo) {
            return redirect($redirectTo);
        }

        if (count($rolesData) === 1) {
            if ((int) $rolesData[0]['id'] === 1) {
                return redirect()->route('petakom-dashboard');
            }

            if ((int) $rolesData[0]['id'] === 2) {
                return redirect()->route('profile-petakom');
            }
        }

        return redirect()->route('choose-role-petakom');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Auth::guard('petakom')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
