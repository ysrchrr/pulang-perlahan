<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

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
            ->whereHas('role', function ($query) {
                $query->where('is_active', '1');
            })
            ->with('role:id,role_name,slug_name,is_active')
            ->get()
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

    public function authGoogle()
    {
        return Socialite::driver('google')->redirect();
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
