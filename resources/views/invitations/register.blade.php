@extends('layouts.guest')

@section('content')

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow border-0">

                <div class="card-body p-5">

                    {{-- Heading --}}
                    <div class="text-center mb-4">

                        <h2 class="fw-bold mb-2">
                            Create Your Account
                        </h2>

                        <p class="text-muted mb-0">
                            You have been invited to join
                            <strong>
                                {{ $invitation->organization->name }}
                            </strong>
                        </p>

                    </div>


                    {{-- Invitation Information --}}
                    <div class="bg-light rounded p-3 mb-4">

                        <div class="mb-2">

                            <small class="text-muted d-block">
                                Invited Email
                            </small>

                            <strong>
                                {{ $invitation->email }}
                            </strong>

                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Your Role
                            </small>

                            <span class="badge bg-primary">
                                {{ ucfirst($invitation->role) }}
                            </span>

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


                    {{-- Registration Form --}}
                    <form
                        action="{{ route(
                            'invitations.register.complete',
                            $invitation->token
                        ) }}"
                        method="POST"
                    >

                        @csrf


                        {{-- Name --}}
                        <div class="mb-3">

                            <label
                                for="name"
                                class="form-label fw-semibold"
                            >
                                Name
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter your name"
                                autocomplete="name"
                                required
                                autofocus
                            >

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label fw-semibold"
                            >
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                value="{{ $invitation->email }}"
                                readonly
                            >

                            <small class="text-muted">
                                This email address was used for the invitation
                                and cannot be changed.
                            </small>

                        </div>


                        {{-- Role --}}
                        <div class="mb-3">

                            <label
                                for="role"
                                class="form-label fw-semibold"
                            >
                                Role
                            </label>

                            <input
                                type="text"
                                id="role"
                                class="form-control"
                                value="{{ ucfirst($invitation->role) }}"
                                readonly
                            >

                            <small class="text-muted">
                                Your role was assigned by the person who
                                invited you.
                            </small>

                        </div>


                        {{-- Password --}}
                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label fw-semibold"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter password"
                                autocomplete="new-password"
                                required
                            >

                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Password must be at least 4 characters.
                            </small>

                        </div>


                        {{-- Confirm Password --}}
                        <div class="mb-4">

                            <label
                                for="password_confirmation"
                                class="form-label fw-semibold"
                            >
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm password"
                                autocomplete="new-password"
                                required
                            >

                        </div>


                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2"
                        >
                            Create Account & Accept Invitation
                        </button>

                    </form>


                    {{-- Footer Message --}}
                    <p class="text-center text-muted small mt-4 mb-0">

                        After creating your account, this invitation will
                        automatically be accepted and you will become a
                        member of the project.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection