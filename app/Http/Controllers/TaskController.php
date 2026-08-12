<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\Organization;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //Ye current user ki organizations nikalta hai.
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
            $query->where('users.id', $userId);
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
//Admin tabhi project access kar sakta hai jab woh organization ka owner ho.
        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(404);
            }

            return $organization;
        }
//Kya ye user organization ka member hai?
        $belongsToOrganization = $organization
            ->users()
            ->where('users.id', $userId)
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
            $task->assignee_id != session('user_id')
        ) {
            abort(403);
        }

        return true;
    }
//That's the right approach for your current organization/role structure.
    public function index(Project $project)
    {
        $this->checkProjectAccess($project);

        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'employee') {

            $tasks = $project->tasks()
                ->where('assignee_id', $userId)
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
            compact('project', 'tasks')
        );
    }

    public function create(Project $project)
    {
        $this->checkProjectAccess($project);

        $userRole = session('user_role');

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
            compact('project', 'employees')
        );
    }

    public function store(
        StoreTaskRequest $request,
        Project $project
    ) {
        $this->checkProjectAccess($project);

        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $data = $request->validated();

        if (!empty($data['assignee_id'])) {

            $employeeExists = $project
                ->organization
                ->users()
                ->where(
                    'users.id',
                    $data['assignee_id']
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

        $data['created_by'] = session('user_id');

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
            compact('project', 'task')
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

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $employees = $project
            ->organization
            ->users()
            ->where('role', 'employee')
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

            if (!array_key_exists('status', $data)) {
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

            if (!empty($data['assignee_id'])) {

                $employeeExists = $project
                    ->organization
                    ->users()
                    ->where(
                        'users.id',
                        $data['assignee_id']
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

    public function myTasks()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole !== 'employee') {
            abort(403);
        }

        $tasks = Task::with([
            'project.organization'
        ])
            ->where(
                'assignee_id',
                $userId
            )
            ->whereHas('project', function ($query) use ($userId) {

                $query->whereHas(
                    'organization',
                    function ($query) use ($userId) {

                        $query->whereHas(
                            'users',
                            function ($query) use ($userId) {

                                $query->where(
                                    'users.id',
                                    $userId
                                );
                            }
                        );
                    }
                );
            })
            ->latest()
            ->get();

        return view(
            'tasks.my-tasks',
            compact('tasks')
        );
    }

    public function updateMyTaskStatus(
        Request $request,
        Task $task
    ) {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole !== 'employee') {
            abort(403);
        }

        if ($task->assignee_id != $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('my.tasks')
            ->with(
                'success',
                'Task status updated successfully.'
            );
    }
}