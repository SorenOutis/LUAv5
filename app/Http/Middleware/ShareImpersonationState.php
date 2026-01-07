<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ShareImpersonationState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $impersonatorName = null;
        $isImpersonating = false;

        if (Auth::check() && Session::has('impersonator_id')) {
            $impersonator = \App\Models\User::find(Session::get('impersonator_id'));

            if ($impersonator) {
                $impersonatorName = $impersonator->name;
                $isImpersonating = true;
            }
        }

        inertia()->share('is_impersonating', $isImpersonating);
        inertia()->share('impersonator_name', $impersonatorName);

        return $next($request);
    }
}
