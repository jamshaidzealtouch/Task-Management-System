@extends('layouts.app')

@section('content')

@php
    use App\Enums\TaskStatus;
    use App\Enums\TaskPriority;

    $userRole = session('user_role');
@endphp


<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>Task List</h2>

        <h5 class="text-primary">
            Project : {{ $project->title }}
        </h5>

    </div>


    <div class="d-flex gap-2">

        {{-- Deleted Tasks --}}
        @if($userRole === 'admin' || $userRole === 'manager')

            <a
                href="{{ route('projects.tasks.trash', $project->id) }}"
                class="btn btn-danger"
            >
                Deleted Tasks
            </a>

        @endif


        {{-- Add Task --}}
        @if($userRole === 'admin' || $userRole === 'manager')

            <a
                href="{{ route('projects.tasks.create', $project->id) }}"
                class="btn btn-primary"
            >
                Add Task
            </a>

        @endif

    </div>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show"
        id="success-alert"
    >

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


<div class="table-responsive">

    <table class="table table-bordered table-hover align-middle">

        <thead class="table-dark text-center">

            <tr>

                <th>ID</th>

                <th>Title</th>

                <th>Description</th>

                <th>Due Date</th>

                <th>Priority</th>

                <th>Status</th>

                <th>Assigned To</th>

                <th width="220">Action</th>

            </tr>

        </thead>


        <tbody>

        @forelse($tasks as $task)

            <tr>

                {{-- ID --}}
                <td class="text-center">
                    {{ $task->id }}
                </td>


                {{-- Title --}}
                <td>
                    {{ $task->title }}
                </td>


                {{-- Description --}}
                <td>
                    {{ $task->description }}
                </td>


                {{-- Due Date --}}
                <td class="text-center">
                    {{ $task->due_date }}
                </td>


                {{-- Priority --}}
                <td class="text-center">

                    @php

                        $priorityBadge = match ($task->priority) {

                            TaskPriority::Low =>
                                'success',

                            TaskPriority::Medium =>
                                'warning text-dark',

                            TaskPriority::High =>
                                'danger',

                            default =>
                                'secondary',

                        };

                    @endphp


                    <span class="badge bg-{{ $priorityBadge }}">

                        {{ ucwords($task->priority->value) }}

                    </span>

                </td>


                {{-- Status --}}
                <td class="text-center">

                    @php

                        $statusBadge = match ($task->status) {

                            TaskStatus::ToDo =>
                                'secondary',

                            TaskStatus::InProgress =>
                                'primary',

                            TaskStatus::Completed =>
                                'success',

                            default =>
                                'dark',

                        };

                    @endphp


                    <span class="badge bg-{{ $statusBadge }}">

                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $task->status->value
                            )
                        ) }}

                    </span>

                </td>


                {{-- Assigned Employee --}}
                <td class="text-center">

                    @if($task->assignedUser)

                        <span class="badge bg-info text-dark">

                            {{ $task->assignedUser->name }}

                        </span>

                    @else

                        <span class="text-muted">
                            Not Assigned
                        </span>

                    @endif

                </td>


                {{-- Actions --}}
                <td>

                    <div
                        class="d-flex justify-content-center gap-2"
                    >

                        {{-- View --}}
                        <a
                            href="{{ route(
                                'projects.tasks.show',
                                [$project->id, $task->id]
                            ) }}"
                            class="btn btn-info btn-sm"
                        >
                            View
                        </a>


                        {{-- Admin / Manager --}}
                        @if(
                            $userRole === 'admin' ||
                            $userRole === 'manager'
                        )

                            {{-- Edit --}}
                            <a
                                href="{{ route(
                                    'projects.tasks.edit',
                                    [$project->id, $task->id]
                                ) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>


                            {{-- Delete --}}
                            <form
                                action="{{ route(
                                    'projects.tasks.destroy',
                                    [$project->id, $task->id]
                                ) }}"
                                method="POST"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        'Delete this task?'
                                    )"
                                >
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
                    colspan="8"
                    class="text-center text-danger py-3"
                >
                    No Tasks Found
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection