<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DashboardService
{
    public function getDashboardData($userId, $userRole)
    {
        // Get currently logged-in user
        $user = User::findOrFail($userId);

        $notifications = $user->notifications;

        $organization = null;
        $admin = null;
        $projects = collect();

   
        $tasksQuery = Task::query();

      
        if ($userRole === 'employee') {

            // Get employee organization
            $organization = Organization::find($user->organization_id);

            if ($organization) {

                // Get organization admin/owner
                $admin = User::find($organization->owner_id);

                // Get organization projects
                $projects = Project::where(
                    'organization_id',
                    $user->organization_id
                )
                    ->latest()
                    ->get();
            }

            $tasksQuery->where('assignee_id', $userId);
        }
        elseif ($userRole === 'manager') {

            // Get manager organization
            $organization = Organization::find($user->organization_id);

            if ($organization) {

                // Get organization admin/owner
                $admin = User::find($organization->owner_id);

                // Get organization projects
                $projects = Project::where(
                    'organization_id',
                    $organization->id
                )
                    ->latest()
                    ->get();

                // Manager can see all tasks of organization projects
                $tasksQuery->whereHas('project', function ($query) use ($organization) {

                    $query->where(
                        'organization_id',
                        $organization->id
                    );

                });
            }
        }

        elseif ($userRole === 'admin') {

            // Find organization owned by admin
            $organization = Organization::where(
                'owner_id',
                $userId
            )->first();

            if ($organization) {

                // Get organization projects
                $projects = Project::where(
                    'organization_id',
                    $organization->id
                )
                    ->latest()
                    ->get();

                // Admin can see all tasks of organization projects
                $tasksQuery->whereHas('project', function ($query) use ($organization) {

                    $query->where(
                        'organization_id',
                        $organization->id
                    );

                });
            }
        }

        $tasks = (clone $tasksQuery)
            ->with([
                'project.organization',
                'creator',
                'assignee',
            ])
            ->latest()
            ->get();

        // Total projects
        $totalProjects = $projects->count();

        // Total tasks
        $totalTasks = (clone $tasksQuery)->count();

        $statusCounts = (clone $tasksQuery)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $priorityCounts = (clone $tasksQuery)
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $overdueTasks = (clone $tasksQuery)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
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

                // Get tasks assigned to this employee
                $employeeTasks = $tasks->where(
                    'assignee_id',
                    $employee->id
                );

                $employeePerformance->push([

                    // Employee information
                    'employee' => $employee,

                    // Total tasks
                    'total_tasks' => $employeeTasks->count(),

                    // Completed tasks
                    'completed_tasks' => $employeeTasks
                        ->where('status', 'completed')
                        ->count(),

                    // To-do tasks
                    'pending_tasks' => $employeeTasks
                        ->where('status', 'to_do')
                        ->count(),

                    // In-progress tasks
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
            'statusCounts',
            'priorityCounts',
            'overdueTasks',
            'notifications',
            'employeePerformance'
        );
    }
}