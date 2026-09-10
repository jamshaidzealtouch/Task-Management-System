@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>Projects</h2>

        <h5 class="text-primary">
            Organization: {{ $organization->name }}
        </h5>

    </div>

    <a href="{{ route('organizations.projects.create', $organization->id) }}"
       class="btn btn-primary">

        Add Project

    </a>

</div>


@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif


<div class="table-responsive">

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

            <tr>

                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

        </thead>


        <tbody>

        @forelse($projects as $project)

            <tr>

                <td>{{ $project->id }}</td>

                <td>{{ $project->title }}</td>

                <td>{{ $project->description }}</td>

                <td>{{ $project->start_date }}</td>

                <td>{{ $project->end_date }}</td>

                <td>{{ $project->status->value ?? $project->status }}</td>

                <td>

                    <a href="{{ route('projects.show', $project->id) }}"
                       class="btn btn-info btn-sm">

                        View

                    </a>

                    <a href="{{ route('projects.edit', $project->id) }}"
                       class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <a href="{{ route('projects.tasks.index', $project->id) }}"
                       class="btn btn-success btn-sm">

                        Tasks

                    </a>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    class="text-center text-danger">

                    No Projects Found

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection