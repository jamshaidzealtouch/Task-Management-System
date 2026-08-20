@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h4 class="mb-0">Invite User</h4>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <strong>Project:</strong>
                {{ $project->title }}
            </div>

            <div class="mb-3">
                <strong>Organization:</strong>
                {{ $project->organization->name }}
            </div>

            <form method="POST"
                  action="{{ route('projects.invitations.store', $project->id) }}">

                @csrf

                {{-- Email --}}
                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter user's email"
                           value="{{ old('email') }}"
                           required>

                    @error('email')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Role --}}
                <div class="mb-3">

                    <label class="form-label">
                        Role
                    </label>

                    <select name="role"
                            class="form-select"
                            required>

                        <option value="">
                            Select Role
                        </option>

                        @if(session('user_role') === 'admin')

                            <option value="manager">
                                Manager
                            </option>

                            <option value="employee">
                                Employee
                            </option>

                        @elseif(session('user_role') === 'manager')

                            <option value="employee">
                                Employee
                            </option>

                        @endif

                    </select>

                    @error('role')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Buttons --}}
                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary">
                        Send Invitation
                    </button>

                    <a href="{{ route('projects.users.index', $project->id) }}"
                       class="btn btn-secondary">
                        Back
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection