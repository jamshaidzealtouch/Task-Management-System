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
    public function create(Project $project)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        if ($userRole === 'admin') {
            if ($project->organization->owner_id != $userId) {
                abort(403);
            }
        }

        if ($userRole === 'manager') {
            $belongsToOrganization = $project->organization
                ->users()
                ->where('users.id', $userId)
                ->exists();

            if (!$belongsToOrganization) {
                abort(403);
            }
        }

        return view('invitations.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        if ($userRole === 'admin') {
            if ($project->organization->owner_id != $userId) {
                abort(403);
            }
        }

        if ($userRole === 'manager') {
            $belongsToOrganization = $project->organization
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
                'max:255',
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
            $alreadyMember = $project->users()
                ->where('users.id', $existingUser->id)
                ->exists();

            if ($alreadyMember) {
                return back()
                    ->withErrors([
                        'email' =>
                            'This user is already a member of this project.'
                    ])
                    ->withInput();
            }
        }

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
                        'An invitation has already been sent to this email.'
                ])
                ->withInput();
        }

        $token = bin2hex(
            random_bytes(32)
        );

        $invitation = Invitation::create([
            'project_id' => $project->id,
            'invited_by' => $userId,
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => $token,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

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

        $this->acceptInvitationForUser(
            $invitation,
            $user
        );

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Invitation accepted successfully. You can now login and access the project.'
            );
    }

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
                'min:8',
                'confirmed',
            ],
        ]);

        $email = $invitation->email;

        $existingUser = User::where(
            'email',
            $email
        )->first();

        if ($existingUser) {
            $this->acceptInvitationForUser(
                $invitation,
                $existingUser
            );

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Your account already exists and the invitation has been accepted. Please login.'
                );
        }

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

    private function acceptInvitationForUser(
        Invitation $invitation,
        User $user
    ) {
        $alreadyMember = $invitation->project
            ->users()
            ->where(
                'users.id',
                $user->id
            )
            ->exists();

        if (!$alreadyMember) {
            $invitation->project
                ->users()
                ->attach(
                    $user->id,
                    [
                        'role' => $invitation->role,
                    ]
                );
        }

        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }
}