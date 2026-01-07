<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationExitController extends Controller
{
    public function __invoke(Request $request)
    {
        $impersonatorId = $request->session()->get('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        $impersonator = User::findOrFail($impersonatorId);

        // Clear all impersonation-related session data
        $request->session()->forget('impersonator_id');
        $request->session()->save();

        // Re-authenticate as admin
        Auth::login($impersonator, remember: true);

        return redirect()->route('filament.admin.pages.dashboard');
    }
}
