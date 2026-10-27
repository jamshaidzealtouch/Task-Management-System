<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid email or password.',
            ];
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];
    }

    public function register(
        string $name,
        string $email,
        string $password,
        string $role
    ): User {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
           'role' => $role,
        ]);
    }
}