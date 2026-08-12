<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
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
        $tasks = collect();

        if ($userRole === 'employee') {
            $organization = Organization::find(
                $user->organization_id
            );

            if ($organization) {
                $admin = User::find(
                    $organization->owner_id
                );
            }

            $tasks = Task::with([
                'project.organization'
            ])
                ->where('assignee_id', $userId)
                ->latest()
                ->get();
        }

        elseif ($userRole === 'manager') {
            $organization = Organization::whereHas(
                'users',
                function ($query) use ($userId) {
                    $query->where(
                        'users.id',
                        $userId
                    );
                }
            )->first();

            if ($organization) {
                $admin = User::find(
                    $organization->owner_id
                );
            }
        }

        elseif ($userRole === 'admin') {
            $organization = Organization::where(
                'owner_id',
                $userId
            )->first();
        }

        return view(
            'home',
            compact(
                'user',
                'organization',
                'admin',
                'tasks'
            )
        );
    }

    public function about()
    {
        return view('about');
    }
}