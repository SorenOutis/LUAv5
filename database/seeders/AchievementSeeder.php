<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Learning Achievements (Very Easy to get)
            [
                'name' => 'First Steps',
                'description' => 'Complete your first lesson',
                'icon' => '📚',
                'category' => 'Learning',
                'difficulty' => 'Easy',
                'xp_reward' => 75,
            ],
            [
                'name' => 'Lesson Collector',
                'description' => 'Complete 2 lessons',
                'icon' => '📖',
                'category' => 'Learning',
                'difficulty' => 'Easy',
                'xp_reward' => 100,
            ],
            [
                'name' => 'Quick Learner',
                'description' => 'Complete a lesson in one day',
                'icon' => '⚡',
                'category' => 'Learning',
                'difficulty' => 'Easy',
                'xp_reward' => 75,
            ],
            [
                'name' => 'Knowledge Seeker',
                'description' => 'Complete 5 lessons total',
                'icon' => '🎓',
                'category' => 'Learning',
                'difficulty' => 'Easy',
                'xp_reward' => 150,
            ],

            // Streak Achievements (Very Easy to get)
            [
                'name' => 'Getting Started',
                'description' => 'Maintain a 1-day learning streak',
                'icon' => '🔥',
                'category' => 'Streak',
                'difficulty' => 'Easy',
                'xp_reward' => 60,
            ],
            [
                'name' => 'Consistent Learner',
                'description' => 'Maintain a 2-day learning streak',
                'icon' => '💪',
                'category' => 'Streak',
                'difficulty' => 'Easy',
                'xp_reward' => 100,
            ],
            [
                'name' => 'On Fire',
                'description' => 'Maintain a 3-day learning streak',
                'icon' => '🏃',
                'category' => 'Streak',
                'difficulty' => 'Easy',
                'xp_reward' => 150,
            ],
            [
                'name' => 'Unstoppable',
                'description' => 'Maintain a 7-day learning streak',
                'icon' => '⭐',
                'category' => 'Streak',
                'difficulty' => 'Medium',
                'xp_reward' => 300,
            ],

            // Achievement Milestones (Very Easy to get)
            [
                'name' => 'Achievement Starter',
                'description' => 'Unlock 1 achievement',
                'icon' => '🎯',
                'category' => 'Milestones',
                'difficulty' => 'Easy',
                'xp_reward' => 50,
            ],
            [
                'name' => 'Achievement Hunter',
                'description' => 'Unlock 3 achievements',
                'icon' => '🏆',
                'category' => 'Milestones',
                'difficulty' => 'Easy',
                'xp_reward' => 125,
            ],
            [
                'name' => 'Collector',
                'description' => 'Unlock 5 achievements',
                'icon' => '✨',
                'category' => 'Milestones',
                'difficulty' => 'Medium',
                'xp_reward' => 250,
            ],
            [
                'name' => 'Master Collector',
                'description' => 'Unlock 10 achievements',
                'icon' => '🌟',
                'category' => 'Milestones',
                'difficulty' => 'Hard',
                'xp_reward' => 500,
            ],

            // Engagement Achievements (Very Easy to get)
            [
                'name' => 'Social Butterfly',
                'description' => 'Post your first comment',
                'icon' => '🦋',
                'category' => 'Social',
                'difficulty' => 'Easy',
                'xp_reward' => 50,
            ],
            [
                'name' => 'Friendly',
                'description' => 'Get 1 reply to your post',
                'icon' => '👋',
                'category' => 'Social',
                'difficulty' => 'Easy',
                'xp_reward' => 60,
            ],
            [
                'name' => 'Conversational',
                'description' => 'Participate in 2 discussions',
                'icon' => '💬',
                'category' => 'Social',
                'difficulty' => 'Easy',
                'xp_reward' => 100,
            ],
            [
                'name' => 'Helper',
                'description' => 'Help 1 other student',
                'icon' => '🤝',
                'category' => 'Social',
                'difficulty' => 'Easy',
                'xp_reward' => 100,
            ],

            // Milestone Achievements (Very Easy to get)
            [
                'name' => 'Welcome Aboard',
                'description' => 'Create your first profile',
                'icon' => '🎉',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 50,
            ],
            [
                'name' => 'Night Owl',
                'description' => 'Complete a lesson after 9 PM',
                'icon' => '🌙',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 50,
            ],
            [
                'name' => 'Morning Person',
                'description' => 'Complete a lesson before 8 AM',
                'icon' => '🌅',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 50,
            ],
            [
                'name' => 'Focused',
                'description' => 'Score 70% or higher on an assessment',
                'icon' => '🎯',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 100,
            ],
            [
                'name' => 'Perfect Score',
                'description' => 'Score 100% on an assessment',
                'icon' => '💯',
                'category' => 'Special',
                'difficulty' => 'Medium',
                'xp_reward' => 200,
            ],
            [
                'name' => 'Comeback Kid',
                'description' => 'Return after being absent for 3 days',
                'icon' => '🎪',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 75,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(['name' => $achievement['name']], $achievement);
        }
    }
}
