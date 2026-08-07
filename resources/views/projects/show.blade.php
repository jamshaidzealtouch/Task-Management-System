@extends('layouts.app')

@php
    use App\Enums\ProjectStatus;
@endphp

@section('content')

<div class="card shadow">

    <div class="card-header">

        <h3>Project Details</h3>

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
                        {{ ucwords(str_replace('_', ' ', $project->status->value)) }}
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

        <a href="{{ route('projects.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </div>

</div>

@endsection