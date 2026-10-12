<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case KEPALA_DESA = 'kepala_desa';
    case WARGA = 'warga';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::KEPALA_DESA => 'Kepala Desa',
            self::WARGA => 'Warga',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-purple-100 text-purple-800 border border-purple-200',
            self::KEPALA_DESA => 'bg-amber-100 text-amber-800 border border-amber-200',
            self::WARGA => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this, [self::ADMIN, self::KEPALA_DESA]);
    }
}
