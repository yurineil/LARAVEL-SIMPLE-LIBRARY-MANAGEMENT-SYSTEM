<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('student.home')->with('error', 'Admin only. You do not have access to that page.');
        }

        return $next($request);
    }
}
