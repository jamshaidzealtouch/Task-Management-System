<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(session('user_role'), ['admin', 'manager'])) {

            abort(403, 'Unauthorized');

        }

        return $next($request);
    }
}