@extends('layouts.app')

@section('content')

@php
    use App\Enums\ProjectStatus;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">Project List</h2>

    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Project
    </a>

</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         id="success-alert"
         role="alert">

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

    </div>
@endif

<div class="table-responsive">

    <table class="table table-bordered table-hover align-middle">

        <thead class="table-dark text-center">

            <tr>
                <th>ID</th>
                <th>Organization</th>
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

                    <td>
                        {{ $project->organization?->name }}
                    </td>

                    <td>{{ $project->title }}</td>

                    <td>{{ $project->description }}</td>

                    <td>{{ $project->start_date }}</td>

                    <td>{{ $project->end_date }}</td>

                    <td class="text-center">

                        @php
                            $badge = match ($project->status) {

                                ProjectStatus::Pending => 'warning text-dark',

                                ProjectStatus::InProgress => 'primary',

                                ProjectStatus::Completed => 'success',

                                ProjectStatus::Cancelled => 'danger',

                            };
                        @endphp

                        <span class="badge bg-{{ $badge }}">
                            {{ ucwords(str_replace('_', ' ', $project->status->value)) }}
                        </span>

                    </td>

                    <td>{{ $project->created_at }}</td>

                    <td>{{ $project->updated_at }}</td>

                    <td>

                        <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('projects.tasks.index', $project->id) }}"
                               class="btn btn-info btn-sm">
                                View
                            </a>

                            <a href="{{ route('projects.edit', $project->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

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

                    <td colspan="10"
                        class="text-center text-danger py-4">

                        No Projects Found

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- Auto Hide Success Alert --}}
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