@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h1>Dashboard</h1>

    <hr>

    <h4>
        Welcome,
        {{ session('user_name') }}
    </h4>

    <p>
        Role:
        <strong>{{ ucfirst(session('user_role')) }}</strong>
    </p>

    {{-- Admin Dashboard --}}
    @if(session('user_role') == 'admin')

        <div class="alert alert-primary">

            <h4>Admin Dashboard</h4>

            <p>
                You can manage:
            </p>

            <ul>
                <li>Organizations</li>
                <li>Users</li>
                <li>Projects</li>
                <li>Tasks</li>
            </ul>

        </div>

    @endif


    {{-- Manager Dashboard --}}
    @if(session('user_role') == 'manager')

        <div class="alert alert-success">

            <h4>Manager Dashboard</h4>

            <p>
                You can:
            </p>

            <ul>
                <li>Create Organizations</li>
                <li>Create Projects</li>
                <li>Assign Tasks</li>
                <li>Manage Employees</li>
            </ul>

        </div>

    @endif


    {{-- Employee Dashboard --}}
    @if(session('user_role') == 'employee')

        <div class="alert alert-warning">

            <h4>Employee Dashboard</h4>

            <p>
                You can:
            </p>

            <ul>
                <li>View Assigned Tasks</li>
                <li>Update Task Status</li>
            </ul>

        </div>

    @endif

</div>

@endsection