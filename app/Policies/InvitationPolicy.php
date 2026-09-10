<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\Project;
use App\Models\User;

class InvitationPolicy
{
    // Check if user can create invitation
    public function create(User $user, Project $project): bool
    {
        // Admin can invite only in his own organization
        if ($user->role->value === 'admin') {

            return $project->organization
                && $project->organization->owner_id === $user->id;
        }

        // Manager can invite only if he belongs to project organization
        if ($user->role->value === 'manager') {

            return $project->organization
                && $project->organization
                    ->users()
                    ->where('users.id', $user->id)
                    ->exists();
        }

        return false;
    }

    // Check if user can accept invitation
    public function accept(User $user, Invitation $invitation): bool
    {
        return $invitation->email === $user->email;
    }
}