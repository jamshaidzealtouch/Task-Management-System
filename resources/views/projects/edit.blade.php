@extends('layouts.app')

@section('content')

<h2>Edit Project</h2>

<form action="{{ route('projects.update', $project->id) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="mb-3">

        <label>Project Title</label>

        <input
            type="text"
            name="title"
            class="form-control"
            value="{{ $project->title }}">

    </div>

    <div class="mb-3">

        <label>Description</label>

        <textarea
            name="description"
            class="form-control">{{ $project->description }}</textarea>

    </div>

    <div class="mb-3">

        <label>Start Date</label>

        <input
            type="date"
            name="start_date"
            class="form-control"
            value="{{ $project->start_date }}">

    </div>

    <div class="mb-3">

        <label>End Date</label>

        <input
            type="date"
            name="end_date"
            class="form-control"
            value="{{ $project->end_date }}">

    </div>

    <div class="mb-3">

        <label>Status</label>

        <select name="status" class="form-control">

            <option value="Pending"
                {{ $project->status == 'Pending' ? 'selected' : '' }}>
                Pending
            </option>

            <option value="In Progress"
                {{ $project->status == 'In Progress' ? 'selected' : '' }}>
                In Progress
            </option>

            <option value="Completed"
                {{ $project->status == 'Completed' ? 'selected' : '' }}>
                Completed
            </option>

            <option value="Cancelled"
                {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                  Cancelled
</option>

        </select>

    </div>

    <button class="btn btn-success">
        Update Project
    </button>

</form>

@endsection