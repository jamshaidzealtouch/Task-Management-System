@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Project List</h2>

        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            Add Project
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark text-center">

                <tr>
                    <th>ID</th>
                    <th>Project Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th style="width:260px;">Action</th>
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

                        <td>

                            @if($project->status == 'Pending')
                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @elseif($project->status == 'In Progress')
                                <span class="badge bg-primary">
                                    In Progress
                                </span>

                            @else
                                <span class="badge bg-success">
                                    Completed
                                </span>
                            @endif

                        </td>

                        <td>{{ $project->created_at }}</td>

                        <td>{{ $project->updated_at }}</td>

                        <td>

                            <div class="d-flex justify-content-center">

                                <!-- View -->
                                <a href="{{ route('projects.show', $project->id) }}"
                                   class="btn btn-info btn-sm me-2">
                                    View
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('projects.edit', $project->id) }}"
                                   class="btn btn-warning btn-sm me-2">
                                    Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('projects.destroy', $project->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this project?')">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center text-danger">

                            No Projects Found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection