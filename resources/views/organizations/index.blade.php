@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">Organization List</h2>

        <a href="{{ route('organizations.create') }}"
           class="btn btn-primary">

            Add Organization

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

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark text-center">

                <tr>

                    <th>ID</th>

                    <th>Organization Name</th>

                    <th>Description</th>

                    <th>Owner ID</th>

                    <th>Created At</th>

                    <th>Updated At</th>

                    <th width="250">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($organizations as $organization)

                    <tr>

                        <td>{{ $organization->id }}</td>

                        <td>{{ $organization->name }}</td>

                        <td>{{ $organization->description }}</td>

                        <td>{{ $organization->owner_id }}</td>

                        <td>{{ $organization->created_at }}</td>

                        <td>{{ $organization->updated_at }}</td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('organizations.show', $organization->id) }}"
                                   class="btn btn-info btn-sm">

                                    View

                                </a>

                                <a href="{{ route('organizations.edit', $organization->id) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('organizations.destroy', $organization->id) }}"
                                      method="POST">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this organization?')">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center text-danger py-4">

                            No Organizations Found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

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