<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    // View organization list
    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }

    // View single organization
    public function view(User $user, Organization $organization): bool
    {
        if ($user->role->value === 'admin') {
            return $organization->owner_id === $user->id;
        }

        if ($user->role->value === 'manager') {
            return $organization->users()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }

    // Create organization
    public function create(User $user): bool
    {
        return $user->role->value === 'admin';
    }

    // Update organization
    public function update(User $user, Organization $organization): bool
    {
        return $organization->owner_id === $user->id
            && $user->role->value === 'admin';
    }

    // Delete organization
    public function delete(User $user, Organization $organization): bool
    {
        return $organization->owner_id === $user->id
            && $user->role->value === 'admin';
    }
}