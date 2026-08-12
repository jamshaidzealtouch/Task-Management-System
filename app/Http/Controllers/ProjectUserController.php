<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectUserController extends Controller
{
    public function index(Project $project)
    {
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {
            if ($organization->owner_id != session('user_id')) {
                abort(404);
            }
        } else {
            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', session('user_id'))
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        $users = $project
            ->users()
            ->get();

        return view(
            'projects.users.index',
            compact(
                'project',
                'organization',
                'users'
            )
        );
    }

    public function create(Project $project)
    {
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {
            if ($organization->owner_id != session('user_id')) {
                abort(404);
            }
        } else {
            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', session('user_id'))
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        return view(
            'projects.users.create',
            compact(
                'project',
                'organization'
            )
        );
    }

    public function store(
        Request $request,
        Project $project
    ) {
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {
            if ($organization->owner_id != session('user_id')) {
                abort(404);
            }
        } else {
            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', session('user_id'))
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:4',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:manager,employee',
            ],
        ]);

        if (
            $userRole === 'manager' &&
            $data['role'] !== 'employee'
        ) {
            abort(403);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'organization_id' => $organization->id,
        ]);

        $project->users()->attach(
            $user->id,
            [
                'role' => $data['role'],
            ]
        );

        return redirect()
            ->route(
                'projects.users.index',
                $project->id
            )
            ->with(
                'success',
                'User account created and assigned to project successfully.'
            );
    }
//delete base on role
    public function destroy(
        Project $project,
        User $user
    ) {
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {
            if ($organization->owner_id != session('user_id')) {
                abort(404);
            }
        } else {
            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', session('user_id'))
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        $assigned = $project
            ->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$assigned) {
            abort(404);
        }

        if (
            $userRole === 'manager' &&
            $user->role->value !== 'employee'
        ) {
            abort(403);
        }

        $project
            ->users()
            ->detach($user->id);

        return redirect()
            ->route(
                'projects.users.index',
                $project->id
            )
            ->with(
                'success',
                'User removed from project successfully.'
            );
    }
}