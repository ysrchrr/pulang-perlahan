<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'users_roles', 'users_id', 'roles_id');
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('role_name', $roleName)->exists();
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'created_by');
    }

    public function assignedPengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'assigned_to');
    }

    public function escalatedPengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'escalated_to');
    }

    public static function createFromPegawai(Pegawai $pegawai, $roleId = 6)
    {
        return DB::transaction(function () use ($pegawai, $roleId) {
            if (empty($pegawai->email)) {
                throw new \Exception('Email pegawai wajib diisi untuk membuat akun.');
            }

            $existingUser = self::where('email', $pegawai->email)->first();
            if ($existingUser) {
                throw new \Exception('Email sudah digunakan user lain.');
            }

            $user = self::create([
                'name' => $pegawai->nama,
                'email' => $pegawai->email,
                'password' => bcrypt(env('DEFAULT_PASSWORD', '12345678')),
            ]);

            UserRole::create([
                'users_id' => $user->id,
                'roles_id' => $roleId,
            ]);

            $pegawai->update([
                'users_id' => $user->id,
            ]);

            return $user;
        });
    }
}
