<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        // Check if a file was uploaded.
        if (
            isset($data['attachment']) &&
            $data['attachment'] instanceof UploadedFile
        ) {
           
            $data['attachment_path'] = $data['attachment']->store(
                'attachments/tasks',
                'public'
            );

            unset($data['attachment']);
        }

        $task = Task::create($data);

        // Load task relationships.
        return $task->load([
            'project',
            'creator',
            'assignee'
        ]);
    }

    /**
     * Update an existing task.
     */
    public function updateTask(Task $task, array $data): Task
    {
        // Check if a new file was uploaded.
        if (
            isset($data['attachment']) &&
            $data['attachment'] instanceof UploadedFile
        ) {
            // Delete the old attachment if it exists.
            if ($task->attachment_path) {
                Storage::disk('public')->delete(
                    $task->attachment_path
                );
            }

            // Store the new file.
            $data['attachment_path'] = $data['attachment']->store(
                'attachments/tasks',
                'public'
            );

            // Remove the UploadedFile object.
            unset($data['attachment']);
        }

        // Update task.
        $task->update($data);

        // Load task relationships.
        return $task->load([
            'project',
            'creator',
            'assignee'
        ]);
    }

    /**
 * Update task status.
 */
public function updateTaskStatus(Task $task, array $data): Task
{
    $task->update([
        'status' => $data['status'],
    ]);

    return $task->load([
        'project',
        'creator',
        'assignee',
    ]);
}

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}