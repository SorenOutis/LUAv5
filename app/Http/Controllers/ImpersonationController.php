<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user
     */
    public function start(User $user)
    {
        Gate::allowIf(Auth::user()?->hasRole('admin'), 'Only admins can impersonate users.');

        if ($user->is(Auth::user())) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        session()->put('impersonator_id', Auth::id());
        Auth::login($user, remember: true);

        return redirect()->route('dashboard')->with('success', 'You are now impersonating '.$user->name);
    }

    /**
     * Stop impersonating and return to admin account
     */
    public function stop()
    {
        if (! session()->has('impersonator_id')) {
            return back()->with('error', 'You are not currently impersonating anyone.');
        }

        $impersonatorId = session()->get('impersonator_id');
        $impersonator = User::findOrFail($impersonatorId);

        // Remove impersonation flag BEFORE login to avoid conflicts
        session()->forget('impersonator_id');

        // Re-authenticate as admin
        Auth::login($impersonator, remember: true);

        // Use Inertia::location for proper redirect
        return Inertia::location(route('filament.admin.pages.dashboard'));
    }
}
