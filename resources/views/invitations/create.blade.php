@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h4 class="mb-0">Invite User</h4>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('invitations.store') }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Organization
                    </label>

                    <select name="organization_id"
                            class="form-select"
                            required>

                        <option value="">
                            Select Organization
                        </option>

                        @foreach($organizations as $organization)

                            <option value="{{ $organization->id }}">
                                {{ $organization->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('organization_id')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


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

                        <option value="manager">
                            Manager
                        </option>

                        <option value="employee">
                            Employee
                        </option>

                    </select>

                    @error('role')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary">
                        Send Invitation
                    </button>

                    <a href="{{ route('invitations.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection