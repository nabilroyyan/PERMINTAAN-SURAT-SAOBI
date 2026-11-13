<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case WARGA = 'warga';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::WARGA => 'Warga',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-purple-100 text-purple-800 border border-purple-200',
            self::WARGA => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return $this === self::ADMIN;
    }
}
