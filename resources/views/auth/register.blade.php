@extends('layouts.auth')

@section('content')

<div class="container mt-5">

<div class="card shadow p-4 mx-auto" style="max-width:500px;">

<h2 class="text-center mb-4">Create Account</h2>


{{-- Success Alert --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


{{-- Error Alert --}}
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



<form action="{{ route('register.store') }}" method="POST">

@csrf


<div class="mb-3">
<label class="form-label">Full Name</label>

<input 
type="text"
name="name"
class="form-control"
placeholder="Enter your name"
value="{{ old('name') }}"
required>

</div>



<div class="mb-3">

<label class="form-label">Email Address</label>

<input 
type="email"
name="email"
class="form-control"
placeholder="Enter your email"
value="{{ old('email') }}"
required>

</div>



<div class="mb-3">

<label class="form-label">Password</label>

<input 
type="password"
name="password"
class="form-control"
placeholder="Create password"
required>

</div>



<button class="btn btn-success w-100">
Create Account
</button>


</form>


<p class="text-center mt-3">
Already have an account?
<a href="/login">Login</a>
</p>


</div>

</div>


@endsection