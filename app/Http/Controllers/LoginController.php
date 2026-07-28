<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
   
    public function index()
    {
        return view('auth.login');
    }

  
    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Incorrect password.');
        }

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
        ]);

        return redirect('/home');
    }

    // Logout
    public function logout()
    {
        session()->flush();

        return redirect('/login');
    }
}