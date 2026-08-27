<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class InvitationPolicy
{
    // Determine whether the user can create an invitation
    public function create(User $user, Project $project): bool
    {
        // Get project organization
        $organization = $project->organization;

        if (!$organization) {
            return false;
        }

        // Admin
        if ($user->role === 'admin') {
            return $organization->owner_id == $user->id;
        }

        // Manager
        if ($user->role === 'manager') {
            return $user->organization_id == $organization->id;
        }

        // Employee
        return false;
    }
}