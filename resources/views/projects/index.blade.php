@extends('layouts.app')


@section('content')


@php
    use App\Enums\ProjectStatus;

    $userRole = session('user_role');
@endphp


{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Project List
        </h2>

        @if(isset($organization))

            <h5 class="text-primary mb-0">
                Organization: {{ $organization->name }}
            </h5>

        @endif

    </div>


    {{-- Only Admin and Manager can create projects --}}
    @if(
        $userRole === 'admin' ||
        $userRole === 'manager'
    )

        <a
            href="{{ route('projects.create') }}"
            class="btn btn-primary">

            Add Project

        </a>

    @endif

</div>


{{-- Success Message --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show"
        id="success-alert"
        role="alert">

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Project Table --}}
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

                <th width="350">Action</th>

            </tr>

        </thead>


        <tbody>

            @forelse($projects as $project)

                <tr>

                    {{-- ID --}}
                    <td>
                        {{ $project->id }}
                    </td>


                    {{-- Organization --}}
                    <td>

                        @if($project->organization)

                            {{ $project->organization->name }}

                        @else

                            <span class="text-muted">
                                No Organization
                            </span>

                        @endif

                    </td>


                    {{-- Project Title --}}
                    <td>
                        {{ $project->title }}
                    </td>


                    {{-- Description --}}
                    <td>
                        {{ $project->description }}
                    </td>


                    {{-- Start Date --}}
                    <td>
                        {{ $project->start_date }}
                    </td>


                    {{-- End Date --}}
                    <td>
                        {{ $project->end_date }}
                    </td>


                    {{-- Status --}}
                    <td class="text-center">

                        @php

                            $status = $project->status instanceof ProjectStatus
                                ? $project->status->value
                                : $project->status;


                            $badge = match ($status) {

                                'pending' =>
                                    'warning text-dark',

                                'in_progress' =>
                                    'primary',

                                'completed' =>
                                    'success',

                                'cancelled' =>
                                    'danger',

                                default =>
                                    'secondary',

                            };

                        @endphp


                        <span class="badge bg-{{ $badge }}">

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $status
                                )
                            ) }}

                        </span>

                    </td>


                    {{-- Created At --}}
                    <td>
                        {{ $project->created_at }}
                    </td>


                    {{-- Updated At --}}
                    <td>
                        {{ $project->updated_at }}
                    </td>


                    {{-- Actions --}}
                    <td>

                        <div class="d-flex justify-content-center gap-2 flex-wrap">


                            {{-- View Tasks --}}
                            <a
                                href="{{ route(
                                    'projects.tasks.index',
                                    $project->id
                                ) }}"
                                class="btn btn-info btn-sm">

                                Tasks

                            </a>


                            {{-- Manage Users --}}
                            @if(
                                $userRole === 'admin' ||
                                $userRole === 'manager'
                            )

                                <a
                                    href="{{ route(
                                        'projects.users.index',
                                        $project->id
                                    ) }}"
                                    class="btn btn-primary btn-sm">

                                    Manage Users

                                </a>

                            @endif


                            {{-- Admin / Manager Actions --}}
                            @if(
                                $userRole === 'admin' ||
                                $userRole === 'manager'
                            )


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'projects.edit',
                                        $project->id
                                    ) }}"
                                    class="btn btn-warning btn-sm">

                                    Edit

                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'projects.destroy',
                                        $project->id
                                    ) }}"
                                    method="POST">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this project?'
                                        )">

                                        Delete

                                    </button>

                                </form>

                            @endif


                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="10"
                        class="text-center text-danger py-4">

                        @if($userRole === 'employee')

                            No projects have tasks assigned to you.

                        @else

                            No Projects Found.

                        @endif

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


{{-- Auto Hide Success Alert --}}
<script>

    setTimeout(function () {

        const alert = document.getElementById('success-alert');

        if (alert) {

            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);

            bsAlert.close();

        }

    }, 3000);

</script>


@endsection