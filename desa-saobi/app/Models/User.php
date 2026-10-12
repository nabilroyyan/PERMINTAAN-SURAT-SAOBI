<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'password_plain',
        'nik',
        'role',
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
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isKepalaDesa(): bool
    {
        return $this->role === UserRole::KEPALA_DESA;
    }

    public function isWarga(): bool
    {
        return $this->role === UserRole::WARGA;
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->isAdmin() || $this->isKepalaDesa();
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->role instanceof UserRole ? $this->role->label() : ucfirst($this->role ?? 'Warga');
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return $this->role instanceof UserRole ? $this->role->badgeClass() : 'bg-slate-100 text-slate-700';
    }

    public function suratRequests()
    {
        return $this->hasMany(SuratRequest::class);
    }

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'nik', 'nik');
    }
}
