@extends('layouts.app')

@section('content')

<div class="card shadow">

    <div class="card-header">

        <h2 class="mb-0">
            Add User to Project
        </h2>

    </div>


    <div class="card-body">

        {{-- Project Information --}}
        <div class="alert alert-info">

            <div>
                <strong>Project:</strong>
                {{ $project->title }}
            </div>

            <div>
                <strong>Organization:</strong>
                {{ $organization->name }}
            </div>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('projects.users.store', $project->id) }}"
            method="POST">

            @csrf


            {{-- Name --}}
            <div class="mb-3">

                <label class="form-label">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Enter user name"
                    required>

            </div>


            {{-- Email --}}
            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Enter user email"
                    required>

            </div>


            {{-- Password --}}
            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    required>

                <small class="text-muted">
                    Password must be at least 8 characters.
                </small>

            </div>


            {{-- Confirm Password --}}
            <div class="mb-3">

                <label class="form-label">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirm password"
                    required>

            </div>


            {{-- Role --}}
            <div class="mb-3">

                <label class="form-label">
                    Role
                </label>

                <select
                    name="role"
                    class="form-select"
                    required>

                    <option value="">
                        -- Select Role --
                    </option>


                    {{-- Admin can create Manager --}}
                    @if(session('user_role') === 'admin')

                        <option
                            value="manager"
                            {{ old('role') === 'manager' ? 'selected' : '' }}>

                            Manager

                        </option>

                    @endif


                    {{-- Admin and Manager can create Employee --}}
                    <option
                        value="employee"
                        {{ old('role') === 'employee' ? 'selected' : '' }}>

                        Employee

                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <button
                type="submit"
                class="btn btn-success">

                Add User

            </button>


            <a
                href="{{ route('projects.users.index', $project->id) }}"
                class="btn btn-secondary">

                Back

            </a>

        </form>

    </div>

</div>

@endsection