<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function getAllTasks()
    {
        return Task::with([
            'project',
            'creator',
            'assignee'
        ])->get();
    }
    public function createTask(array $data): Task
    {
        $task = Task::create($data);

        return $task->load([
            'project',
            'creator',
            'assignee'
        ]);
    }
    public function updateTask(Task $task, array $data): Task
{
    $task->update($data);

    return $task->load([
        'project',
        'creator',
        'assignee'
    ]);
}
}