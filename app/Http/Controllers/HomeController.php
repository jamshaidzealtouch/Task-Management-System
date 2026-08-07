<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function home()
    {
        $userRole = session('user_role');
    
        return view('home', compact('userRole'));
    }

    public function about()
    {
        return view('about');
    }
}