<?php

namespace App\Services;

use App\Models\Organization;

class OrganizationService
{
    public function getAll()
    {
        return Organization::all();
    }

    public function create(array $data, $user)
    {
        return Organization::create([
            'name' => $data['name'],
            'owner_id' => $user->id,
        ]);
    }

    public function update(Organization $organization, array $data)
    {
        $organization->update([
            'name' => $data['name'],
        ]);

        return $organization;
    }

    public function delete(Organization $organization)
    {
        return $organization->delete();
    }
}