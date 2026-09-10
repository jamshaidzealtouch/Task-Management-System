<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{

    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

            'employee',

        ]);
    }
    public function view(User $user, Task $task): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

            'employee',

        ]);
    }
    public function create(User $user): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

        ]);
    }
    public function update(User $user, Task $task): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

            'employee',

        ]);
    }
    public function delete(User $user, Task $task): bool
    {
        return $user->role->value === 'admin';
    }
}