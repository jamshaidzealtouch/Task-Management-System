<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show login page.
     */
    public function index()
    {
        return view('auth.login');
    }


    /**
     * Check login credentials.
     */
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


        // Incorrect password
        if (!Hash::check($request->password, $user->password)) {

            return back()->with(
                'error',
                'Incorrect password.'
            );
        }


        // Store user information in session
        session([
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role->value,
        ]);


        // Login successful
        return redirect()->route('home');
    }


    /**
     * Logout user.
     */
    public function logout()
    {
        // Remove all session data
        session()->flush();

        // Redirect to login page
        return redirect()->route('login');
    }
}