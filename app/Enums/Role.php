<?php

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            Role::SuperAdmin => 'Super Admin',
            Role::Admin => 'Admin',
            Role::Staff => 'Staff',
        };
    }

    public function canAccessActivityLog(): bool
    {
        return $this === Role::Admin;
    }
}
