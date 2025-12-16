<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CodingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get user profile for XP and level data
        $profile = $user->profile()->firstOrCreate([], [
            'total_xp' => 0,
            'level' => 1,
            'current_level_xp' => 0,
            'xp_for_next_level' => 1000,
            'streak_days' => 0,
            'rank_title' => 'Plastic',
        ]);

        // Prepare coding challenges (sample data)
        $challenges = [
            [
                'id' => 1,
                'title' => 'Hello World',
                'difficulty' => 'Beginner',
                'description' => 'Write a program that prints "Hello, World!" to the console.',
                'xpReward' => 50,
                'completed' => false,
                'language' => 'JavaScript',
                'category' => 'Basics',
            ],
            [
                'id' => 2,
                'title' => 'Sum of Numbers',
                'difficulty' => 'Beginner',
                'description' => 'Create a function that returns the sum of two numbers.',
                'xpReward' => 75,
                'completed' => false,
                'language' => 'JavaScript',
                'category' => 'Functions',
            ],
            [
                'id' => 3,
                'title' => 'Fibonacci Sequence',
                'difficulty' => 'Intermediate',
                'description' => 'Generate the first n numbers in the Fibonacci sequence.',
                'xpReward' => 150,
                'completed' => false,
                'language' => 'JavaScript',
                'category' => 'Algorithms',
            ],
            [
                'id' => 4,
                'title' => 'Palindrome Checker',
                'difficulty' => 'Intermediate',
                'description' => 'Check if a string is a palindrome.',
                'xpReward' => 125,
                'completed' => false,
                'language' => 'JavaScript',
                'category' => 'Strings',
            ],
            [
                'id' => 5,
                'title' => 'Binary Search',
                'difficulty' => 'Advanced',
                'description' => 'Implement a binary search algorithm.',
                'xpReward' => 250,
                'completed' => false,
                'language' => 'JavaScript',
                'category' => 'Algorithms',
            ],
        ];

        // Prepare statistics
        $stats = [
            'totalChallenges' => count($challenges),
            'completedChallenges' => 0,
            'xpEarned' => 0,
            'currentStreak' => $profile->streak_days,
            'totalXP' => $profile->total_xp,
            'level' => $profile->level,
        ];

        // Prepare featured resources
        $resources = [
            [
                'id' => 1,
                'title' => 'JavaScript Basics',
                'description' => 'Learn the fundamentals of JavaScript programming.',
                'type' => 'Tutorial',
                'difficulty' => 'Beginner',
                'duration' => '2 hours',
            ],
            [
                'id' => 2,
                'title' => 'Advanced Algorithms',
                'description' => 'Master complex algorithmic problem-solving techniques.',
                'type' => 'Course',
                'difficulty' => 'Advanced',
                'duration' => '8 hours',
            ],
            [
                'id' => 3,
                'title' => 'Data Structures Deep Dive',
                'description' => 'Understand arrays, linked lists, trees, and graphs.',
                'type' => 'Tutorial',
                'difficulty' => 'Intermediate',
                'duration' => '4 hours',
            ],
        ];

        return Inertia::render('Coding', [
            'challenges' => $challenges,
            'stats' => $stats,
            'resources' => $resources,
            'userLevel' => $profile->level,
            'userName' => $user->name,
        ]);
    }
}
