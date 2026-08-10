<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * View all tasks.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

            'employee',

        ]);
    }

    /**
     * View single task.
     */
    public function view(User $user, Task $task): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

            'employee',

        ]);
    }

    /**
     * Create task.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

        ]);
    }

    /**
     * Update task.
     */
    public function update(User $user, Task $task): bool
    {
        return in_array($user->role->value, [

            'admin',

            'manager',

            'employee',

        ]);
    }

    /**
     * Delete task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->role->value === 'admin';
    }
}