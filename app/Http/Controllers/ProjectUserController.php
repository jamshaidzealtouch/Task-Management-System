<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Invitation;

class ProjectUserController extends Controller
{
    public function index(Project $project)
    {
        $userRole = session('user_role');
        $userId = session('user_id');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(404);
            }

        } else {

            $belongsToOrganization = User::where('id', $userId)
                ->where('organization_id', $organization->id)
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        $users = User::where(
            'organization_id',
            $organization->id
        )
            ->orderBy('name')
            ->get();

        $invitations = Invitation::where(
            'project_id',
            $project->id
        )
            ->where(
                'status',
                'pending'
            )
            ->orderByDesc('created_at')
            ->get();

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

    public function destroy(
        Project $project,
        User $user
    ) {
        $userRole = session('user_role');
        $userId = session('user_id');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(404);
            }

        } else {

            $belongsToOrganization = User::where('id', $userId)
                ->where('organization_id', $organization->id)
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }
        }

        if (
            !$user->organization_id ||
            $user->organization_id != $organization->id
        ) {
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

        return redirect()
            ->route(
                'projects.users.index',
                $project->id
            )
            ->with(
                'success',
                'Organization users are automatically available in this project.'
            );
    }
}