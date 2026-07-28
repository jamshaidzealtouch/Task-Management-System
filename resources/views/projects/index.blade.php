@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">Project List</h2>

        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Project
        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">

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
                    <th width="260">Action</th>
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

                        <td class="text-center">

                            @if($project->status == 'Pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @elseif($project->status == 'In Progress')

                                <span class="badge bg-primary">
                                    In Progress
                                </span>

                            @elseif($project->status == 'Completed')

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            @elseif($project->status == 'Cancelled')

                                <span class="badge bg-danger">
                                    Cancelled
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Unknown
                                </span>

                            @endif

                        </td>

                        <td>{{ $project->created_at }}</td>

                        <td>{{ $project->updated_at }}</td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                <!-- View -->
                                <a href="{{ route('projects.show', $project->id) }}"
                                   class="btn btn-info btn-sm">
                                    View
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('projects.edit', $project->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('projects.destroy', $project->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
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

                        <td colspan="9" class="text-center text-danger py-4">
                            No Projects Found
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- Auto Hide Success Alert After 5 Seconds --}}
<script>
    setTimeout(function () {

        let alert = document.getElementById('success-alert');

        if (alert) {

            alert.classList.remove('show');

            setTimeout(function () {
                alert.remove();
            }, 500);

        }

    }, 5000);
</script>

@endsection