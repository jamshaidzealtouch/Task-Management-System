<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
  
    public function index()
    {
        return view('auth.register');
    }

    
    public function store(RegisterRequest $request)
{
    $data = $request->validated();

    $data['password'] = Hash::make($data['password']);

    User::create($data);

    return redirect('/login')
        ->with('success', 'Registration successful. Please login.');
}
}