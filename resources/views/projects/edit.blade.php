@extends('layouts.app')

@section('content')

@php
    use App\Enums\ProjectStatus;

    // Project status can come from database as string or Enum
    $currentStatus = $project->status instanceof ProjectStatus
        ? $project->status->value
        : $project->status;
@endphp

<div class="card-header">
    <h3>Edit Project</h3>
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


    <form
        action="{{ route('projects.update', $project->id) }}"
        method="POST">

        @csrf
        @method('PUT')


        {{-- ========================================= --}}
        {{-- Organization --}}
        {{-- ========================================= --}}

        <div class="mb-3">

            <label
                for="organization_id"
                class="form-label">

                Organization

            </label>


            {{-- ADMIN --}}
            @if(session('user_role') === 'admin')

                <select
                    name="organization_id"
                    id="organization_id"
                    class="form-select"
                    required>

                    <option value="">
                        Select Organization
                    </option>

                    @foreach($organizations as $organization)

                        <option
                            value="{{ $organization->id }}"
                            {{ old(
                                'organization_id',
                                $project->organization_id
                            ) == $organization->id
                                ? 'selected'
                                : '' }}>

                            {{ $organization->name }}

                        </option>

                    @endforeach

                </select>


            {{-- MANAGER --}}
            @elseif(session('user_role') === 'manager')

                <input
                    type="text"
                    class="form-control"
                    value="{{ $organization->name }}"
                    readonly>

                <input
                    type="hidden"
                    name="organization_id"
                    value="{{ $organization->id }}">

            @endif


            @error('organization_id')

                <div class="text-danger mt-1">
                    {{ $message }}
                </div>

            @enderror

        </div>


        {{-- ========================================= --}}
        {{-- Project Title --}}
        {{-- ========================================= --}}

        <div class="mb-3">

            <label
                for="title"
                class="form-label">

                Project Title

            </label>

            <input
                type="text"
                name="title"
                id="title"
                class="form-control"
                placeholder="Enter project title"
                value="{{ old('title', $project->title) }}"
                required>

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
                class="form-label">

                Description

            </label>

            <textarea
                name="description"
                id="description"
                class="form-control"
                rows="4"
                placeholder="Enter project description">{{ old('description', $project->description) }}</textarea>

            @error('description')

                <div class="text-danger mt-1">
                    {{ $message }}
                </div>

            @enderror

        </div>


        {{-- ========================================= --}}
        {{-- Start Date --}}
        {{-- ========================================= --}}

        <div class="mb-3">

            <label
                for="start_date"
                class="form-label">

                Start Date

            </label>

            <input
                type="date"
                name="start_date"
                id="start_date"
                class="form-control"
                value="{{ old(
                    'start_date',
                    $project->start_date
                        ? date('Y-m-d', strtotime($project->start_date))
                        : ''
                ) }}"
                required>

            @error('start_date')

                <div class="text-danger mt-1">
                    {{ $message }}
                </div>

            @enderror

        </div>


        {{-- ========================================= --}}
        {{-- End Date --}}
        {{-- ========================================= --}}

        <div class="mb-3">

            <label
                for="end_date"
                class="form-label">

                End Date

            </label>

            <input
                type="date"
                name="end_date"
                id="end_date"
                class="form-control"
                value="{{ old(
                    'end_date',
                    $project->end_date
                        ? date('Y-m-d', strtotime($project->end_date))
                        : ''
                ) }}"
                required>

            @error('end_date')

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
                class="form-label">

                Status

            </label>

            <select
                name="status"
                id="status"
                class="form-select"
                required>

                <option value="">
                    Select Status
                </option>

                @foreach(ProjectStatus::cases() as $status)

                    <option
                        value="{{ $status->value }}"
                        {{ old('status', $currentStatus) == $status->value
                            ? 'selected'
                            : '' }}>

                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $status->value
                            )
                        ) }}

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
        {{-- Buttons --}}
        {{-- ========================================= --}}

        <button
            type="submit"
            class="btn btn-primary">

            Update Project

        </button>


        <a
            href="{{ route('projects.index') }}"
            class="btn btn-secondary">

            Back

        </a>

    </form>

</div>

@endsection