@extends('layouts.app')

@section('content')

<div class="container mt-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Manage Users</h2>

            <h5 class="text-primary mb-1">
                Project: {{ $project->title }}
            </h5>

            <span class="text-muted">
                Organization: {{ $organization->name }}
            </span>
        </div>

        <a href="{{ route('projects.invitations.create', $project->id) }}"
           class="btn btn-primary">
            Invite User
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            id="success-alert"
        >

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- Project Users --}}
    <div class="card shadow">

        <div class="card-header">

            <h4 class="mb-0">
                Project Users
            </h4>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Role</th>

                            <th>Organization</th>

                            <th width="200">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- ========================= --}}
                        {{-- ACCEPTED / PROJECT USERS --}}
                        {{-- ========================= --}}

                        @forelse($users as $user)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $user->id }}
                                </td>


                                {{-- Name --}}
                                <td>
                                    {{ $user->name }}
                                </td>


                                {{-- Email --}}
                                <td>
                                    {{ $user->email }}
                                </td>


                                {{-- Role --}}
                                <td class="text-center">

                                    @php

                                        $role = $user->role instanceof \App\Enums\UserRole
                                            ? $user->role->value
                                            : $user->role;

                                        $badge = match($role) {

                                            'admin' => 'danger',

                                            'manager' => 'warning text-dark',

                                            'employee' => 'primary',

                                            default => 'secondary',

                                        };

                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($role) }}
                                    </span>

                                </td>


                                {{-- Organization --}}
                                <td>
                                    {{ $organization->name }}
                                </td>


                                {{-- Action --}}
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-1">

                                        {{-- Accepted --}}
                                        <span class="badge bg-success align-self-center">
                                            Accepted
                                        </span>


                                        {{-- Remove --}}
                                        <form
                                            action="{{ route(
                                                'projects.users.destroy',
                                                [
                                                    'project' => $project->id,
                                                    'user' => $user->id
                                                ]
                                            ) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Remove this user from the project?')"
                                            >
                                                Remove
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- No accepted users --}}
                        @endforelse


                        {{-- ========================= --}}
                        {{-- PENDING INVITATIONS --}}
                        {{-- ========================= --}}

                        @foreach($invitations ?? [] as $invitation)

                            @if($invitation->status === 'pending')

                                <tr class="table-warning">

                                    {{-- ID --}}
                                    <td>
                                        -
                                    </td>


                                    {{-- Name --}}
                                    <td>

                                        <span class="text-muted">
                                            Not registered
                                        </span>

                                    </td>


                                    {{-- Email --}}
                                    <td>

                                        {{ $invitation->email }}

                                    </td>


                                    {{-- Role --}}
                                    <td class="text-center">

                                        @if($invitation->role === 'manager')

                                            <span class="badge bg-warning text-dark">
                                                Manager
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                Employee
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Organization --}}
                                    <td>

                                        {{ $organization->name }}

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2">

                                            {{-- Pending --}}
                                            <span class="badge bg-warning text-dark align-self-center">
                                                Pending
                                            </span>


                                            {{-- Cancel Invitation --}}
                                            @if(Route::has('projects.invitations.destroy'))

                                                <form
                                                    action="{{ route(
                                                        'projects.invitations.destroy',
                                                        [
                                                            'project' => $project->id,
                                                            'invitation' => $invitation->id
                                                        ]
                                                    ) }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Cancel this invitation?')"
                                                    >
                                                        Cancel
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endif

                        @endforeach


                        {{-- ========================= --}}
                        {{-- NOTHING FOUND --}}
                        {{-- ========================= --}}

                        @if($users->isEmpty() && ($invitations ?? collect())->where('status', 'pending')->isEmpty())

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-danger py-4"
                                >

                                    No Users or Pending Invitations Found

                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>


            {{-- Back Button --}}
            <a
                href="{{ route('projects.index') }}"
                class="btn btn-secondary"
            >
                Back to Projects
            </a>

        </div>

    </div>

</div>


{{-- Auto Hide Success Alert --}}
<script>

    setTimeout(function () {

        const alert =
            document.getElementById('success-alert');

        if (alert) {

            const bsAlert =
                bootstrap.Alert.getOrCreateInstance(alert);

            bsAlert.close();

        }

    }, 3000);

</script>

@endsection