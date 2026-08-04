@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3>Task Details</h3>

            <h5 class="text-primary">

                Project :
                {{ $project->title }}

            </h5>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>

                    <th width="200">Title</th>

                    <td>{{ $task->title }}</td>

                </tr>

                <tr>

                    <th>Description</th>

                    <td>{{ $task->description }}</td>

                </tr>

                <tr>

                    <th>Due Date</th>

                    <td>{{ $task->due_date }}</td>

                </tr>

                <tr>

                    <th>Priority</th>

                    <td>

                        @if($task->priority == 'Low')

                            <span class="badge bg-success">

                                Low

                            </span>

                        @elseif($task->priority == 'Medium')

                            <span class="badge bg-warning text-dark">

                                Medium

                            </span>

                        @else

                            <span class="badge bg-danger">

                                High

                            </span>

                        @endif

                    </td>

                </tr>

                <tr>

                    <th>Status</th>

                    <td>

                        @if($task->status == 'To_Do')

                            <span class="badge bg-secondary">

                                To Do

                            </span>

                        @elseif($task->status == 'In_Progress')

                            <span class="badge bg-primary">

                                In Progress

                            </span>

                        @else

                            <span class="badge bg-success">

                                Completed

                            </span>

                        @endif

                    </td>

                </tr>

            </table>

            <a href="{{ route('projects.tasks.index',$project->id) }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>

</div>

@endsection