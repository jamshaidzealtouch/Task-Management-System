@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3>Edit Task</h3>

        <h5 class="text-primary mb-0">
            Project: {{ $project->title }}
        </h5>
    </div>


    <div class="card-body">

        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Success Message --}}
        @if (session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        <form
            action="{{ route('projects.tasks.update', [$project->id, $task->id]) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            {{-- ========================================= --}}
            {{-- Task Title --}}
            {{-- ========================================= --}}

            <div class="mb-3">

                <label
                    for="title"
                    class="form-label"
                >
                    Task Title
                </label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    class="form-control"
                    placeholder="Enter task title"
                    value="{{ old('title', $task->title) }}"
                    required
                >

                @error('title')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ========================================= --}}
            {{-- Description --}}
            {{-- ========================================= --}}

            <div class="mb-3">

                <label
                    for="description"
                    class="form-label"
                >
                    Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    class="form-control"
                    rows="4"
                    placeholder="Enter task description"
                >{{ old('description', $task->description) }}</textarea>

                @error('description')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ========================================= --}}
            {{-- Due Date --}}
            {{-- ========================================= --}}

            <div class="mb-3">

                <label
                    for="due_date"
                    class="form-label"
                >
                    Due Date
                </label>

                @php
                    $dueDate = $task->due_date;

                    if ($dueDate instanceof \Carbon\CarbonInterface) {
                        $dueDate = $dueDate->format('Y-m-d');
                    } elseif (!empty($dueDate)) {
                        $dueDate = \Carbon\Carbon::parse($dueDate)->format('Y-m-d');
                    } else {
                        $dueDate = '';
                    }
                @endphp

                <input
                    type="date"
                    name="due_date"
                    id="due_date"
                    class="form-control"
                    value="{{ old('due_date', $dueDate) }}"
                >

                @error('due_date')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ========================================= --}}
            {{-- Priority --}}
            {{-- ========================================= --}}

            <div class="mb-3">

                <label
                    for="priority"
                    class="form-label"
                >
                    Priority
                </label>

                @php
                    $currentPriority = $task->priority instanceof \BackedEnum
                        ? $task->priority->value
                        : $task->priority;
                @endphp

                <select
                    name="priority"
                    id="priority"
                    class="form-select"
                    required
                >

                    @foreach(App\Enums\TaskPriority::cases() as $priority)

                        <option
                            value="{{ $priority->value }}"
                            {{ old('priority', $currentPriority) == $priority->value ? 'selected' : '' }}
                        >
                            {{ ucfirst(str_replace('_', ' ', $priority->value)) }}
                        </option>

                    @endforeach

                </select>

                @error('priority')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ========================================= --}}
            {{-- Status --}}
            {{-- ========================================= --}}

            <div class="mb-3">

                <label
                    for="status"
                    class="form-label"
                >
                    Status
                </label>

                @php
                    $currentStatus = $task->status instanceof \BackedEnum
                        ? $task->status->value
                        : $task->status;
                @endphp

                <select
                    name="status"
                    id="status"
                    class="form-select"
                    required
                >

                    @foreach(App\Enums\TaskStatus::cases() as $status)

                        <option
                            value="{{ $status->value }}"
                            {{ old('status', $currentStatus) == $status->value ? 'selected' : '' }}
                        >
                            {{ ucwords(str_replace('_', ' ', $status->value)) }}
                        </option>

                    @endforeach

                </select>

                @error('status')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ========================================= --}}
            {{-- Assign Employee --}}
            {{-- ========================================= --}}

            <div class="mb-3">

                <label
                    for="assigned_to"
                    class="form-label"
                >
                    Assign Employee
                </label>

                <select
                    name="assigned_to"
                    id="assigned_to"
                    class="form-select"
                >

                    <option value="">
                        Select Employee
                    </option>

                    @foreach($employees as $employee)

                        <option
                            value="{{ $employee->id }}"
                            {{ old('assigned_to', $task->assigned_to) == $employee->id ? 'selected' : '' }}
                        >
                            {{ $employee->name }}
                            ({{ $employee->email }})
                        </option>

                    @endforeach

                </select>

                @if($employees->isEmpty())

                    <small class="text-danger">
                        No employees are available in this organization.
                    </small>

                @endif

                @error('assigned_to')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ========================================= --}}
            {{-- Buttons --}}
            {{-- ========================================= --}}

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Task
            </button>


            <a
                href="{{ route('projects.tasks.index', $project->id) }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </form>

    </div>

</div>

@endsection