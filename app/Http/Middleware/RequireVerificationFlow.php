<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireVerificationFlow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow if already authenticated
        if (auth()->check()) {
            return $next($request);
        }

        // Check if user has verified through the VerificationSlider
        if (!session()->has('verification_verified')) {
            // Redirect to home/welcome page where verification is required
            return redirect('/');
        }

        return $next($request);
    }
}
