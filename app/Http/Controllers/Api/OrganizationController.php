<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Http\Resources\OrganizationResource;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();

        return response()->json([
            'message' => 'Organizations fetched successfully.',
            'organizations' => OrganizationResource::collection($organizations),
        ], 200);
    }

    public function show(Organization $organization)
{
    return response()->json([
        'message' => 'Organization fetched successfully.',
     'organization' => new OrganizationResource($organization),
    ], 200);
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $user = $request->user();
    
        $organization = Organization::create([
            'name' => $request->name,
            'owner_id' => $user->id,
        ]);
    
        return response()->json([
            'message' => 'Organization created successfully.',
            'organization' => new OrganizationResource($organization),
        ], 201);
    }

    public function update(Request $request, Organization $organization)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $organization->update([
        'name' => $request->name,
    ]);

    return response()->json([
        'message' => 'Organization updated successfully.',
        'organization' => new OrganizationResource($organization),
    ], 200);
}
public function destroy(Organization $organization)
{
    $organization->delete();

    return response()->json([
        'message' => 'Organization deleted successfully.',
    ], 200);
}
}