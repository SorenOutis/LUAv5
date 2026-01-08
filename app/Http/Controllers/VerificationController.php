<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VerificationController extends Controller
{
    /**
     * Mark user as verified through the VerificationSlider
     */
    public function mark(Request $request)
    {
        session()->put('verification_verified', true);
        session()->save();

        return back();
    }
}
