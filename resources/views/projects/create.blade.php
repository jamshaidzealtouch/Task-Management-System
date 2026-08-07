@extends('layouts.app')

@section('content')

@php
    use App\Enums\ProjectStatus;
@endphp

<div class="card shadow">

    <div class="card-header">

        <h3>Add New Project</h3>

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

        <form action="{{ route('projects.store') }}" method="POST">

            @csrf

            {{-- Organization --}}
            <div class="mb-3">

                <label class="form-label">Organization</label>

                <select
                    name="organization_id"
                    class="form-select">

                    <option value="">
                        Select Organization
                    </option>

                    @foreach($organizations as $organization)

                        <option
                            value="{{ $organization->id }}"
                            {{ old('organization_id') == $organization->id ? 'selected' : '' }}>

                            {{ $organization->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Project Title --}}
            <div class="mb-3">

                <label class="form-label">Project Title</label>

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

            {{-- Start Date --}}
            <div class="mb-3">

                <label class="form-label">Start Date</label>

                <input
                    type="date"
                    name="start_date"
                    class="form-control"
                    value="{{ old('start_date') }}">

            </div>

            {{-- End Date --}}
            <div class="mb-3">

                <label class="form-label">End Date</label>

                <input
                    type="date"
                    name="end_date"
                    class="form-control"
                    value="{{ old('end_date') }}">

            </div>

            {{-- Status --}}
            <div class="mb-3">

                <label class="form-label">Status</label>

                <select
                    name="status"
                    class="form-select">

                    <option value="">
                        Select Status
                    </option>

                    @foreach(ProjectStatus::cases() as $status)

                        <option
                            value="{{ $status->value }}"
                            {{ old('status') == $status->value ? 'selected' : '' }}>

                            {{ ucwords(str_replace('_', ' ', $status->value)) }}

                        </option>

                    @endforeach

                </select>

            </div>

            <button
                type="submit"
                class="btn btn-success">

                Save Project

            </button>

            <a href="{{ route('projects.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </form>

    </div>

</div>

@endsection