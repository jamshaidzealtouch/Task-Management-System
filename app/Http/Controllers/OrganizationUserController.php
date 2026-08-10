<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizationUserController extends Controller
{
    /**
     * Show users belonging to an organization.
     */
    public function index(Organization $organization)
    {
        // Only organization owner/admin can manage users
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        $users = $organization->users()->get();

        return view(
            'organizations.users.index',
            compact('organization', 'users')
        );
    }


    /**
     * Show Add User form.
     */
    public function create(Organization $organization)
    {
        // Only organization owner/admin can add users
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        return view(
            'organizations.users.create',
            compact('organization')
        );
    }


    /**
     * Store Manager / Employee.
     */
    public function store(
        Request $request,
        Organization $organization
    ) {
        // Only organization owner/admin can add users
        if ($organization->owner_id != session('user_id')) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|string|min:6|confirmed',

            'role' => 'required|in:manager,employee',
        ]);


        User::create([
            'name' => $data['name'],

            'email' => $data['email'],

            'password' => Hash::make($data['password']),

            'role' => UserRole::from($data['role']),

            // Automatically assign current organization
            'organization_id' => $organization->id,
        ]);


        return redirect()
            ->route(
                'organizations.users.index',
                $organization
            )
            ->with(
                'success',
                'User added successfully.'
            );
    }
}