<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            Role::Admin => 'Admin',
            Role::Staff => 'Staff',
        };
    }

    public function canAccessActivityLog(): bool
    {
        return $this === Role::Admin;
    }
}
