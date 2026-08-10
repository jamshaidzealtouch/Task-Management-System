@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>{{ $organization->name }} - Users</h2>

        <p class="text-muted mb-0">
            Manage Managers and Employees
        </p>
    </div>

    <a href="{{ route('organizations.users.create', $organization) }}"
       class="btn btn-primary">

        + Add User

    </a>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="card shadow">

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Role</th>

                </tr>

            </thead>


            <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>
                            {{ $user->id }}
                        </td>

                        <td>
                            {{ $user->name }}
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>

                            <span class="badge bg-primary">

                                {{ ucfirst($user->role->value ?? $user->role) }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4"
                            class="text-center">

                            No users found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<a href="{{ route('organizations.show', $organization) }}"
   class="btn btn-secondary mt-3">

    Back to Organization

</a>

@endsection