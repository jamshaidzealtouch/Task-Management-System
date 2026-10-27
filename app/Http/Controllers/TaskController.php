<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\Organization;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Notifications\TaskAssignedNotification;

class TaskController extends Controller
{
    // Get User Organizations

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

        return Organization::whereHas(
            'projects.users',
            function ($query) use ($userId) {
                $query->where(
                    'users.id',
                    $userId
                );
            }
        )->get();
    }

    private function getUserOrganization()
    {
        return $this
            ->getUserOrganizations()
            ->firstOrFail();
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
                abort(403);
            }

            return $organization;
        }
        if ($userRole === 'manager') {

            $hasAccess = Project::where(
                'organization_id',
                $organization->id
            )
                ->whereHas(
                    'users',
                    function ($query) use ($userId) {
                        $query->where(
                            'users.id',
                            $userId
                        );
                    }
                )
                ->exists();

            if (!$hasAccess) {
                abort(403);
            }

            return $organization;
        }
        if ($userRole === 'employee') {

            $hasAccess = Project::where(
                'organization_id',
                $organization->id
            )
                ->whereHas(
                    'users',
                    function ($query) use ($userId) {
                        $query->where(
                            'users.id',
                            $userId
                        );
                    }
                )
                ->exists();

            if (!$hasAccess) {
                abort(403);
            }

            return $organization;
        }

        abort(403);
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
    public function index(Project $project)
    {
        $this->checkProjectAccess($project);

        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'employee') {

            $tasks = $project
                ->tasks()
                ->where(
                    'assignee_id',
                    $userId
                )
                ->where(
                    'assignment_status',
                    'accepted'
                )
                ->latest()
                ->get();

        } elseif (
            $userRole === 'admin' ||
            $userRole === 'manager'
        ) {

            $tasks = $project
                ->tasks()
                ->latest()
                ->get();

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

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $employees = User::where(
            'role',
            'employee'
        )
            ->whereHas(
                'projects',
                function ($query) use ($project) {
                    $query->where(
                        'projects.id',
                        $project->id
                    );
                }
            )
            ->orderBy('name')
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
        Project $project,
        TaskService $taskService
    ) {
        $data = $request->validated();
    
        if ($request->hasFile('attachment')) {
    
            $data['attachment'] = $request->file('attachment');
        }
    
        $assignmentStatus = 'accepted';
    
        if (!empty($data['assignee_id'])) {
    
            $employee = User::find($data['assignee_id']);
    
            if (!$employee) {
    
                return back()
                    ->withInput()
                    ->with('error', 'Selected employee does not exist.');
            }
    
            // Make sure selected user is an employee.
            if ($employee->role?->value !== 'employee') {
    
                return back()
                    ->withInput()
                    ->with('error', 'Only employees can be assigned to tasks.');
            }
    
            $assignmentStatus = 'accepted';
        }

        $data['project_id'] = $project->id;
    
        $data['created_by'] = session('user_id');
    
        $data['assignment_status'] = $assignmentStatus;
    
        $taskService->createTask($data);

        if (!empty($data['assignee_id'])){
            $employee= User::find ($data['assignee_id']);

            if ($employee){

                $employee->notify(new TaskAssignedNotification());
        }
        }
    
        return redirect()
            ->route('projects.tasks.index', $project->id)
            ->with('success', 'Task assigned successfully.');
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

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $employees = User::where(
            'role',
            'employee'
        )
            ->whereHas(
                'projects',
                function ($query) use ($project) {
                    $query->where(
                        'projects.id',
                        $project->id
                    );
                }
            )
            ->orderBy('name')
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
        Task $task,
        TaskService $taskService
    ) {
        $this->checkTaskAccess(
            $project,
            $task
        );

        $userRole = session('user_role');

        $data = $request->validated();

        if ($userRole === 'employee') {

            if (!array_key_exists(
                'status',
                $data
            )) {
                abort(403);
            }

            $task->update([
                'status' => $data['status'],
            ]);

            return redirect()
                ->route(
                    'projects.tasks.index',
                    $project->id
                )
                ->with(
                    'success',
                    'Task status updated successfully.'
                );
        }

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        if (!empty($data['assignee_id'])) {

            $employee = User::where(
                'id',
                $data['assignee_id']
            )
                ->where(
                    'role',
                    'employee'
                )
                ->whereHas(
                    'projects',
                    function ($query) use ($project) {
                        $query->where(
                            'projects.id',
                            $project->id
                        );
                    }
                )
                ->first();

            if (!$employee) {
                abort(403);
            }

            if (
                $employee->primary_project_id !=
                $project->id
            ) {
                $data['assignment_status'] = 'pending';
            } else {
                $data['assignment_status'] = 'accepted';
            }
        }


        $taskService->updateTask(
            $task,
            $data
        );


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


    // Delete Task

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
            ->latest()
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

        $pendingTasks = Task::with([
            'project.organization',
            'creator'
        ])
            ->where(
                'assignee_id',
                $userId
            )
            ->where(
                'assignment_status',
                'pending'
            )
            ->latest()
            ->get();

        $tasks = Task::with([
            'project.organization',
            'creator'
        ])
            ->where(
                'assignee_id',
                $userId
            )
            ->where(
                'assignment_status',
                'accepted'
            )
            ->latest()
            ->get();

        return view(
            'tasks.my-tasks',
            compact(
                'tasks',
                'pendingTasks'
            )
        );
    }

    public function acceptTask(Task $task)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole !== 'employee') {
            abort(403);
        }

        if ($task->assignee_id != $userId) {
            abort(403);
        }

        if ($task->assignment_status !== 'pending') {

            return back()->with(
                'error',
                'This task assignment is no longer pending.'
            );
        }

        $task->update([
            'assignment_status' => 'accepted',
        ]);

        return redirect()
            ->route('my.tasks')
            ->with(
                'success',
                'Task accepted successfully.'
            );
    }

    public function rejectTask(Task $task)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole !== 'employee') {
            abort(403);
        }

        if ($task->assignee_id != $userId) {
            abort(403);
        }

        if ($task->assignment_status !== 'pending') {

            return back()->with(
                'error',
                'This task assignment is no longer pending.'
            );
        }

        $task->update([
            'assignment_status' => 'rejected',
        ]);

        return redirect()
            ->route('my.tasks')
            ->with(
                'success',
                'Task assignment rejected.'
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



        if ($task->assignment_status !== 'accepted') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'in:to_do,in_progress,completed',
            ],
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