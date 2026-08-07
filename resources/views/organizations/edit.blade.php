@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3>Edit Organization</h3>

        </div>

        <div class="card-body">

            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('organizations.update', $organization->id) }}"
                  method="POST">

                @csrf

                @method('PUT')

                {{-- Organization Name --}}
                <div class="mb-3">

                    <label class="form-label">

                        Organization Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $organization->name) }}">

                </div>

                {{-- Description --}}
                <div class="mb-3">

                    <label class="form-label">

                        Description

                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4">{{ old('description', $organization->description) }}</textarea>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Organization

                </button>

                <a href="{{ route('organizations.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection