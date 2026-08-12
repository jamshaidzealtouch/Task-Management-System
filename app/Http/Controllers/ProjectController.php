<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Organization;

class ProjectController extends Controller
{
    //Current logged-in user ki organizations find karna.
    private function getUserOrganizations()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {
            return Organization::where('owner_id', $userId)->get();
        }

        return Organization::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    private function getUserOrganization()
    {
        return $this->getUserOrganizations()->firstOrFail();
    }

    //Kya current user is organization ko access kar sakta hai?
    private function checkOrganizationAccess(
        Organization $organization
    ) {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(404);
            }

            return;
        }

        $belongsToOrganization = $organization
            ->users()
            ->where('users.id', $userId)
            ->exists();

        if (!$belongsToOrganization) {
            abort(404);
        }
    }
//all project show 
    public function index()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'employee') {

            $organization = $this->getUserOrganization();

            $projects = Project::where(
                'organization_id',
                $organization->id
            )
            ->whereHas('tasks', function ($query) use ($userId) {

                $query->where(
                    'assignee_id',
                    $userId
                );

            })
            ->get();
        }
        elseif ($userRole === 'admin') {

            $organizations = $this->getUserOrganizations();

            $organizationIds = $organizations->pluck('id');

            $projects = Project::whereIn(
                'organization_id',
                $organizationIds
            )->get();
        }
        elseif ($userRole === 'manager') {

            $organization = $this->getUserOrganization();

            $projects = Project::where(
                'organization_id',
                $organization->id
            )->get();
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

        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        $projects = Project::where(
            'organization_id',
            $organization->id
        )->get();

        return view(
            'projects.index',
            compact(
                'projects',
                'organization'
            )
        );
    }
// new record
    public function create()
    {
        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        if ($userRole === 'admin') {

            $organizations = $this->getUserOrganizations();

            return view(
                'projects.create',
                compact('organizations')
            );
        }

        $organization = $this->getUserOrganization();

        return view(
            'projects.create',
            compact('organization')
        );
    }

    public function organizationCreate(
        Organization $organization
    ) {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        return view(
            'projects.create',
            compact('organization')
        );
    }
//save record
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

            if (empty($data['organization_id'])) {
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

            $organization = $this->getUserOrganization();

            $data['organization_id'] = $organization->id;
        }

        $data['user_id'] = $userId;

        Project::create($data);

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

        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        $data = $request->validated();

        $data['organization_id'] = $organization->id;

        $data['user_id'] = session('user_id');

        Project::create($data);

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

    public function show(Project $project)
    {
        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess(
            $organization
        );

        if (session('user_role') === 'employee') {

            $hasAssignedTask = $project->tasks()
                ->where(
                    'assignee_id',
                    session('user_id')
                )
                ->exists();

            if (!$hasAssignedTask) {
                abort(403);
            }
        }

        return view(
            'projects.show',
            compact('project')
        );
    }

    public function edit(Project $project)
    {
        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess(
            $organization
        );

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

        return view(
            'projects.edit',
            compact(
                'project',
                'organization'
            )
        );
    }
//update 
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

        $currentOrganization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess(
            $currentOrganization
        );

        $data = $request->validated();

        if ($userRole === 'admin') {

            if (empty($data['organization_id'])) {
                abort(422);
            }

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

            $organization = $this->getUserOrganization();

            $data['organization_id'] = $organization->id;
        }

        $project->update($data);

        return redirect()
            ->route('projects.index')
            ->with(
                'success',
                'Project updated successfully.'
            );
    }
//delete
    public function destroy(Project $project)
    {
        $userRole = session('user_role');

        if (
            $userRole !== 'admin' &&
            $userRole !== 'manager'
        ) {
            abort(403);
        }

        $organization = Organization::findOrFail(
            $project->organization_id
        );

        $this->checkOrganizationAccess(
            $organization
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