@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">User Invitations</h2>
            <p class="text-muted mb-0">
                Manage organization invitations
            </p>
        </div>

        <a href="{{ route('invitations.create') }}"
           class="btn btn-primary">
            + Invite User
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             id="success-alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Invitations Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Organization</th>

                            <th>Email</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Sent At</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invitations as $invitation)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $invitation->organization->name }}
                                    </strong>

                                </td>


                                <td>

                                    {{ $invitation->email }}

                                </td>


                                <td>

                                    @if($invitation->role === 'manager')

                                        <span class="badge bg-primary">
                                            Manager
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Employee
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($invitation->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @elseif($invitation->status === 'accepted')

                                        <span class="badge bg-success">
                                            Accepted
                                        </span>

                                    @elseif($invitation->status === 'expired')

                                        <span class="badge bg-danger">
                                            Expired
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $invitation->created_at?->format('Y-m-d H:i') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <h5>No Invitations Yet</h5>

                                        <p class="mb-3">
                                            You haven't sent any user invitations.
                                        </p>

                                        <a href="{{ route('invitations.create') }}"
                                           class="btn btn-primary">

                                            Invite Your First User

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>

setTimeout(function () {

    let alert = document.getElementById('success-alert');

    if (alert) {

        alert.classList.remove('show');

        setTimeout(function () {
            alert.remove();
        }, 500);

    }

}, 5000);

</script>

@endsection