@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">My Tasks</h1>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- PENDING TASK ASSIGNMENTS --}}
    {{-- ========================================================= --}}

    @if($pendingTasks->count() > 0)

        <div class="card mb-4">

            <div class="card-header bg-warning">

                <h4 class="mb-0">
                    New Task Assignments
                </h4>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Project</th>

                                <th>Task Title</th>

                                <th>Description</th>

                                <th>Priority</th>

                                <th>Due Date</th>

                                <th>Assigned By</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($pendingTasks as $task)

                                <tr>

                                    <td>
                                        {{ $task->id }}
                                    </td>


                                    <td>
                                        {{ $task->project->title }}
                                    </td>


                                    <td>
                                        {{ $task->title }}
                                    </td>


                                    <td>
                                        {{ $task->description }}
                                    </td>


                                    <td>
                                        {{ ucfirst($task->priority->value ?? $task->priority) }}
                                    </td>


                                    <td>
                                        {{ $task->due_date }}
                                    </td>


                                    <td>
                                        {{ $task->creator->name ?? 'N/A' }}
                                    </td>


                                    <td>

                                        <div class="d-flex gap-2">

                                            {{-- Accept Task --}}
                                            <form
                                                action="{{ route('tasks.accept', $task->id) }}"
                                                method="POST"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm"
                                                >
                                                    Accept
                                                </button>

                                            </form>


                                            {{-- Reject Task --}}
                                            <form
                                                action="{{ route('tasks.reject', $task->id) }}"
                                                method="POST"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    Reject
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MY ACCEPTED TASKS --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <h4 class="mb-0">
                My Tasks
            </h4>

        </div>


        <div class="card-body">

            @if($tasks->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Project</th>

                                <th>Task Title</th>

                                <th>Description</th>

                                <th>Priority</th>

                                <th>Due Date</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($tasks as $task)

                                <tr>

                                    <td>
                                        {{ $task->id }}
                                    </td>


                                    <td>
                                        {{ $task->project->title }}
                                    </td>


                                    <td>
                                        {{ $task->title }}
                                    </td>


                                    <td>
                                        {{ $task->description }}
                                    </td>


                                    <td>
                                        {{ ucfirst($task->priority->value ?? $task->priority) }}
                                    </td>


                                    <td>
                                        {{ $task->due_date }}
                                    </td>


                                    <td>
                                        {{ ucfirst(str_replace('_', ' ', $task->status->value ?? $task->status)) }}
                                    </td>


                                    <td>

                                        <form
                                            action="{{ route('my.tasks.status', $task->id) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            <select
                                                name="status"
                                                class="form-select mb-2"
                                            >

                                                <option
                                                    value="to_do"
                                                    {{ ($task->status->value ?? $task->status) === 'to_do' ? 'selected' : '' }}
                                                >
                                                    To Do
                                                </option>


                                                <option
                                                    value="in_progress"
                                                    {{ ($task->status->value ?? $task->status) === 'in_progress' ? 'selected' : '' }}
                                                >
                                                    In Progress
                                                </option>


                                                <option
                                                    value="completed"
                                                    {{ ($task->status->value ?? $task->status) === 'completed' ? 'selected' : '' }}
                                                >
                                                    Completed
                                                </option>

                                            </select>


                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-sm"
                                            >
                                                Update Status
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">

                    No accepted tasks found.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection