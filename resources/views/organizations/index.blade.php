@extends('layouts.app')


@section('content')


<div class="container mt-4">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <h2 class="mb-0">Organization List</h2>


        {{-- Add Organization --}}
        @if(session('user_role') === 'admin')

            <a href="{{ route('organizations.create') }}"
               class="btn btn-primary">

                Add Organization

            </a>

        @endif


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
                    <th width="350">Action</th>

                </tr>

            </thead>


            <tbody>


                @forelse($organizations as $organization)


                    <tr>


                        {{-- ID --}}
                        <td>
                            {{ $organization->id }}
                        </td>


                        {{-- Organization Name --}}
                        <td>

                            <strong>
                                {{ $organization->name }}
                            </strong>

                        </td>


                        {{-- Description --}}
                        <td>
                            {{ $organization->description ?? 'N/A' }}
                        </td>


                        {{-- Owner --}}
                        <td class="text-center">
                            {{ $organization->owner_id }}
                        </td>


                        {{-- Created At --}}
                        <td>
                            {{ $organization->created_at?->format('Y-m-d H:i') }}
                        </td>


                        {{-- Updated At --}}
                        <td>
                            {{ $organization->updated_at?->format('Y-m-d H:i') }}
                        </td>


                        {{-- Actions --}}
                        <td>


                            <div class="d-flex justify-content-center gap-2 flex-wrap">


                                {{-- View --}}
                                <a href="{{ route(
                                    'organizations.show',
                                    $organization->id
                                ) }}"
                                   class="btn btn-info btn-sm">

                                    View

                                </a>


                                {{-- Edit --}}
                                <a href="{{ route(
                                    'organizations.edit',
                                    $organization->id
                                ) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>


                                {{-- Projects --}}
                                <a href="{{ route(
                                    'organizations.projects.index',
                                    $organization->id
                                ) }}"
                                   class="btn btn-primary btn-sm">

                                    Projects

                                </a>


                                {{-- Delete --}}
                                <form action="{{ route(
                                    'organizations.destroy',
                                    $organization->id
                                ) }}"
                                      method="POST">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm(
                                                'Are you sure you want to delete this organization?'
                                            )">

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