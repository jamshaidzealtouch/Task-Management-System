<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Enums\UserRole;

class RegisterController extends Controller
{
    /**
     * Show registration form.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();

        // Use role selected from registration form
        $data['role'] = UserRole::from($data['role']);

        User::create($data);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Registration successful.'
            );
    }
}