@extends('layouts.app')

@section('content')

<div class="container mt-4">

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

        <a
            href="{{ route('projects.invitations.create', $project->id) }}"
            class="btn btn-primary"
        >
            Invite User
        </a>

    </div>


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


    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    <div class="card shadow">

        <div class="card-header">

            <h4 class="mb-0">
                Project Users
            </h4>

        </div>


        <div class="card-body">

            <p class="text-muted">
                Users who have accepted an invitation in this
                organization are automatically available in all
                projects of this organization.
            </p>


            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Organization</th>
                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- ========================= --}}
                        {{-- ACCEPTED ORGANIZATION USERS --}}
                        {{-- ========================= --}}

                        @forelse($users as $user)

                            <tr>

                                <td>
                                    {{ $user->id }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $user->name }}
                                    </strong>

                                </td>


                                <td>
                                    {{ $user->email }}
                                </td>


                                <td class="text-center">

                                    @php

                                        $role = $user->role instanceof \App\Enums\UserRole
                                            ? $user->role->value
                                            : $user->role;

                                        $badge = match($role) {

                                            'admin' =>
                                                'danger',

                                            'manager' =>
                                                'warning text-dark',

                                            'employee' =>
                                                'primary',

                                            default =>
                                                'secondary',

                                        };

                                    @endphp


                                    <span class="badge bg-{{ $badge }}">

                                        {{ ucfirst($role) }}

                                    </span>

                                </td>


                                <td>

                                    {{ $organization->name }}

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-success">

                                        Available

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-danger py-4"
                                >

                                    No accepted users found.

                                </td>

                            </tr>

                        @endforelse


                        {{-- ========================= --}}
                        {{-- PENDING INVITATIONS --}}
                        {{-- ========================= --}}

                        @foreach($invitations ?? [] as $invitation)

                            @if($invitation->status === 'pending')

                                <tr class="table-warning">

                                    <td>
                                        -
                                    </td>


                                    <td>

                                        <span class="text-muted">
                                            Not registered
                                        </span>

                                    </td>


                                    <td>

                                        {{ $invitation->email }}

                                    </td>


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


                                    <td>

                                        {{ $organization->name }}

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-warning text-dark">

                                            Invitation Pending

                                        </span>

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>


            <a
                href="{{ route('projects.index') }}"
                class="btn btn-secondary"
            >

                Back to Projects

            </a>

        </div>

    </div>

</div>


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