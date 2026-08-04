@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Deleted Tasks</h2>

            <h5 class="text-primary">
                Project : {{ $project->title }}
            </h5>

        </div>

        <a href="{{ route('projects.tasks.index', $project->id) }}"
           class="btn btn-secondary">

            Back to Tasks

        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    <table class="table table-bordered table-hover">

        <thead class="table-dark">

            <tr>

                <th>ID</th>

                <th>Title</th>

                <th>Due Date</th>

                <th>Priority</th>

                <th>Status</th>

                <th>Deleted At</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

        @forelse($tasks as $task)

            <tr>

                <td>{{ $task->id }}</td>

                <td>{{ $task->title }}</td>

                <td>{{ $task->due_date }}</td>

                <td>{{ $task->priority }}</td>

                <td>{{ $task->status }}</td>

                <td>{{ $task->deleted_at }}</td>

                <td>

                    <form action="{{ route('projects.tasks.restore', [$project->id, $task->id]) }}"
                          method="POST">

                        @csrf

                        @method('PATCH')

                        <button class="btn btn-success btn-sm">

                            Restore

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    class="text-center text-danger">

                    No Deleted Tasks Found

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection