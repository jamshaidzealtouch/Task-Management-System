@extends('layouts.app')

@section('content')

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


{{-- ========================================================= --}}
{{-- ADMIN DASHBOARD --}}
{{-- ========================================================= --}}

@if(session('user_role') === 'admin')

    <div class="card border-primary shadow mb-4">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">
                Admin Dashboard
            </h4>

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


    {{-- ===================================================== --}}
    {{-- ADMIN ORGANIZATION PROJECTS --}}
    {{-- ===================================================== --}}

    @if(isset($projects) && $projects->count() > 0)

        <div class="card shadow mb-4">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Organization Projects
                </h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($projects as $project)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $project->title }}
                                        </strong>
                                    </td>

                                    <td>

                                        {{ $project->description }}

                                    </td>

                                    <td>

                                        @php
                                            $status = $project->status;

                                            if ($status instanceof \BackedEnum) {
                                                $status = $status->value;
                                            }
                                        @endphp

                                        <span class="badge bg-primary">
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            href="{{ route('projects.show', $project->id) }}"
                                            class="btn btn-info btn-sm"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

@endif


{{-- ========================================================= --}}
{{-- MANAGER DASHBOARD --}}
{{-- ========================================================= --}}

@if(session('user_role') === 'manager')

    <div class="card border-success shadow mb-4">

        <div class="card-header bg-success text-white">

            <h4 class="mb-0">
                Manager Dashboard
            </h4>

        </div>

        <div class="card-body">

            <p>
                You can:
            </p>

            <ul>

                <li>Create Projects</li>
                <li>Assign Tasks</li>
                <li>Manage Employees</li>
                <li>Manage Organization Projects</li>

            </ul>


            {{-- Organization Information --}}

            @if(isset($organization) && $organization)

                <hr>

                <h5>
                    Organization
                </h5>

                <p class="mb-0">

                    <strong>
                        {{ $organization->name }}
                    </strong>

                </p>

            @endif

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- MANAGER ORGANIZATION PROJECTS --}}
    {{-- ===================================================== --}}

    @if(isset($projects) && $projects->count() > 0)

        <div class="card shadow mb-4">

            <div class="card-header bg-success text-white">

                <h4 class="mb-0">
                    Organization Projects
                </h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($projects as $project)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $project->title }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $project->description }}
                                    </td>

                                    <td>

                                        @php
                                            $status = $project->status;

                                            if ($status instanceof \BackedEnum) {
                                                $status = $status->value;
                                            }
                                        @endphp

                                        <span class="badge bg-primary">
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            href="{{ route('projects.show', $project->id) }}"
                                            class="btn btn-info btn-sm"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

@endif


{{-- ========================================================= --}}
{{-- EMPLOYEE DASHBOARD --}}
{{-- ========================================================= --}}

@if(session('user_role') === 'employee')

    {{-- Employee Information --}}

    <div class="card border-warning shadow mb-4">

        <div class="card-header bg-warning text-dark">

            <h4 class="mb-0">
                Employee Dashboard
            </h4>

        </div>

        <div class="card-body">

            <p>
                You can:
            </p>

            <ul>

                <li>View Assigned Tasks</li>
                <li>Update Task Status</li>

            </ul>


            {{-- ============================================= --}}
            {{-- Organization Information --}}
            {{-- ============================================= --}}

            @if(isset($organization) && $organization)

                <hr>

                <h5 class="mb-3">
                    Organization Information
                </h5>

                <div class="row">

                    <div class="col-md-6">

                        <p class="mb-1">
                            <strong>Organization:</strong>
                        </p>

                        <p>
                            {{ $organization->name }}
                        </p>

                    </div>


                    @if(isset($admin) && $admin)

                        <div class="col-md-6">

                            <p class="mb-1">
                                <strong>Admin:</strong>
                            </p>

                            <p>

                                {{ $admin->name }}

                                @if($admin->email)

                                    <br>

                                    <small class="text-muted">
                                        {{ $admin->email }}
                                    </small>

                                @endif

                            </p>

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- EMPLOYEE ORGANIZATION PROJECTS --}}
    {{-- ===================================================== --}}

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">
                Organization Projects
            </h4>

        </div>

        <div class="card-body">

            @if(isset($projects) && $projects->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($projects as $project)

                                <tr>

                                    <td>

                                        <strong>
                                            {{ $project->title }}
                                        </strong>

                                    </td>

                                    <td>

                                        {{ $project->description }}

                                    </td>

                                    <td>

                                        @php

                                            $status = $project->status;

                                            if ($status instanceof \BackedEnum) {
                                                $status = $status->value;
                                            }

                                        @endphp

                                        <span class="badge bg-primary">

                                            {{ ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            ) }}

                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'projects.show',
                                                $project->id
                                            ) }}"
                                            class="btn btn-info btn-sm"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">

                    <strong>
                        No projects found.
                    </strong>

                    <br>

                    There are currently no projects in your organization.

                </div>

            @endif

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- MY ASSIGNED TASKS --}}
    {{-- ===================================================== --}}

    <div class="card shadow mb-4">

        <div class="card-header bg-dark text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    My Assigned Tasks
                </h4>

                <a
                    href="{{ route('my.tasks') }}"
                    class="btn btn-light btn-sm"
                >
                    View All Tasks
                </a>

            </div>

        </div>


        <div class="card-body">

            @if(isset($tasks) && $tasks->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Task
                                </th>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Organization
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($tasks as $task)

                                <tr>

                                    {{-- Task --}}

                                    <td>

                                        <strong>
                                            {{ $task->title }}
                                        </strong>

                                        @if($task->description)

                                            <br>

                                            <small class="text-muted">

                                                {{ $task->description }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Project --}}

                                    <td>

                                        @if($task->project)

                                            {{ $task->project->title }}

                                        @else

                                            <span class="text-muted">
                                                No Project
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Organization --}}

                                    <td>

                                        @if(
                                            $task->project &&
                                            $task->project->organization
                                        )

                                            {{ $task->project->organization->name }}

                                        @else

                                            <span class="text-muted">
                                                No Organization
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Due Date --}}

                                    <td>

                                        @if($task->due_date)

                                            {{ \Carbon\Carbon::parse(
                                                $task->due_date
                                            )->format('Y-m-d') }}

                                        @else

                                            N/A

                                        @endif

                                    </td>


                                    {{-- Priority --}}

                                    <td>

                                        @php

                                            $priority = $task->priority;

                                            if ($priority instanceof \BackedEnum) {
                                                $priority = $priority->value;
                                            }

                                        @endphp


                                        <span class="badge bg-warning text-dark">

                                            {{ ucfirst($priority) }}

                                        </span>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @php

                                            $status = $task->status;

                                            if ($status instanceof \BackedEnum) {
                                                $status = $status->value;
                                            }

                                        @endphp


                                        <span class="badge bg-primary">

                                            {{ ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Action --}}

                                    <td>

                                        @if($task->project)

                                            <a
                                                href="{{ route(
                                                    'projects.tasks.show',
                                                    [
                                                        $task->project->id,
                                                        $task->id
                                                    ]
                                                ) }}"
                                                class="btn btn-info btn-sm"
                                            >
                                                View
                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">

                    <strong>
                        No tasks assigned.
                    </strong>

                    <br>

                    You currently don't have any tasks assigned to you.

                </div>

            @endif

        </div>

    </div>

@endif

@endsection