@extends('layouts.app')

@section('content')

<div class="card shadow">

    <div class="card-header">

        <h3>
            Add User to {{ $organization->name }}
        </h3>

    </div>


    <div class="card-body">

        {{-- Validation Errors --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('organizations.users.store', $organization) }}"
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
                    required>

            </div>


            {{-- Email --}}

            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
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
                    required>

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
                        Select Role
                    </option>

                    <option value="manager"
                        {{ old('role') == 'manager' ? 'selected' : '' }}>

                        Manager

                    </option>

                    <option value="employee"
                        {{ old('role') == 'employee' ? 'selected' : '' }}>

                        Employee

                    </option>

                </select>

            </div>


            {{-- Organization --}}

            <div class="mb-3">

                <label class="form-label">
                    Organization
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $organization->name }}"
                    disabled>

                <small class="text-muted">

                    User will automatically be assigned
                    to this organization.

                </small>

            </div>


            <button
                type="submit"
                class="btn btn-success">

                Create User

            </button>


            <a
                href="{{ route('organizations.users.index', $organization) }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection