<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with([
            'project',
            'creator',
            'assignee'
        ])->get();

        return response()->json([
            'message' => 'Tasks fetched successfully.',
            'tasks' => $tasks,
        ], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'project_id' => $request->project_id,
            'created_by' => $request->created_by,
            'assignee_id' => $request->assignee_id,
            'assignment_status' => $request->assignment_status,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Task created successfully.',
            'task' => $task->load([
                'project',
                'creator',
                'assignee'
            ]),
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'message' => 'Task fetched successfully.',
            'task' => $task->load([
                'project',
                'creator',
                'assignee'
            ]),
        ], 200);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            'project_id' => $request->project_id,
            'created_by' => $request->created_by,
            'assignee_id' => $request->assignee_id,
            'assignment_status' => $request->assignment_status,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Task updated successfully.',
            'task' => $task->load([
                'project',
                'creator',
                'assignee'
            ]),
        ], 200);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Task status updated successfully.',
            'task' => $task,
        ], 200);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.',
        ], 200);
    }
}