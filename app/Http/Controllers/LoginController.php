<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function check(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Email not found
        if (!$user) {

            return back()->with(
                'error',
                'Email not found.'
            );
        }
       
        if (!Hash::check($request->password, $user->password)) {

            return back()->with(
                'error',
                'Incorrect password.'
            );
        }
        session([
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role->value,
        ]);
        return redirect()->route('home');
    }

    public function logout()
    {
        session()->flush();

        return redirect()->route('login');
    }
}