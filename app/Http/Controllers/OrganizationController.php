<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::where(
            'owner_id',
            session('user_id')
        )->get();

        return view(
            'organizations.index',
            compact('organizations')
        );
    }
    public function create()
    {
        return view('organizations.create');
    }

    public function store(StoreOrganizationRequest $request)
    {
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