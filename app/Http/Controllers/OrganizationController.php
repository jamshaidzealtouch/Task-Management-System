<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use Illuminate\Support\Facades\Gate;

class OrganizationController extends Controller
{
    // All organizations show
    public function index()
    {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'viewAny',
            Organization::class
        );

        $userId = $user->id;
        $userRole = $user->role->value;

        if ($userRole === 'admin') {

            $organizations = Organization::where(
                'owner_id',
                $userId
            )->get();

        } else {

            $organizations = Organization::whereHas(
                'users',
                function ($query) use ($userId) {
                    $query->where(
                        'users.id',
                        $userId
                    );
                }
            )->get();
        }

        return view(
            'organizations.index',
            compact('organizations')
        );
    }

    // New organization create
    public function create()
    {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'create',
            Organization::class
        );

        return view(
            'organizations.create'
        );
    }

    // Save organization
    public function store(
        StoreOrganizationRequest $request
    ) {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'create',
            Organization::class
        );

        $data = $request->validated();

        $data['owner_id'] = $user->id;

        Organization::create($data);

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                'Organization created successfully.'
            );
    }

    // Show organization
    public function show(
        Organization $organization
    ) {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'view',
            $organization
        );

        return view(
            'organizations.show',
            compact('organization')
        );
    }

    // Edit organization
    public function edit(
        Organization $organization
    ) {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'update',
            $organization
        );

        return view(
            'organizations.edit',
            compact('organization')
        );
    }

    // Update organization
    public function update(
        UpdateOrganizationRequest $request,
        Organization $organization
    ) {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'update',
            $organization
        );

        $organization->update(
            $request->validated()
        );

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                'Organization updated successfully.'
            );
    }

    // Delete organization
    public function destroy(
        Organization $organization
    ) {
        $user = User::findOrFail(
            session('user_id')
        );

        Gate::forUser($user)->authorize(
            'delete',
            $organization
        );

        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                'Organization deleted successfully.'
            );
    }
}