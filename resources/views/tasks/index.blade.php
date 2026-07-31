@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Task List</h2>
            <h5 class="text-primary">
                Project : {{ $project->title }}
            </h5>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('projects.tasks.trash', $project->id) }}"
               class="btn btn-danger">
                Deleted Tasks
            </a>

            <a href="{{ route('projects.tasks.create', $project->id) }}"
               class="btn btn-primary">
                Add Task
            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="success-alert">

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
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th width="220">Action</th>
                </tr>

            </thead>

            <tbody>

            @forelse($tasks as $task)

                <tr>

                    <td>{{ $task->id }}</td>

                    <td>{{ $task->title }}</td>

                    <td>{{ $task->description }}</td>

                    <td>{{ $task->due_date }}</td>

                    <td class="text-center">

                        @if($task->priority == 'Low')

                            <span class="badge bg-success">Low</span>

                        @elseif($task->priority == 'Medium')

                            <span class="badge bg-warning text-dark">Medium</span>

                        @else

                            <span class="badge bg-danger">High</span>

                        @endif

                    </td>

                    <td class="text-center">

                        @if($task->status == 'To_Do')

                            <span class="badge bg-secondary">To Do</span>

                        @elseif($task->status == 'In_Progress')

                            <span class="badge bg-primary">In Progress</span>

                        @else

                            <span class="badge bg-success">Completed</span>

                        @endif

                    </td>

                    <td>

                        <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('projects.tasks.show', [$project->id, $task->id]) }}"
                               class="btn btn-info btn-sm">
                                View
                            </a>

                            <a href="{{ route('projects.tasks.edit', [$project->id, $task->id]) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('projects.tasks.destroy', [$project->id, $task->id]) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this task?')">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center text-danger py-3">
                        No Tasks Found
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

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