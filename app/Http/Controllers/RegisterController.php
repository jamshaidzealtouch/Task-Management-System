<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Enums\UserRole;

class RegisterController extends Controller
{

    public function index()
    {
        return view('auth.register');
    }
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['role'] = UserRole::from(
            $request->input('role')
        );

        User::create($data);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Registration successful.'
            );
    }
}