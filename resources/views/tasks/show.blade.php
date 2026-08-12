@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3>Task Details</h3>

            <h5 class="text-primary">
                Project:
                {{ $project->title }}
            </h5>

        </div>


        <div class="card-body">

            <table class="table table-bordered">

                {{-- Title --}}
                <tr>

                    <th width="200">
                        Title
                    </th>

                    <td>
                        {{ $task->title }}
                    </td>

                </tr>


                {{-- Description --}}
                <tr>

                    <th>
                        Description
                    </th>

                    <td>
                        {{ $task->description }}
                    </td>

                </tr>


                {{-- Due Date --}}
                <tr>

                    <th>
                        Due Date
                    </th>

                    <td>
                        {{ $task->due_date }}
                    </td>

                </tr>


                {{-- Priority --}}
                <tr>

                    <th>
                        Priority
                    </th>

                    <td>

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

                </tr>


                {{-- Status --}}
                <tr>

                    <th>
                        Status
                    </th>

                    <td>

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

                </tr>


                {{-- Assigned Employee --}}
                <tr>

                    <th>
                        Assigned To
                    </th>

                    <td>

                        @if($task->assignee)

                            <span class="badge bg-info text-dark">
                                {{ $task->assignee->name }}
                            </span>

                            <small class="text-muted">
                                ({{ $task->assignee->email }})
                            </small>

                        @else

                            <span class="text-muted">
                                Not Assigned
                            </span>

                        @endif

                    </td>

                </tr>


                {{-- Created By --}}
                <tr>

                    <th>
                        Created By
                    </th>

                    <td>

                        @if($task->creator)

                            {{ $task->creator->name }}

                        @else

                            <span class="text-muted">
                                Unknown
                            </span>

                        @endif

                    </td>

                </tr>

            </table>


            <a
                href="{{ route('projects.tasks.index', $project->id) }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div>

    </div>

</div>

@endsection