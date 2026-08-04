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

                {{-- Task Title --}}
                <div class="mb-3">

                    <label class="form-label">Task Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}">

                </div>

                {{-- Description --}}
                <div class="mb-3">

                    <label class="form-label">Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4">{{ old('description') }}</textarea>

                </div>

                {{-- Due Date --}}
                <div class="mb-3">

                    <label class="form-label">Due Date</label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control"
                        value="{{ old('due_date') }}">

                </div>

                {{-- Priority --}}
                <div class="mb-3">

                    <label class="form-label">Priority</label>

                    <select
                        name="priority"
                        class="form-select">

                        <option value="">Select Priority</option>

                        @foreach(App\Enums\TaskPriority::cases() as $priority)

                            <option
                                value="{{ $priority->value }}"
                                {{ old('priority') == $priority->value ? 'selected' : '' }}>

                                {{ ucfirst($priority->value) }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Status --}}
                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="">Select Status</option>

                        @foreach(App\Enums\TaskStatus::cases() as $status)

                            <option
                                value="{{ $status->value }}"
                                {{ old('status') == $status->value ? 'selected' : '' }}>

                                {{ ucwords(str_replace('_', ' ', $status->value)) }}

                            </option>

                        @endforeach

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