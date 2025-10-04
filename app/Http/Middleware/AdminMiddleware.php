<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        if (Auth::check()) {
            return redirect()->route('employee.dashboard')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        return redirect()->route('login')->with('error', 'Please login to continue.');
    }
}