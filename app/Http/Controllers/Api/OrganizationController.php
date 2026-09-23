<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\OrganizationService;

class OrganizationController extends Controller
{
    protected OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function index()
    {
        $organizations = $this->organizationService->getAll();

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

    public function store(StoreOrganizationRequest $request)
    {
        $organization = $this->organizationService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Organization created successfully.',
            'organization' => new OrganizationResource($organization),
        ], 201);
    }

    public function update(
        UpdateOrganizationRequest $request,
        Organization $organization
    ) {
        $organization = $this->organizationService->update(
            $organization,
            $request->validated()
        );

        return response()->json([
            'message' => 'Organization updated successfully.',
            'organization' => new OrganizationResource($organization),
        ], 200);
    }

    public function destroy(Organization $organization)
    {
        $this->organizationService->delete($organization);

        return response()->json([
            'message' => 'Organization deleted successfully.',
        ], 200);
    }
}