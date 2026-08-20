<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Invitation;

class ProjectUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Project Users
    |--------------------------------------------------------------------------
    */

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

        /*
        |--------------------------------------------------------------------------
        | Admin Access
        |--------------------------------------------------------------------------
        */

        if ($userRole === 'admin') {

            if ($organization->owner_id != session('user_id')) {
                abort(404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Manager Access
        |--------------------------------------------------------------------------
        */

        else {

            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', session('user_id'))
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Get Accepted / Project Users
        |--------------------------------------------------------------------------
        */

        $users = $project
            ->users()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Get Pending Invitations
        |--------------------------------------------------------------------------
        */

        $invitations = Invitation::where('project_id', $project->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'projects.users.index',
            compact(
                'project',
                'organization',
                'users',
                'invitations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Remove User From Project
    |--------------------------------------------------------------------------
    */

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

        /*
        |--------------------------------------------------------------------------
        | Admin Access
        |--------------------------------------------------------------------------
        */

        if ($userRole === 'admin') {

            if ($organization->owner_id != session('user_id')) {
                abort(404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Manager Access
        |--------------------------------------------------------------------------
        */

        else {

            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', session('user_id'))
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check User Is Assigned To Project
        |--------------------------------------------------------------------------
        */

        $assigned = $project
            ->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$assigned) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Manager Can Only Remove Employee
        |--------------------------------------------------------------------------
        */

        $userRoleValue = $user->role instanceof \App\Enums\UserRole
            ? $user->role->value
            : $user->role;

        if (
            $userRole === 'manager' &&
            $userRoleValue !== 'employee'
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Remove User
        |--------------------------------------------------------------------------
        */

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