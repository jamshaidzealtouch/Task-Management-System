<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * View all projects.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }

    /**
     * View single project.
     */
    public function view(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }

    /**
     * Create project.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }

    /**
     * Update project.
     */
    public function update(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }

    /**
     * Delete project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->role->value === 'admin';
    }
}