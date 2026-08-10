@extends('layouts.app')

@section('content')

<div class="card shadow">

    <div class="card-header">
        <h3>Organization Details</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="200">ID</th>
                <td>{{ $organization->id }}</td>
            </tr>

            <tr>
                <th>Organization Name</th>
                <td>{{ $organization->name }}</td>
            </tr>

            <tr>
                <th>Description</th>
                <td>{{ $organization->description }}</td>
            </tr>

            <tr>
                <th>Owner ID</th>
                <td>{{ $organization->owner_id }}</td>
            </tr>

            <tr>
                <th>Created At</th>
                <td>{{ $organization->created_at }}</td>
            </tr>

            <tr>
                <th>Updated At</th>
                <td>{{ $organization->updated_at }}</td>
            </tr>

        </table>


        {{-- Actions --}}

        <a href="{{ route('organizations.users.index', $organization) }}"
           class="btn btn-primary">

            Manage Users

        </a>


        <a href="{{ route('organizations.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </div>

</div>

@endsection