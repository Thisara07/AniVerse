<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Allow both regular users and admins to access user routes
        if ($user->role === 'user' || $user->role === 'customer' || $user->role === 'admin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}