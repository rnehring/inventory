<?php

namespace App\Enums;

enum UserType: int
{
    case EMPLOYEE = 1;
    case MANAGER = 2;

    public function label(): string
    {
        return match($this) {
            self::EMPLOYEE => 'Employee',
            self::MANAGER => 'Manager',
        };
    }

    public function canViewCosts(): bool
    {
        return $this === self::MANAGER;
    }

    public function canEditInventory(): bool
    {
        return $this === self::MANAGER;
    }

    public function canManageUsers(): bool
    {
        return $this === self::MANAGER;
    }
}
