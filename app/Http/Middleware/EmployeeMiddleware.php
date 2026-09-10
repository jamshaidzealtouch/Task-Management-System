<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user_role')) {

            abort(403, 'Unauthorized');

        }

        if (!in_array(session('user_role'), [

            'admin',

            'manager',

            'employee',

        ])) {

            abort(403, 'Unauthorized');

        }

        return $next($request);
    }
}