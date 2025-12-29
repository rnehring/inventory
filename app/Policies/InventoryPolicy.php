<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inventory;
use App\Enums\UserType;

class InventoryPolicy
{
    /**
     * Determine if the user can view costs
     */
    public function viewCosts(User $user): bool
    {
        return $user->user_type === UserType::MANAGER->value;
    }

    /**
     * Determine if the user can update inventory counts
     */
    public function update(User $user, ?Inventory $inventory = null): bool
    {
        // All authenticated users can update counts
        return true;
    }

    /**
     * Determine if the user can delete inventory records
     */
    public function delete(User $user, Inventory $inventory): bool
    {
        // Only managers can delete
        return $user->user_type === UserType::MANAGER->value;
    }

    /**
     * Determine if the user can upload inventory data
     */
    public function upload(User $user): bool
    {
        // Only managers can upload
        return $user->user_type === UserType::MANAGER->value;
    }
}
