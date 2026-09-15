<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\MemberResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful.',
            'user' => new MemberResource($result['user']),
            'token' => $result['token'],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful.'
        ], 200);
    }
    // Register new user
    public function register(RegisterRequest $request)
{
    $user = $this->authService->register( $request->name,$request->email,$request->password, $request->role);

    return response()->json([
        'message' => 'Registration successful.',
        'user' => new MemberResource($user),
    ], 201);
}
}