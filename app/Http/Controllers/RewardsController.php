<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RewardsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Rewards', [
            'rewards' => [
                [
                    'id' => 1,
                    'name' => 'First Lesson Completed',
                    'description' => 'You earned this reward for completing your first lesson',
                    'icon' => '🎓',
                    'type' => 'badge',
                    'earnedAt' => 'Dec 1, 2023',
                    'xpValue' => 50,
                ],
                [
                    'id' => 2,
                    'name' => 'Course Completion Certificate',
                    'description' => 'HTML & CSS Basics - Certificate of Completion',
                    'icon' => '🏅',
                    'type' => 'certificate',
                    'earnedAt' => 'Dec 30, 2023',
                    'xpValue' => 200,
                ],
                [
                    'id' => 3,
                    'name' => 'Advanced Features Unlocked',
                    'description' => 'You unlocked access to premium course content',
                    'icon' => '🔓',
                    'type' => 'unlock',
                    'earnedAt' => 'Jan 1, 2024',
                    'xpValue' => 150,
                ],
                [
                    'id' => 4,
                    'name' => 'Bonus XP - Holiday Bonus',
                    'description' => 'Special holiday bonus for your learning',
                    'icon' => '🎉',
                    'type' => 'bonus',
                    'earnedAt' => 'Dec 25, 2023',
                    'xpValue' => 500,
                ],
                [
                    'id' => 5,
                    'name' => 'Weekly Streak Bonus',
                    'description' => 'You maintained your learning streak',
                    'icon' => '🔥',
                    'type' => 'bonus',
                    'earnedAt' => 'Jan 2, 2024',
                    'xpValue' => 100,
                ],
                [
                    'id' => 6,
                    'name' => 'Perfect Quiz Score',
                    'description' => '100% on JavaScript Basics Quiz',
                    'icon' => '💯',
                    'type' => 'badge',
                    'earnedAt' => 'Dec 28, 2023',
                    'xpValue' => 75,
                ],
            ],
            'badges' => [
                [
                    'id' => 1,
                    'name' => 'Quick Learner',
                    'description' => 'Complete 5 lessons in one day',
                    'icon' => '⚡',
                    'rarity' => 'uncommon',
                    'earnedAt' => 'Dec 28, 2023',
                ],
                [
                    'id' => 2,
                    'name' => 'Dedicated Student',
                    'description' => 'Maintain 7-day streak',
                    'icon' => '🔥',
                    'rarity' => 'uncommon',
                    'earnedAt' => 'Dec 15, 2023',
                ],
                [
                    'id' => 3,
                    'name' => 'Course Master',
                    'description' => 'Complete a full course',
                    'icon' => '🏆',
                    'rarity' => 'rare',
                    'earnedAt' => 'Dec 30, 2023',
                ],
                [
                    'id' => 4,
                    'name' => 'Knowledge Seeker',
                    'description' => 'Enroll in 3 courses',
                    'icon' => '📚',
                    'rarity' => 'common',
                    'earnedAt' => 'Dec 20, 2023',
                ],
                [
                    'id' => 5,
                    'name' => 'Rising Star',
                    'description' => 'Reach level 15',
                    'icon' => '⭐',
                    'rarity' => 'rare',
                    'earnedAt' => 'Dec 22, 2023',
                ],
            ],
            'stats' => [
                'totalRewards' => 6,
                'totalBadges' => 5,
                'totalXPFromRewards' => 1175,
                'nextMilestone' => '10 Rewards Earned',
            ],
        ]);
    }
}
