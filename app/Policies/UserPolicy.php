<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserType;

class UserPolicy
{
    /**
     * Determine if the user can view any users
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type === UserType::MANAGER->value;
    }

    /**
     * Determine if the user can create users
     */
    public function create(User $user): bool
    {
        return $user->user_type === UserType::MANAGER->value;
    }

    /**
     * Determine if the user can update a user
     */
    public function update(User $user, User $model): bool
    {
        return $user->user_type === UserType::MANAGER->value;
    }

    /**
     * Determine if the user can delete a user
     */
    public function delete(User $user, User $model): bool
    {
        // Managers can delete users, but not themselves
        return $user->user_type === UserType::MANAGER->value && $user->id !== $model->id;
    }
}
