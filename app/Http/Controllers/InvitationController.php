<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;

class InvitationController extends Controller
{
    // Create invitation form
    public function create(Project $project)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(403);
            }

        } elseif ($userRole === 'manager') {

            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', $userId)
                ->exists();

            if (!$belongsToOrganization) {
                abort(403);
            }
        }

        return view(
            'invitations.create',
            compact('project')
        );
    }

    // Store invitation
    public function store(Request $request, Project $project)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $organization = $project->organization;

        if (!$organization) {
            abort(404);
        }

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(403);
            }

        } elseif ($userRole === 'manager') {

            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', $userId)
                ->exists();

            if (!$belongsToOrganization) {
                abort(403);
            }
        }

        $data = $request->validate([
            'email' => [
                'required',
                'email',
                'max:200',
            ],
            'role' => [
                'required',
                'in:manager,employee',
            ],
        ]);

        if (
            $userRole === 'manager' &&
            $data['role'] !== 'employee'
        ) {
            abort(403);
        }

        $existingUser = User::where(
            'email',
            $data['email']
        )->first();

        if ($existingUser) {

            if (
                $existingUser->organization_id ==
                $organization->id
            ) {
                return back()
                    ->withErrors([
                        'email' =>
                            'This user is already a member of this organization.'
                    ])
                    ->withInput();
            }

            if ($existingUser->organization_id !== null) {
                return back()
                    ->withErrors([
                        'email' =>
                            'This user already belongs to another organization.'
                    ])
                    ->withInput();
            }
        }

        // Check existing pending invitation
        $existingInvitation = Invitation::where(
            'project_id',
            $project->id
        )
            ->where('email', $data['email'])
            ->where('status', 'pending')
            ->exists();

        if ($existingInvitation) {
            return back()
                ->withErrors([
                    'email' =>
                        'An invitation has already been sent to this user for this project.'
                ])
                ->withInput();
        }

        // Generate invitation token
        $token = bin2hex(
            random_bytes(32)
        );

        // Create invitation
        $invitation = Invitation::create([
            'project_id' => $project->id,
            'invited_by' => $userId,
            'user_id' => $existingUser?->id,
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => $token,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        // Send invitation email
        Mail::to($data['email'])
            ->send(
                new InvitationMail($invitation)
            );

        return redirect()
            ->route(
                'projects.users.index',
                $project->id
            )
            ->with(
                'success',
                'Invitation sent successfully.'
            );
    }

    // Accept invitation
    public function accept($token)
    {
        $invitation = Invitation::where(
            'token',
            $token
        )->first();

        if (!$invitation) {
            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation is invalid or no longer available.'
                ]
            );
        }

        if ($invitation->status !== 'pending') {
            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation has already been accepted or is no longer available.'
                ]
            );
        }

        if (
            $invitation->expires_at &&
            now()->greaterThan(
                $invitation->expires_at
            )
        ) {
            $invitation->update([
                'status' => 'expired',
            ]);

            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation has expired.'
                ]
            );
        }

        $user = User::where(
            'email',
            $invitation->email
        )->first();

        if (!$user) {
            return redirect()->route(
                'invitations.register',
                $invitation->token
            );
        }

        DB::transaction(function () use (
            $invitation,
            $user
        ) {
            $this->acceptInvitationForUser(
                $invitation,
                $user
            );
        });

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Invitation accepted successfully. You can now login and access all organization projects.'
            );
    }

    // Show registration form
    public function showRegister($token)
    {
        $invitation = Invitation::where(
            'token',
            $token
        )->first();

        if (!$invitation) {
            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation is invalid or no longer available.'
                ]
            );
        }

        if ($invitation->status !== 'pending') {
            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation has already been accepted or is no longer available.'
                ]
            );
        }

        if (
            $invitation->expires_at &&
            now()->greaterThan(
                $invitation->expires_at
            )
        ) {
            $invitation->update([
                'status' => 'expired',
            ]);

            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation has expired.'
                ]
            );
        }

        $existingUser = User::where(
            'email',
            $invitation->email
        )->first();

        if ($existingUser) {
            return redirect()->route(
                'invitations.accept',
                $invitation->token
            );
        }

        return view(
            'invitations.register',
            compact('invitation')
        );
    }

    // Complete invitation registration
    public function completeRegistration(
        Request $request,
        $token
    ) {
        $invitation = Invitation::where(
            'token',
            $token
        )->first();

        if (!$invitation) {
            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation is invalid or no longer available.'
                ]
            );
        }

        if ($invitation->status !== 'pending') {
            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation has already been accepted or is no longer available.'
                ]
            );
        }

        if (
            $invitation->expires_at &&
            now()->greaterThan(
                $invitation->expires_at
            )
        ) {
            $invitation->update([
                'status' => 'expired',
            ]);

            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation has expired.'
                ]
            );
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:4',
                'confirmed',
            ],
        ]);

        $email = $invitation->email;

        $existingUser = User::where(
            'email',
            $email
        )->first();

        if ($existingUser) {
            DB::transaction(function () use (
                $invitation,
                $existingUser
            ) {
                $this->acceptInvitationForUser(
                    $invitation,
                    $existingUser
                );
            });

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Your account already exists and the invitation has been accepted. Please login.'
                );
        }
        // Create new user
        DB::transaction(function () use (
            $data,
            $email,
            $invitation
        ) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make(
                    $data['password']
                ),
                'role' => $invitation->role,
            ]);

            $this->acceptInvitationForUser(
                $invitation,
                $user
            );
        });

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Account created and invitation accepted successfully. Please login.'
            );
    }
    // Process accepted invitation for user
    private function acceptInvitationForUser(
        Invitation $invitation,
        User $user
    ) {
        $organization = $invitation
            ->project
            ->organization;

        if (!$organization) {
            throw new \Exception(
                'Organization not found for this invitation.'
            );
        }

        // Save first accepted project
        if (!$user->primary_project_id) {
            $user->primary_project_id = $invitation->project_id;
        }

        // Set user's organization
        $user->organization_id = $organization->id;

        $user->save();

        // Get organization owner
        $ownerId = $organization->owner_id;

        // Get all organizations owned by the owner
        $organizations = \App\Models\Organization::where(
            'owner_id',
            $ownerId
        )->get();

        // Add user to all organization projects
        foreach ($organizations as $org) {

            $projects = Project::where(
                'organization_id',
                $org->id
            )->get();

            foreach ($projects as $project) {

                $alreadyAssigned = $project
                    ->users()
                    ->where(
                        'users.id',
                        $user->id
                    )
                    ->exists();

                if (!$alreadyAssigned) {

                    $project->users()->attach(
                        $user->id,
                        [
                            'role' => $invitation->role,
                        ]
                    );
                }
            }
        }

        // Mark invitation as accepted
        $invitation->update([
            'user_id' => $user->id,
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }
}