<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // If user is admin, redirect to admin dashboard
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard')
                           ->with('error', 'Admin tidak memiliki akses ke fitur pelanggan.');
        }

        // If user is a customer, allow access
        return $next($request);
    }
} 