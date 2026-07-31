@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3>Edit Task</h3>

            <h5 class="text-primary">

                Project : {{ $project->title }}

            </h5>

        </div>

        <div class="card-body">

            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('projects.tasks.update', [$project->id, $task->id]) }}"
                  method="POST">

                @csrf
                @method('PUT')

               
                <div class="mb-3">

                    <label class="form-label">Task Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title', $task->title) }}">

                </div>

                
                <div class="mb-3">

                    <label class="form-label">Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4">{{ old('description', $task->description) }}</textarea>

                </div>

               
                <div class="mb-3">

                    <label class="form-label">Due Date</label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control"
                        value="{{ old('due_date', $task->due_date) }}">

                </div>

               
                <div class="mb-3">

                    <label class="form-label">Priority</label>

                    <select name="priority" class="form-select">

                        <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>

                        <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>

                        <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>

                    </select>

                </div>

                
                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="To_Do" {{ $task->status == 'To_Do' ? 'selected' : '' }}>To Do</option>

                        <option value="In_Progress" {{ $task->status == 'In_Progress' ? 'selected' : '' }}>In Progress</option>

                        <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>

                    </select>

                </div>

                <button class="btn btn-primary">

                    Update Task

                </button>

                <a href="{{ route('projects.tasks.index', $project->id) }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection