<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{

    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
    public function view(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
    public function create(User $user): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
    public function update(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
    public function delete(User $user, Project $project): bool
    {
        return $user->role->value === 'admin';
    }
    public function manageUsers(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
    public function addUser(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
    public function removeUser(User $user, Project $project): bool
    {
        return in_array($user->role->value, [
            'admin',
            'manager',
        ]);
    }
}