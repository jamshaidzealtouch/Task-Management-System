@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3>Add Task</h3>

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


            <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">

                @csrf

               
                <div class="mb-3">

                    <label class="form-label">Task Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}">

                </div>

            
                <div class="mb-3">

                    <label class="form-label">Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4">{{ old('description') }}</textarea>

                </div>

             
                <div class="mb-3">

                    <label class="form-label">Due Date</label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control"
                        value="{{ old('due_date') }}">

                </div>

                
                <div class="mb-3">

                    <label class="form-label">Priority</label>

                    <select
                        name="priority"
                        class="form-select">

                        <option value="">Select Priority</option>

                        <option value="Low">Low</option>

                        <option value="Medium">Medium</option>

                        <option value="High">High</option>

                    </select>

                </div>

            
                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="">Select Status</option>

                        <option value="To_Do">to_do</option>

                        <option value="In_Progress">In_Progress</option>

                        <option value="Completed">Completed</option>

                    </select>

                </div>

                <button class="btn btn-success">

                    Save Task

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