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

        <a
            href="{{ route('projects.users.create', $project->id) }}"
            class="btn btn-primary"
        >
            Add User
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

                            <th width="150">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

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


                                {{-- Remove --}}
                                <td class="text-center">

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

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-danger py-4"
                                >

                                    No Users Found

                                </td>

                            </tr>

                        @endforelse

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