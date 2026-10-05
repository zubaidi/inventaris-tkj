<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',
        'jurusan_id',
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

    // public function isAdmin(): bool
    // {
    //     return $this->role === 'admin';
    // }

    /**
     * Relasi ke tabel jurusan.
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Cek apakah user ini super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah user ini admin (super_admin atau admin biasa).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Cek apakah user ini user biasa.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Cek apakah user ini kepala sekolah.
     */
    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }

    /**
     * Cek apakah user ini waka.
     */
    public function isWaka(): bool
    {
        return $this->role === 'waka';
    }

    /**
     * Cek apakah user ini "pimpinan" — kepala sekolah atau waka.
     * Read-only, bisa liat semua jurusan di sekolahnya.
     */
    public function isPimpinan(): bool
    {
        return in_array($this->role, ['kepala_sekolah', 'waka']);
    }

    /**
     * Cek apakah user bisa akses area admin (CRUD).
     */
    public function canAccessAdminPanel(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }
}
