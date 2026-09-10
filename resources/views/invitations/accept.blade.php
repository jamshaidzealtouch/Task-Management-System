@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-5 text-center">

                    <div class="mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10
                                    d-inline-flex align-items-center
                                    justify-content-center"
                             style="width: 70px; height: 70px;">

                            <span class="fs-2">✉</span>

                        </div>
                    </div>

                    <h2 class="fw-bold mb-3">
                        You're Invited!
                    </h2>

                    <p class="text-muted mb-4">
                        You have been invited to join the following organization.
                    </p>

                    <div class="bg-light rounded p-4 mb-4 text-start">

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Organization
                            </small>

                            <strong class="fs-5">
                                {{ $invitation->organization->name }}
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

                    <p class="text-muted small mb-4">
                        Your invitation is valid until
                        <strong>
                            {{ $invitation->expires_at?->format('d M Y, h:i A') }}
                        </strong>.
                    </p>

                    <a href="{{ route('invitations.register', $invitation->token) }}"
                       class="btn btn-primary px-4 py-2">

                        Accept Invitation & Create Account

                    </a>

                    <p class="text-muted small mt-4 mb-0">
                        By accepting this invitation, you will become a member
                        of this organization.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection