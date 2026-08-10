<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\Organization;

class TaskController extends Controller
{
    private function getUserOrganizations()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {
            return Organization::where(
                'owner_id',
                $userId
            )->get();
        }

        return Organization::whereHas('users', function ($query) use ($userId) {
            $query->where(
                'users.id',
                $userId
            );
        })->get();
    }

    private function getUserOrganization()
    {
        return $this->getUserOrganizations()->firstOrFail();
    }

    private function checkProjectAccess(Project $project)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        $organization = Organization::findOrFail(
            $project->organization_id
        );

        if ($userRole === 'admin') {
            if ($organization->owner_id != $userId) {
                abort(404);
            }

            return $organization;
        }

        $belongsToOrganization = $organization
            ->users()
            ->where(
                'users.id',
                $userId
            )
            ->exists();

        if (!$belongsToOrganization) {
            abort(404);
        }

        return $organization;
    }

    private function checkTaskAccess(
        Project $project,
        Task $task
    ) {
        $this->checkProjectAccess($project);

        if ($task->project_id != $project->id) {
            abort(404);
        }

        if (
            session('user_role') === 'employee' &&
            $task->assigned_to != session('user_id')
        ) {
            abort(403);
        }

        return true;
    }

    public function index(Project $project)
    {
        $this->checkProjectAccess($project);

        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'employee') {
            $tasks = $project->tasks()
                ->where(
                    'assigned_to',
                    $userId
                )
                ->get();
        } elseif (
            $userRole === 'admin' ||
            $userRole === 'manager'
        ) {
            $tasks = $project->tasks()->get();
        } else {
            abort(403);
        }

        return view(
            'tasks.index',
            compact(
                'project',
                'tasks'
            )
        );
    }

    public function create(Project $project)
    {
        $this->checkProjectAccess($project);

        $userRole = session('user_role');

        if ($userRole === 'employee') {
            abort(403);
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $employees = $project->organization
            ->users()
            ->where('role', 'employee')
            ->get();

        return view(
            'tasks.create',
            compact(
                'project',
                'employees'
            )
        );
    }

    public function store(
        StoreTaskRequest $request,
        Project $project
    ) {
        $this->checkProjectAccess($project);

        $userRole = session('user_role');

        if ($userRole === 'employee') {
            abort(403);
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $data = $request->validated();

        if (!empty($data['assigned_to'])) {
            $employeeExists = $project
                ->organization
                ->users()
                ->where(
                    'users.id',
                    $data['assigned_to']
                )
                ->where(
                    'role',
                    'employee'
                )
                ->exists();

            if (!$employeeExists) {
                abort(403);
            }
        }

        $data['project_id'] = $project->id;

        Task::create($data);

        return redirect()
            ->route(
                'projects.tasks.index',
                $project->id
            )
            ->with(
                'success',
                'Task added successfully.'
            );
    }

    public function show(
        Project $project,
        Task $task
    ) {
        $this->checkTaskAccess(
            $project,
            $task
        );

        return view(
            'tasks.show',
            compact(
                'project',
                'task'
            )
        );
    }

    public function edit(
        Project $project,
        Task $task
    ) {
        $this->checkTaskAccess(
            $project,
            $task
        );

        $userRole = session('user_role');

        if ($userRole === 'employee') {
            abort(403);
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $employees = $project
            ->organization
            ->users()
            ->where(
                'role',
                'employee'
            )
            ->get();

        return view(
            'tasks.edit',
            compact(
                'project',
                'task',
                'employees'
            )
        );
    }

    public function update(
        UpdateTaskRequest $request,
        Project $project,
        Task $task
    ) {
        $this->checkTaskAccess(
            $project,
            $task
        );

        $data = $request->validated();

        if (session('user_role') === 'employee') {
            if (!array_key_exists(
                'status',
                $data
            )) {
                abort(403);
            }

            $task->update([
                'status' => $data['status'],
            ]);
        } else {
            if (
                session('user_role') !== 'admin' &&
                session('user_role') !== 'manager'
            ) {
                abort(403);
            }

            if (!empty($data['assigned_to'])) {
                $employeeExists = $project
                    ->organization
                    ->users()
                    ->where(
                        'users.id',
                        $data['assigned_to']
                    )
                    ->where(
                        'role',
                        'employee'
                    )
                    ->exists();

                if (!$employeeExists) {
                    abort(403);
                }
            }

            $task->update($data);
        }

        return redirect()
            ->route(
                'projects.tasks.index',
                $project->id
            )
            ->with(
                'success',
                'Task updated successfully.'
            );
    }

    public function destroy(
        Project $project,
        Task $task
    ) {
        $this->checkTaskAccess(
            $project,
            $task
        );

        $userRole = session('user_role');

        if ($userRole === 'employee') {
            abort(403);
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $task->delete();

        return redirect()
            ->route(
                'projects.tasks.index',
                $project->id
            )
            ->with(
                'success',
                'Task deleted successfully.'
            );
    }

    public function trash(Project $project)
    {
        $this->checkProjectAccess($project);

        $userRole = session('user_role');

        if ($userRole === 'employee') {
            abort(403);
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $tasks = Task::onlyTrashed()
            ->where(
                'project_id',
                $project->id
            )
            ->get();

        return view(
            'tasks.trash',
            compact(
                'project',
                'tasks'
            )
        );
    }

    public function restore(
        Project $project,
        $task
    ) {
        $this->checkProjectAccess($project);

        $userRole = session('user_role');

        if ($userRole === 'employee') {
            abort(403);
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $task = Task::onlyTrashed()
            ->where(
                'project_id',
                $project->id
            )
            ->findOrFail($task);

        $task->restore();

        return redirect()
            ->route(
                'projects.tasks.trash',
                $project->id
            )
            ->with(
                'success',
                'Task restored successfully.'
            );
    }
}