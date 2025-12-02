<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuestsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Quests', [
            'activeQuests' => [
                [
                    'id' => 1,
                    'title' => 'Complete 3 Lessons',
                    'description' => 'Finish three lessons in any course to earn bonus XP',
                    'objective' => 'Complete lessons',
                    'reward' => 150,
                    'difficulty' => 'Easy',
                    'daysLeft' => 5,
                    'progress' => 67,
                    'status' => 'active',
                ],
                [
                    'id' => 2,
                    'title' => 'Reach Level 10',
                    'description' => 'Advance to level 10 through consistent learning',
                    'objective' => 'Gain experience',
                    'reward' => 500,
                    'difficulty' => 'Medium',
                    'daysLeft' => 10,
                    'progress' => 40,
                    'status' => 'active',
                ],
                [
                    'id' => 3,
                    'title' => 'Daily Streaker',
                    'description' => 'Maintain a 7-day learning streak',
                    'objective' => 'Learn daily',
                    'reward' => 200,
                    'difficulty' => 'Medium',
                    'daysLeft' => 3,
                    'progress' => 85,
                    'status' => 'active',
                ],
            ],
            'completedQuests' => [
                [
                    'id' => 4,
                    'title' => 'Welcome to Learning',
                    'description' => 'Start your first course',
                    'objective' => 'Enroll in a course',
                    'reward' => 100,
                    'difficulty' => 'Easy',
                    'daysLeft' => 0,
                    'progress' => 100,
                    'status' => 'completed',
                ],
                [
                    'id' => 5,
                    'title' => 'First Achievement',
                    'description' => 'Unlock your first achievement',
                    'objective' => 'Unlock achievement',
                    'reward' => 150,
                    'difficulty' => 'Easy',
                    'daysLeft' => 0,
                    'progress' => 100,
                    'status' => 'completed',
                ],
            ],
            'availableQuests' => [
                [
                    'id' => 6,
                    'title' => 'Master the Basics',
                    'description' => 'Complete all beginner courses',
                    'objective' => 'Complete courses',
                    'reward' => 1000,
                    'difficulty' => 'Hard',
                    'daysLeft' => 30,
                    'progress' => 0,
                    'status' => 'available',
                ],
                [
                    'id' => 7,
                    'title' => 'Legendary Quest',
                    'description' => 'Reach level 50 and unlock all achievements',
                    'objective' => 'Ultimate challenge',
                    'reward' => 5000,
                    'difficulty' => 'Legendary',
                    'daysLeft' => 365,
                    'progress' => 0,
                    'status' => 'available',
                ],
            ],
            'stats' => [
                'totalActive' => 3,
                'totalCompleted' => 2,
                'totalRewards' => 250,
                'streakDays' => 6,
            ],
        ]);
    }
}
