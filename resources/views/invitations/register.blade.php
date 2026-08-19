@extends('layouts.guest')

@section('content')

<div class="container mt-5">

    <div class="card shadow mx-auto" style="max-width: 550px;">

        <div class="card-body p-5">

            <h2 class="text-center mb-4">
                Create Your Account
            </h2>

            <p class="text-center text-muted mb-4">
                You have been invited to join
                <strong>{{ $invitation->organization->name }}</strong>
            </p>

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

                    <label for="name" class="form-label">
                        Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Enter your name"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Email --}}
                <div class="mb-3">

                    <label for="email" class="form-label">
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
                        This email address was used for the invitation.
                    </small>

                </div>


                {{-- Role --}}
                <div class="mb-3">

                    <label for="role" class="form-label">
                        Role
                    </label>

                    <input
                        type="text"
                        id="role"
                        class="form-control"
                        value="{{ ucfirst($invitation->role) }}"
                        readonly
                    >

                </div>


                {{-- Password --}}
                <div class="mb-3">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter password"
                        required
                    >

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <small class="text-muted">
                        Password must be at least 8 characters.
                    </small>

                </div>


                {{-- Confirm Password --}}
                <div class="mb-4">

                    <label for="password_confirmation" class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm password"
                        required
                    >

                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    Create Account & Accept Invitation
                </button>

            </form>

        </div>

    </div>

</div>

@endsection