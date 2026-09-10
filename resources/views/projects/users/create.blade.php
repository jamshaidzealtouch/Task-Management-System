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


            {{-- Select Existing User --}}
            <div class="mb-3">

                <label
                    for="user_id"
                    class="form-label">

                    Select User

                </label>


                <select
                    name="user_id"
                    id="user_id"
                    class="form-select"
                    required>

                    <option value="">
                        -- Select User --
                    </option>


                    @forelse($users as $user)

                        @php

                            $role = $user->role instanceof \App\Enums\UserRole
                                ? $user->role->value
                                : $user->role;

                        @endphp


                        <option
                            value="{{ $user->id }}"
                            {{ old('user_id') == $user->id ? 'selected' : '' }}>

                            {{ $user->name }}
                            -
                            {{ $user->email }}
                            ({{ ucfirst($role) }})

                        </option>

                    @empty

                        <option
                            value=""
                            disabled>

                            No accepted users found

                        </option>

                    @endforelse

                </select>


                @error('user_id')

                    <div class="text-danger mt-1">

                        {{ $message }}

                    </div>

                @enderror

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