<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Organization;

class ReportController extends Controller
{
    public function exportTasks()
    {
        $userId = session('user_id');

        $userRole = session('user_role');

        $user = User::findOrFail($userId);

        $tasks = collect();

        if ($userRole === 'employee') {

            $tasks = Task::with([
                'project',
                'assignee'
            ])
                ->where('assignee_id', $userId)
                ->latest()
                ->get();
        }

        elseif ($userRole === 'manager') {

            $organization = Organization::find(
                $user->organization_id
            );

            if ($organization) {

                $tasks = Task::whereHas(
                    'project',
                    function ($query) use ($organization) {

                        $query->where(
                            'organization_id',
                            $organization->id
                        );

                    }
                )
                    ->with([
                        'project',
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

                $tasks = Task::whereHas(
                    'project',
                    function ($query) use ($organization) {

                        $query->where(
                            'organization_id',
                            $organization->id
                        );

                    }
                )
                    ->with([
                        'project',
                        'assignee'
                    ])
                    ->latest()
                    ->get();
            }
        }

        return response()->streamDownload(

            function () use ($tasks) {

                $file = fopen('php://output', 'w');

                fputcsv($file, [
                    'Task',
                    'Project',
                    'Employee',
                    'Priority',
                    'Status',
                    'Due Date',
                ]);

                foreach ($tasks as $task) {

                    $priority = $task->priority;

                    if ($priority instanceof \BackedEnum) {
                        $priority = $priority->value;
                    }

                    $status = $task->status;

                    if ($status instanceof \BackedEnum) {
                        $status = $status->value;
                    }

                    fputcsv($file, [
                        $task->title,

                        $task->project
                            ? $task->project->title
                            : 'No Project',

                        $task->assignee
                            ? $task->assignee->name
                            : 'Unassigned',

                        $priority,

                        $status,

                        $task->due_date
                            ? $task->due_date->format('Y-m-d')
                            : 'N/A',
                    ]);
                }

                fclose($file);
            },

            'task-report.csv',

            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}