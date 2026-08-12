@extends('layouts.app')

@php
    use App\Enums\ProjectStatus;
@endphp

@section('content')

<div class="card shadow">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <h3 class="mb-1">Project Details</h3>
            <h5 class="text-primary mb-0">
                {{ $project->title }}
            </h5>
        </div>

        {{-- Manage Users --}}
        @if(
            session('user_role') === 'admin' ||
            session('user_role') === 'manager'
        )
            <!-- <a
                href="{{ route('projects.users.index', $project) }}"
                class="btn btn-primary"
            >
                Manage Users
            </a> -->
        @endif

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="220">ID</th>
                <td>{{ $project->id }}</td>
            </tr>

            <tr>
                <th>Organization</th>
                <td>{{ $project->organization?->name }}</td>
            </tr>

            <tr>
                <th>Project Title</th>
                <td>{{ $project->title }}</td>
            </tr>

            <tr>
                <th>Description</th>
                <td>{{ $project->description }}</td>
            </tr>

            <tr>
                <th>Start Date</th>
                <td>{{ $project->start_date }}</td>
            </tr>

            <tr>
                <th>End Date</th>
                <td>{{ $project->end_date }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>

                    @php
                        $badge = match ($project->status) {
                            ProjectStatus::Pending => 'warning text-dark',
                            ProjectStatus::InProgress => 'primary',
                            ProjectStatus::Completed => 'success',
                            ProjectStatus::Cancelled => 'danger',
                        };
                    @endphp

                    <span class="badge bg-{{ $badge }}">
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $project->status->value
                            )
                        ) }}
                    </span>

                </td>
            </tr>

            <tr>
                <th>Created At</th>
                <td>{{ $project->created_at }}</td>
            </tr>

            <tr>
                <th>Updated At</th>
                <td>{{ $project->updated_at }}</td>
            </tr>

        </table>

        <div class="d-flex gap-2">

            <a
                href="{{ route('projects.index') }}"
                class="btn btn-secondary"
            >
                Back
            </a>

            <a
                href="{{ route('projects.tasks.index', $project) }}"
                class="btn btn-success"
            >
                Tasks
            </a>

        </div>

    </div>

</div>

@endsection