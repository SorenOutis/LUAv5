<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CodingController extends Controller
{
    public function index()
    {
        return Inertia::render('Coding', [
            'challenges' => [],
            'stats' => [
                'totalChallenges' => 0,
                'completedChallenges' => 0,
                'xpEarned' => 0,
                'currentStreak' => 0,
                'totalXP' => 0,
                'level' => 0,
            ],
            'resources' => [],
            'userLevel' => 0,
            'userName' => auth()->user()?->name ?? 'User',
        ]);
    }
}
