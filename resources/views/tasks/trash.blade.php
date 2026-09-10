@extends('layouts.app')

@section('content')

<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Deleted Tasks</h2>

            <h5 class="text-primary">
                Project: {{ $project->title }}
            </h5>

        </div>

        <a
            href="{{ route('projects.tasks.index', $project->id) }}"
            class="btn btn-secondary"
        >
            Back to Tasks
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Deleted Tasks Table --}}
    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark text-center">

                <tr>

                    <th>ID</th>

                    <th>Title</th>

                    <th>Due Date</th>

                    <th>Priority</th>

                    <th>Status</th>

                    <th>Assigned To</th>

                    <th>Deleted At</th>

                    <th>Action</th>

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


                    {{-- Due Date --}}
                    <td class="text-center">

                        @if($task->due_date)

                            {{ $task->due_date }}

                        @else

                            <span class="text-muted">
                                N/A
                            </span>

                        @endif

                    </td>


                    {{-- Priority --}}
                    <td class="text-center">

                        @php

                            $priority = $task->priority instanceof \BackedEnum
                                ? $task->priority->value
                                : $task->priority;

                        @endphp


                        @if($priority === 'low')

                            <span class="badge bg-success">
                                Low
                            </span>

                        @elseif($priority === 'medium')

                            <span class="badge bg-warning text-dark">
                                Medium
                            </span>

                        @elseif($priority === 'high')

                            <span class="badge bg-danger">
                                High
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ ucfirst($priority) }}
                            </span>

                        @endif

                    </td>


                    {{-- Status --}}
                    <td class="text-center">

                        @php

                            $status = $task->status instanceof \BackedEnum
                                ? $task->status->value
                                : $task->status;

                        @endphp


                        @if($status === 'to_do')

                            <span class="badge bg-secondary">
                                To Do
                            </span>

                        @elseif($status === 'in_progress')

                            <span class="badge bg-primary">
                                In Progress
                            </span>

                        @elseif($status === 'completed')

                            <span class="badge bg-success">
                                Completed
                            </span>

                        @else

                            <span class="badge bg-dark">
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </span>

                        @endif

                    </td>


                    {{-- Assigned Employee --}}
                    <td class="text-center">

                        @if($task->assignee)

                            <span class="badge bg-info text-dark">
                                {{ $task->assignee->name }}
                            </span>

                        @else

                            <span class="text-muted">
                                Not Assigned
                            </span>

                        @endif

                    </td>


                    {{-- Deleted At --}}
                    <td class="text-center">

                        @if($task->deleted_at)

                            {{ $task->deleted_at }}

                        @else

                            <span class="text-muted">
                                N/A
                            </span>

                        @endif

                    </td>


                    {{-- Restore --}}
                    <td class="text-center">

                        <form
                            action="{{ route(
                                'projects.tasks.restore',
                                [$project->id, $task->id]
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-success btn-sm"
                                onclick="return confirm('Restore this task?')"
                            >
                                Restore
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center text-danger py-3"
                    >
                        No Deleted Tasks Found
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection