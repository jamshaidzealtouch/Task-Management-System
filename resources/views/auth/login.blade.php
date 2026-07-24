@extends('layouts.auth')

@section('content')

<div class="container mt-5">

    <div class="card shadow p-4 mx-auto" style="max-width:500px;">

        <h2 class="text-center mb-4">Welcome Back</h2>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


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



        <form action="{{ route('login.check') }}" method="POST">

            @csrf


            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                    required>

            </div>



            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required>

            </div>



            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>


        </form>



        <p class="text-center mt-3">
            Don't have an account?
            <a href="{{ route('register') }}">
                Create Account
            </a>
        </p>


    </div>

</div>

@endsection