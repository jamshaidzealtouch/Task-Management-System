<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService)
    {
    }
    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
    
        return response()->json([
            'message' => 'Tasks fetched successfully.',
            'tasks' => TaskResource::collection($tasks),
        ], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask(
            $request->validated()
        );
    
        return response()->json([
            'message' => 'Task created successfully.',
            'task' => new TaskResource($task),
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'message' => 'Task fetched successfully.',
            'task' => new TaskResource(
                $task->load([
                    'project',
                    'creator',
                    'assignee'
                ])
            ),
        ], 200);
    }
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task = $this->taskService->updateTask(
            $task,
            $request->validated()
        );
    
        return response()->json([
            'message' => 'Task updated successfully.',
            'task' => new TaskResource($task),
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
           'task' => new TaskResource(
            $task->load([
                'project',
                'creator',
                'assignee'
            ])
        ),
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