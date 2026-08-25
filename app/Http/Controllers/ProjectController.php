<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Organization;
use App\Models\User;

class ProjectController extends Controller
{
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

        $user = User::findOrFail($userId);

        return $user->organizations()->get();
    }

    private function getUserOrganization()
    {
        return $this
            ->getUserOrganizations()
            ->firstOrFail();
    }

    private function checkOrganizationAccess(
        Organization $organization
    ) {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(403);
            }

            return;
        }

        $user = User::findOrFail($userId);

        $hasAccess = $user
            ->organizations()
            ->where(
                'organizations.id',
                $organization->id
            )
            ->exists();

        if (!$hasAccess) {
            abort(403);
        }
    }

    private function checkProjectAccess(
        Project $project
    ) {
        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess(
            $organization
        );

        return $organization;
    }

    private function addOrganizationMembersToProject(
        Project $project,
        Organization $organization
    ) {
        $members = $organization
            ->users()
            ->get();

        foreach ($members as $member) {

            $alreadyExists = $project
                ->users()
                ->where(
                    'users.id',
                    $member->id
                )
                ->exists();

            if (!$alreadyExists) {

                $role = 'employee';

                if ($member->role) {

                    $role = $member->role instanceof \BackedEnum
                        ? $member->role->value
                        : $member->role;
                }

                if (!in_array(
                    $role,
                    ['owner', 'manager', 'employee']
                )) {
                    $role = 'employee';
                }

                $project->users()->attach(
                    $member->id,
                    [
                        'role' => $role,
                    ]
                );
            }
        }
    }

    public function index()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {

            $organizationIds = Organization::where(
                'owner_id',
                $userId
            )
                ->pluck('id');

            $projects = Project::whereIn(
                'organization_id',
                $organizationIds
            )
                ->latest()
                ->get();
        }

        elseif (
            $userRole === 'manager' ||
            $userRole === 'employee'
        ) {

            $user = User::findOrFail($userId);

            $organizationIds = $user
                ->organizations()
                ->pluck('organizations.id');

            $projects = Project::whereIn(
                'organization_id',
                $organizationIds
            )
                ->latest()
                ->get();
        }

        else {
            abort(403);
        }

        return view(
            'projects.index',
            compact('projects')
        );
    }

    public function organizationProjects(
        Organization $organization
    ) {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if (
            $organization->owner_id !=
            session('user_id')
        ) {
            abort(403);
        }

        $projects = Project::where(
            'organization_id',
            $organization->id
        )
            ->latest()
            ->get();

        return view(
            'projects.index',
            compact(
                'projects',
                'organization'
            )
        );
    }

    public function create()
    {
        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $organizations =
            $this->getUserOrganizations();

        return view(
            'projects.create',
            compact('organizations')
        );
    }

    public function organizationCreate(
        Organization $organization
    ) {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if (
            $organization->owner_id !=
            session('user_id')
        ) {
            abort(403);
        }

        return view(
            'projects.create',
            compact('organization')
        );
    }

    public function store(
        StoreProjectRequest $request
    ) {
        $userRole = session('user_role');
        $userId = session('user_id');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $data = $request->validated();

        if ($userRole === 'admin') {

            if (
                empty($data['organization_id'])
            ) {
                abort(422);
            }

            $organization = Organization::where(
                'id',
                $data['organization_id']
            )
                ->where(
                    'owner_id',
                    $userId
                )
                ->firstOrFail();
        }

        else {

            if (
                empty($data['organization_id'])
            ) {
                abort(422);
            }

            $organization = Organization::findOrFail(
                $data['organization_id']
            );

            $this->checkOrganizationAccess(
                $organization
            );

            $data['organization_id'] =
                $organization->id;
        }

        $data['user_id'] = $userId;

        $project = Project::create($data);

        $this->addOrganizationMembersToProject(
            $project,
            $organization
        );

        return redirect()
            ->route('projects.index')
            ->with(
                'success',
                'Project created successfully.'
            );
    }

    public function organizationStore(
        StoreProjectRequest $request,
        Organization $organization
    ) {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if (
            $organization->owner_id !=
            session('user_id')
        ) {
            abort(403);
        }

        $data = $request->validated();

        $data['organization_id'] =
            $organization->id;

        $data['user_id'] =
            session('user_id');

        $project = Project::create($data);

        $this->addOrganizationMembersToProject(
            $project,
            $organization
        );

        return redirect()
            ->route(
                'organizations.projects.index',
                $organization->id
            )
            ->with(
                'success',
                'Project created successfully.'
            );
    }

    public function show(
        Project $project
    ) {
        $this->checkProjectAccess(
            $project
        );

        return view(
            'projects.show',
            compact('project')
        );
    }

    public function edit(
        Project $project
    ) {
        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $organization =
            $this->checkProjectAccess(
                $project
            );

        if ($userRole === 'admin') {

            $organizations =
                $this->getUserOrganizations();

            return view(
                'projects.edit',
                compact(
                    'project',
                    'organizations'
                )
            );
        }

        return view(
            'projects.edit',
            compact(
                'project',
                'organization'
            )
        );
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project
    ) {
        $userRole = session('user_role');
        $userId = session('user_id');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $currentOrganization =
            Organization::findOrFail(
                $project->organization_id
            );

        $this->checkOrganizationAccess(
            $currentOrganization
        );

        $data = $request->validated();

        if ($userRole === 'admin') {

            if (
                empty($data['organization_id'])
            ) {
                abort(422);
            }

            $newOrganization =
                Organization::where(
                    'id',
                    $data['organization_id']
                )
                    ->where(
                        'owner_id',
                        $userId
                    )
                    ->firstOrFail();
        }

        else {

            $newOrganization =
                Organization::findOrFail(
                    $data['organization_id']
                );

            $this->checkOrganizationAccess(
                $newOrganization
            );

            $data['organization_id'] =
                $newOrganization->id;
        }

        $organizationChanged =
            $project->organization_id !=
            $newOrganization->id;

        $project->update($data);

        if ($organizationChanged) {

            $this->addOrganizationMembersToProject(
                $project,
                $newOrganization
            );
        }

        return redirect()
            ->route('projects.index')
            ->with(
                'success',
                'Project updated successfully.'
            );
    }

    public function destroy(
        Project $project
    ) {
        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $this->checkProjectAccess(
            $project
        );

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with(
                'success',
                'Project deleted successfully.'
            );
    }
}