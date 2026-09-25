<?php

namespace App\Services;

use App\Models\User;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;

class DashboardService
{
    public function getDashboardData($userId, $userRole)
    {
        $user = User::findOrFail($userId);

        $organization = null;
        $admin = null;
        $projects = collect();
        $tasks = collect();


        if ($userRole === 'employee') {

            $organization = Organization::find($user->organization_id);

            if ($organization) {

                $admin = User::find($organization->owner_id);

                $projects = Project::where(
                    'organization_id',
                    $user->organization_id
                )
                    ->latest()
                    ->get();
            }

            $tasks = Task::with([
                'project.organization',
                'creator',
                'assignee'
            ])
                ->where('assignee_id', $userId)
                ->latest()
                ->get();
        }

        elseif ($userRole === 'manager') {

            $organization = Organization::find($user->organization_id);

            if ($organization) {

                $admin = User::find($organization->owner_id);

                $projects = Project::where(
                    'organization_id',
                    $user->organization_id
                )
                    ->latest()
                    ->get();

                    $tasks = Task::whereHas('project', function ($query) use ($organization) {

                        $query->where(
                            'organization_id',
                            $organization->id
                        );
    
                    })
                        ->with([
                            'project.organization',
                            'creator',
                            'assignee'
                        ])
                        ->latest()
                        ->get();
                }
            }

            elseif ($userRole === 'admin') {

                $organization = Organization::where(
                    'owner_id',
                    $userId
                )->first();

                if ($organization) {

                    $projects = Project::where(
                        'organization_id',
                        $organization->id
                    )
                        ->latest()
                        ->get();

                        $tasks = Task::whereHas('project', function ($query) use ($organization) {

                            $query->where(
                                'organization_id',
                                $organization->id
                            );

                        })
                        ->with([
                            'project.organization',
                            'creator',
                            'assignee'
                        ])
                        ->latest()
                        ->get();
                }
            }

            $totalProjects = $projects->count();

            $totalTasks = $tasks->count();
    
            $completedTasks = $tasks
                ->where('status', 'completed')
                ->count();
    
            $pendingTasks = $tasks
                ->where('status', 'to_do')
                ->count();
    
            $overdueTasks = $tasks
                ->where('due_date', '<', now())
                ->where('status', '!=', 'completed')
                ->count();


        $lowPriorityTasks = $tasks
        ->where('priority', 'low')
        ->count();

    $mediumPriorityTasks = $tasks
        ->where('priority', 'medium')
        ->count();

    $highPriorityTasks = $tasks
        ->where('priority', 'high')
        ->count();

        $todoTasks = $tasks
        ->where('status', 'to_do')
        ->count();

    $inProgressTasks = $tasks
        ->where('status', 'in_progress')
        ->count();

    $completedStatusTasks = $tasks
        ->where('status', 'completed')
        ->count();

        $employeePerformance = collect();

        if ($userRole === 'admin' || $userRole === 'manager') {

            $employees = User::where(
                'organization_id',
                $organization?->id
            )
                ->where('role', 'employee')
                ->get();

            foreach ($employees as $employee) {

                $employeeTasks = $tasks->where(
                    'assignee_id',
                    $employee->id
                );

                $employeePerformance->push([

                    'employee' => $employee,

                    'total_tasks' => $employeeTasks->count(),

                    'completed_tasks' => $employeeTasks
                        ->where('status', 'completed')
                        ->count(),

                    'pending_tasks' => $employeeTasks
                        ->where('status', 'to_do')
                        ->count(),

                        'in_progress_tasks' => $employeeTasks
                        ->where('status', 'in_progress')
                        ->count(),

                ]);
            }
        }

        return compact(
            'user',
            'organization',
            'admin',
            'projects',
            'tasks',
            'totalProjects',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'overdueTasks',
            'lowPriorityTasks',
            'mediumPriorityTasks',
            'highPriorityTasks',
            'todoTasks',
            'inProgressTasks',
            'completedStatusTasks',
            'employeePerformance'

        );
    }
}