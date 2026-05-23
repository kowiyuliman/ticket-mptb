<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array(auth()->user()->role, ['admin','leader'])) {
            abort(403);
        }

        if (auth()->user()->role == 'user') {
            abort(403);
        }

        return $next($request);
    }
}