<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    // all data show
    public function index()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {

            // Admin sees organizations owned by him
            $organizations = Organization::where(
                'owner_id',
                $userId
            )->get();

        } elseif ($userRole === 'manager') {

            // Manager sees organizations where he is assigned
            $organizations = Organization::whereHas(
                'users',
                function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                }
            )->get();

        } else {

            abort(403);
        }

        return view(
            'organizations.index',
            compact('organizations')
        );
    }

    // new organization create
    public function create()
    {
        // Only admin can create organization
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        return view('organizations.create');
    }

    // data save in database
    public function store(StoreOrganizationRequest $request)
    {
        // Only admin can create organization
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        $data = $request->validated();

        // Logged-in user becomes the owner/admin
        $data['owner_id'] = session('user_id');

        Organization::create($data);

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                'Organization created successfully.'
            );
    }

    // show data ui
    public function show(Organization $organization)
    {
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        return view(
            'organizations.show',
            compact('organization')
        );
    }

    // edit data organization
    public function edit(Organization $organization)
    {
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        return view(
            'organizations.edit',
            compact('organization')
        );
    }

    public function update(
        UpdateOrganizationRequest $request,
        Organization $organization
    ) {
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

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

    // delete data
    public function destroy(Organization $organization)
    {
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                'Organization deleted successfully.'
            );
    }
}