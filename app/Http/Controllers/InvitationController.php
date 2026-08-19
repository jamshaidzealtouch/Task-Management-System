<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;

class InvitationController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if ($userRole === 'admin') {

            $organizations = Organization::where(
                'owner_id',
                $userId
            )->get();

        } else {

            $organizations = Organization::whereHas(
                'users',
                function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                }
            )->get();
        }

        $invitations = Invitation::whereIn(
            'organization_id',
            $organizations->pluck('id')
        )
        ->latest()
        ->get();

        return view(
            'invitations.index',
            compact(
                'organizations',
                'invitations'
            )
        );
    }


    public function create()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        if ($userRole === 'admin') {

            $organizations = Organization::where(
                'owner_id',
                $userId
            )->get();

        } else {

            $organizations = Organization::whereHas(
                'users',
                function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                }
            )->get();
        }

        return view(
            'invitations.create',
            compact('organizations')
        );
    }


    public function store(Request $request)
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        if (!in_array($userRole, ['admin', 'manager'])) {
            abort(403);
        }

        $data = $request->validate([
            'organization_id' => [
                'required',
                'exists:organizations,id',
            ],

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

        $organization = Organization::findOrFail(
            $data['organization_id']
        );

        if ($userRole === 'admin') {

            if ($organization->owner_id != $userId) {
                abort(404);
            }

        } else {

            $belongsToOrganization = $organization
                ->users()
                ->where('users.id', $userId)
                ->exists();

            if (!$belongsToOrganization) {
                abort(404);
            }

            if ($data['role'] !== 'employee') {
                abort(403);
            }
        }

        $existingInvitation = Invitation::where(
            'organization_id',
            $organization->id
        )
        ->where(
            'email',
            $data['email']
        )
        ->where(
            'status',
            'pending'
        )
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
            'organization_id' => $organization->id,
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
            ->route('invitations.index')
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
        )->firstOrFail();

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
            now()->greaterThan($invitation->expires_at)
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

        return redirect()->route(
            'invitations.register',
            $invitation->token
        );
    }


    public function showRegister($token)
    {
        $invitation = Invitation::where(
            'token',
            $token
        )->firstOrFail();

        if ($invitation->status !== 'pending') {

            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation is no longer available.'
                ]
            );
        }

        if (
            $invitation->expires_at &&
            now()->greaterThan($invitation->expires_at)
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

        return view(
            'invitations.register',
            compact('invitation')
        );
    }


    public function register(
        Request $request,
        $token
    ) {
        $invitation = Invitation::where(
            'token',
            $token
        )->firstOrFail();

        if ($invitation->status !== 'pending') {

            return view(
                'invitations.invalid',
                [
                    'message' =>
                        'This invitation is no longer available.'
                ]
            );
        }

        if (
            $invitation->expires_at &&
            now()->greaterThan($invitation->expires_at)
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

        $existingUser = User::where(
            'email',
            $invitation->email
        )->first();

        if ($existingUser) {

            return back()
                ->withErrors([
                    'email' =>
                        'An account with this email already exists.'
                ])
                ->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $invitation->email,
            'password' => Hash::make($data['password']),
            'role' => $invitation->role,
            'organization_id' => $invitation->organization_id,
        ]);

        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Account created successfully. You can now login.'
            );
    }
    public function completeRegistration(Request $request, $token)
{
    $invitation = Invitation::where(
        'token',
        $token
    )->firstOrFail();

    // Check invitation status
    if ($invitation->status !== 'pending') {
        return view(
            'invitations.invalid',
            [
                'message' => 'This invitation is no longer available.'
            ]
        );
    }

    // Check expiry
    if (
        $invitation->expires_at &&
        now()->greaterThan($invitation->expires_at)
    ) {
        $invitation->update([
            'status' => 'expired',
        ]);

        return view(
            'invitations.invalid',
            [
                'message' => 'This invitation has expired.'
            ]
        );
    }

    // Validate registration data
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

    // Check if email already exists
    if (User::where('email', $invitation->email)->exists()) {
        return back()
            ->withErrors([
                'email' => 'An account with this email already exists.'
            ]);
    }

    // Create user
    $user = User::create([
        'name' => $data['name'],
        'email' => $invitation->email,
        'password' => Hash::make($data['password']),
        'role' => $invitation->role,
        'organization_id' => $invitation->organization_id,
    ]);

    // Mark invitation as accepted
    $invitation->update([
        'status' => 'accepted',
        'accepted_at' => now(),
    ]);

    return redirect()
        ->route('login')
        ->with(
            'success',
            'Account created successfully. You can now login.'
        );
}
}