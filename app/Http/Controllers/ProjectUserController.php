<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    // all user show
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
// new user create 
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

        $users = $organization
            ->users()
            ->get();

        $assignedUserIds = $project
            ->users()
            ->pluck('users.id');

        $users = $users
            ->whereNotIn('id', $assignedUserIds);

        if ($userRole === 'manager') {
            $users = $users->filter(function ($user) {
                $role = $user->role instanceof \App\Enums\UserRole
                    ? $user->role->value
                    : $user->role;

                return $role === 'employee';
            });
        }

        return view(
            'projects.users.create',
            compact(
                'project',
                'organization',
                'users'
            )
        );
    }
// user save in database
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
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);

        $user = User::findOrFail($data['user_id']);

        if ($user->organization_id != $organization->id) {
            abort(403);
        }

        $role = $user->role instanceof \App\Enums\UserRole
            ? $user->role->value
            : $user->role;

        if (
            $userRole === 'manager' &&
            $role !== 'employee'
        ) {
            abort(403);
        }

        $alreadyAssigned = $project
            ->users()
            ->where('users.id', $user->id)
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->with('error', 'This user is already assigned to the project.');
        }

        $project
            ->users()
            ->attach(
                $user->id,
                [
                    'role' => $role,
                ]
            );

        return redirect()
            ->route(
                'projects.users.index',
                $project->id
            )
            ->with(
                'success',
                'User added to project successfully.'
            );
    }

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

        $userRoleValue = $user->role instanceof \App\Enums\UserRole
            ? $user->role->value
            : $user->role;

        if (
            $userRole === 'manager' &&
            $userRoleValue !== 'employee'
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