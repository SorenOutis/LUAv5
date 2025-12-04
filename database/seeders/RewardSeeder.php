<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            // Badge Rewards (Free/Low Cost)
            [
                'name' => 'Starter Badge',
                'description' => 'Commemorate your journey beginning',
                'icon' => '🏅',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 100,
            ],
            [
                'name' => 'Silver Medal',
                'description' => 'Earn for consistent performance',
                'icon' => '🥈',
                'category' => 'Milestones',
                'difficulty' => 'Medium',
                'xp_reward' => 250,
            ],
            [
                'name' => 'Gold Medal',
                'description' => 'Reward for exceptional achievement',
                'icon' => '🥇',
                'category' => 'Milestones',
                'difficulty' => 'Hard',
                'xp_reward' => 500,
            ],

            // Skill Certificates
            [
                'name' => 'Communicator Certificate',
                'description' => 'Master of written communication',
                'icon' => '📜',
                'category' => 'Social',
                'difficulty' => 'Medium',
                'xp_reward' => 300,
            ],
            [
                'name' => 'Problem Solver Diploma',
                'description' => 'Proven analytical skills',
                'icon' => '🎓',
                'category' => 'Learning',
                'difficulty' => 'Hard',
                'xp_reward' => 400,
            ],

            // Tier Unlock Rewards
            [
                'name' => 'Bronze Tier Key',
                'description' => 'Unlock exclusive bronze tier benefits',
                'icon' => '🔑',
                'category' => 'Levels',
                'difficulty' => 'Easy',
                'xp_reward' => 150,
            ],
            [
                'name' => 'Silver Tier Key',
                'description' => 'Unlock exclusive silver tier benefits',
                'icon' => '🗝️',
                'category' => 'Levels',
                'difficulty' => 'Medium',
                'xp_reward' => 350,
            ],
            [
                'name' => 'Gold Tier Key',
                'description' => 'Unlock exclusive gold tier benefits',
                'icon' => '👑',
                'category' => 'Levels',
                'difficulty' => 'Hard',
                'xp_reward' => 600,
            ],

            // Special Badges
            [
                'name' => 'Mentor Badge',
                'description' => 'Recognition for helping others learn',
                'icon' => '👨‍🏫',
                'category' => 'Social',
                'difficulty' => 'Hard',
                'xp_reward' => 450,
            ],
            [
                'name' => 'Streak Master Badge',
                'description' => 'For maintaining remarkable consistency',
                'icon' => '🔥💎',
                'category' => 'Streak',
                'difficulty' => 'Hard',
                'xp_reward' => 500,
            ],
            [
                'name' => 'Innovation Badge',
                'description' => 'For creative problem solving',
                'icon' => '💡',
                'category' => 'Special',
                'difficulty' => 'Medium',
                'xp_reward' => 350,
            ],

            // XP Boosters
            [
                'name' => '25% XP Booster',
                'description' => 'Increase earned XP by 25% for 7 days',
                'icon' => '⚡',
                'category' => 'XP',
                'difficulty' => 'Easy',
                'xp_reward' => 200,
            ],
            [
                'name' => '50% XP Booster',
                'description' => 'Increase earned XP by 50% for 7 days',
                'icon' => '⚡⚡',
                'category' => 'XP',
                'difficulty' => 'Medium',
                'xp_reward' => 400,
            ],
            [
                'name' => '100% XP Booster',
                'description' => 'Double your earned XP for 7 days',
                'icon' => '⚡⚡⚡',
                'category' => 'XP',
                'difficulty' => 'Hard',
                'xp_reward' => 700,
            ],

            // Profile Customization
            [
                'name' => 'Custom Avatar Frame',
                'description' => 'Personalize your profile with a unique frame',
                'icon' => '🖼️',
                'category' => 'Special',
                'difficulty' => 'Easy',
                'xp_reward' => 150,
            ],
            [
                'name' => 'Profile Banner Pack',
                'description' => 'Exclusive banners for your profile',
                'icon' => '🎨',
                'category' => 'Special',
                'difficulty' => 'Medium',
                'xp_reward' => 300,
            ],

            // Leaderboard Positions
            [
                'name' => 'Top 10 Recognition',
                'description' => 'Achievement reward for reaching top 10',
                'icon' => '📊',
                'category' => 'Milestones',
                'difficulty' => 'Hard',
                'xp_reward' => 550,
            ],
            [
                'name' => 'Leaderboard Champion',
                'description' => 'Recognition as #1 on the leaderboard',
                'icon' => '🏆',
                'category' => 'Milestones',
                'difficulty' => 'Legendary',
                'xp_reward' => 1000,
            ],
        ];

        foreach ($rewards as $reward) {
            Achievement::firstOrCreate(['name' => $reward['name']], $reward);
        }
    }
}
