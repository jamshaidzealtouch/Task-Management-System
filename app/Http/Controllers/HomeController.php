<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;

class HomeController extends Controller
{
    public function home()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

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
                'project.organization'
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
            }
        }

        return view(
            'home',
            compact(
                'user',
                'organization',
                'admin',
                'projects',
                'tasks'
            )
        );
    }

    public function about()
    {
        return view('about');
    }
}