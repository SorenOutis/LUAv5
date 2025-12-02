<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Leaderboard', [
            'leaderboard' => [
                [
                    'rank' => 1,
                    'userId' => 1,
                    'name' => 'Alex Chen',
                    'xp' => 15750,
                    'level' => 25,
                    'streakDays' => 42,
                    'achievements' => 18,
                    'isCurrentUser' => false,
                ],
                [
                    'rank' => 2,
                    'userId' => 2,
                    'name' => 'Jordan Silva',
                    'xp' => 14200,
                    'level' => 24,
                    'streakDays' => 38,
                    'achievements' => 16,
                    'isCurrentUser' => false,
                ],
                [
                    'rank' => 3,
                    'userId' => 3,
                    'name' => 'Taylor Morgan',
                    'xp' => 13500,
                    'level' => 23,
                    'streakDays' => 35,
                    'achievements' => 15,
                    'isCurrentUser' => false,
                ],
                [
                    'rank' => 4,
                    'userId' => 4,
                    'name' => 'Casey Rivera',
                    'xp' => 12800,
                    'level' => 22,
                    'streakDays' => 30,
                    'achievements' => 14,
                    'isCurrentUser' => false,
                ],
                [
                    'rank' => 5,
                    'userId' => 5,
                    'name' => 'You',
                    'xp' => 8500,
                    'level' => 18,
                    'streakDays' => 12,
                    'achievements' => 8,
                    'isCurrentUser' => true,
                ],
                [
                    'rank' => 6,
                    'userId' => 6,
                    'name' => 'Morgan Blake',
                    'xp' => 7800,
                    'level' => 16,
                    'streakDays' => 8,
                    'achievements' => 7,
                    'isCurrentUser' => false,
                ],
                [
                    'rank' => 7,
                    'userId' => 7,
                    'name' => 'Riley Stone',
                    'xp' => 6500,
                    'level' => 14,
                    'streakDays' => 5,
                    'achievements' => 5,
                    'isCurrentUser' => false,
                ],
                [
                    'rank' => 8,
                    'userId' => 8,
                    'name' => 'Avery Cross',
                    'xp' => 5200,
                    'level' => 12,
                    'streakDays' => 3,
                    'achievements' => 4,
                    'isCurrentUser' => false,
                ],
            ],
            'userRank' => [
                'rank' => 5,
                'userId' => 5,
                'name' => 'You',
                'xp' => 8500,
                'level' => 18,
                'streakDays' => 12,
                'achievements' => 8,
                'isCurrentUser' => true,
            ],
            'stats' => [
                'totalUsers' => 1247,
                'topXP' => 15750,
                'myRank' => 5,
                'xpToNextRank' => 4300,
            ],
        ]);
    }
}
