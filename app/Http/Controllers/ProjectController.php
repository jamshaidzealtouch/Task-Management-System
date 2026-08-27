<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Organization;
use App\Models\User;

class ProjectController extends Controller
{
    // Get user organizations
    private function getUserOrganizations()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        // Admin owns organizations
        if ($userRole === 'admin') {
            return Organization::where('owner_id', $userId)->get();
        }

        // Manager / Employee
        $user = User::findOrFail($userId);

        return $user->organizations()->get();
    }

    // Get manager current organization
    private function getManagerOrganization()
    {
        $userId = session('user_id');

        $user = User::findOrFail($userId);

        if (!$user->organization_id) {
            abort(403, 'Manager is not assigned to any organization.');
        }

        return Organization::findOrFail($user->organization_id);
    }

    // Get first organization
    private function getUserOrganization()
    {
        return $this->getUserOrganizations()->firstOrFail();
    }

    // Check organization access
    private function checkOrganizationAccess(Organization $organization)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        // Admin
        if ($userRole === 'admin') {
            if ($organization->owner_id != $userId) {
                abort(403);
            }

            return;
        }

        // Manager
        if ($userRole === 'manager') {
            $user = User::findOrFail($userId);

            if ($user->organization_id != $organization->id) {
                abort(403);
            }

            return;
        }

        // Employee
        if ($userRole === 'employee') {
            $user = User::findOrFail($userId);

            $hasAccess = $user
                ->organizations()
                ->where('organizations.id', $organization->id)
                ->exists();

            if (!$hasAccess) {
                abort(403);
            }

            return;
        }

        abort(403);
    }

    // Check project access
    private function checkProjectAccess(Project $project)
    {
        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess($organization);

        return $organization;
    }

    // Add organization members to project
    private function addOrganizationMembersToProject(
        Project $project,
        Organization $organization
    ) {
        $members = $organization->users()->get();

        foreach ($members as $member) {
            $alreadyExists = $project
                ->users()
                ->where('users.id', $member->id)
                ->exists();

            if (!$alreadyExists) {
                $role = 'employee';

                if ($member->role) {
                    $role = $member->role instanceof \BackedEnum
                        ? $member->role->value
                        : $member->role;
                }

                if (!in_array($role, ['owner', 'manager', 'employee'])) {
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

    // Project list
    public function index()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        // Admin
        if ($userRole === 'admin') {
            $organizationIds = Organization::where(
                'owner_id',
                $userId
            )->pluck('id');

            $projects = Project::whereIn(
                'organization_id',
                $organizationIds
            )
                ->latest()
                ->get();
        }

        // Manager
        elseif ($userRole === 'manager') {
            $user = User::findOrFail($userId);

            if (!$user->organization_id) {
                abort(403);
            }

            $projects = Project::where(
                'organization_id',
                $user->organization_id
            )
                ->latest()
                ->get();
        }

        // Employee
        elseif ($userRole === 'employee') {
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

    // Organization projects
    public function organizationProjects(Organization $organization)
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if ($organization->owner_id != session('user_id')) {
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
            compact('projects', 'organization')
        );
    }

    // Create project
    public function create()
    {
        $userRole = session('user_role');

        if ($userRole !== 'admin' && $userRole !== 'manager') {
            abort(403);
        }

        // Admin
        if ($userRole === 'admin') {
            $organizations = $this->getUserOrganizations();

            return view(
                'projects.create',
                compact('organizations')
            );
        }

        // Manager
        $organization = $this->getManagerOrganization();

        return view(
            'projects.create',
            compact('organization')
        );
    }

    // Organization create
    public function organizationCreate(Organization $organization)
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if ($organization->owner_id != session('user_id')) {
            abort(403);
        }

        return view(
            'projects.create',
            compact('organization')
        );
    }

    // Store project
    public function store(StoreProjectRequest $request)
    {
        $userRole = session('user_role');
        $userId = session('user_id');

        if ($userRole !== 'admin' && $userRole !== 'manager') {
            abort(403);
        }

        $data = $request->validated();

        // Admin
        if ($userRole === 'admin') {
            if (empty($data['organization_id'])) {
                abort(422);
            }

            $organization = Organization::where(
                'id',
                $data['organization_id']
            )
                ->where('owner_id', $userId)
                ->firstOrFail();
        }

        // Manager
        else {
            $organization = $this->getManagerOrganization();

            $data['organization_id'] = $organization->id;
        }

        // Create project
        $project = Project::create($data);

        // Add organization members
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

    // Organization store
    public function organizationStore(
        StoreProjectRequest $request,
        Organization $organization
    ) {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if ($organization->owner_id != session('user_id')) {
            abort(403);
        }

        $data = $request->validated();

        $data['organization_id'] = $organization->id;

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

    // Show project
    public function show(Project $project)
    {
        $this->checkProjectAccess($project);

        return view(
            'projects.show',
            compact('project')
        );
    }

    // Edit project
    public function edit(Project $project)
    {
        $userRole = session('user_role');

        if ($userRole !== 'admin' && $userRole !== 'manager') {
            abort(403);
        }

        $organization = $this->checkProjectAccess($project);

        // Admin
        if ($userRole === 'admin') {
            $organizations = $this->getUserOrganizations();

            return view(
                'projects.edit',
                compact(
                    'project',
                    'organizations'
                )
            );
        }

        // Manager
        $managerOrganization = $this->getManagerOrganization();

        if ($project->organization_id != $managerOrganization->id) {
            abort(403);
        }

        return view(
            'projects.edit',
            compact(
                'project',
                'organization'
            )
        );
    }

    // Update project
    public function update(
        UpdateProjectRequest $request,
        Project $project
    ) {
        $userRole = session('user_role');
        $userId = session('user_id');

        if ($userRole !== 'admin' && $userRole !== 'manager') {
            abort(403);
        }

        $currentOrganization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess($currentOrganization);

        $data = $request->validated();

        // Admin
        if ($userRole === 'admin') {
            if (empty($data['organization_id'])) {
                abort(422);
            }

            $newOrganization = Organization::where(
                'id',
                $data['organization_id']
            )
                ->where('owner_id', $userId)
                ->firstOrFail();
        }

        // Manager
        else {
            $newOrganization = $this->getManagerOrganization();

            $data['organization_id'] = $newOrganization->id;
        }

        // Check organization change
        $organizationChanged =
            $project->organization_id != $newOrganization->id;

        // Update project
        $project->update($data);

        // Add new organization members
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

    // Delete project
    public function destroy(Project $project)
    {
        $userRole = session('user_role');

        if ($userRole !== 'admin' && $userRole !== 'manager') {
            abort(403);
        }

        $this->checkProjectAccess($project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with(
                'success',
                'Project deleted successfully.'
            );
    }
}