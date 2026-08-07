<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;


class RegisterController extends Controller
{
  
    public function index()
    {
        return view('auth.register');
    }

    
    public function store(StoreRegisterRequest $request)
    {
        $data = $request->validated();
    
        $data['password'] = bcrypt($data['password']);
    
        $data['role'] = User::count() == 0
            ? UserRole::Admin
            : UserRole::Employee;
    
        User::create($data);
    
        return redirect()
            ->route('login')
            ->with('success', 'Registration successful.');
    }
}