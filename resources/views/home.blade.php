@extends('layouts.app')

@section('content')

<div class="container mt-5">

    {{-- Dashboard Header --}}
    <div class="mb-4">

        <h1>Dashboard</h1>

        <hr>

        <h4>
            Welcome, {{ session('user_name') }}
        </h4>

        <p>
            Role:
            <strong>
                {{ ucfirst(session('user_role')) }}
            </strong>
        </p>

    </div>


    {{-- Admin Dashboard --}}
    @if(session('user_role') === 'admin')

        <div class="card border-primary shadow mb-4">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Admin Dashboard</h4>
            </div>

            <div class="card-body">

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

        </div>

    @endif


    {{-- Manager Dashboard --}}
    @if(session('user_role') === 'manager')

        <div class="card border-success shadow mb-4">

            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Manager Dashboard</h4>
            </div>

            <div class="card-body">

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

        </div>

    @endif


    {{-- Employee Dashboard --}}
    @if(session('user_role') === 'employee')

        <div class="card border-warning shadow mb-4">

            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Employee Dashboard</h4>
            </div>

            <div class="card-body">

                <p>
                    You can:
                </p>

                <ul>
                    <li>View Assigned Tasks</li>
                    <li>Update Task Status</li>
                </ul>

            </div>

        </div>

    @endif

</div>

@endsection