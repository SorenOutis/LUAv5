<?php

namespace Database\Seeders;

use App\Models\Quest;
use Illuminate\Database\Seeder;

class QuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quest::create([
            'title' => 'Update Profile',
            'description' => 'Complete your user profile with all required information',
            'xp' => 50,
            'difficulty' => 'Easy',
        ]);

        Quest::create([
            'title' => 'Complete 3 Lessons',
            'description' => 'Finish three lessons in any course to earn bonus XP',
            'xp' => 150,
            'difficulty' => 'Easy',
        ]);

        Quest::create([
            'title' => 'Reach Level 10',
            'description' => 'Advance to level 10 through consistent learning',
            'xp' => 500,
            'difficulty' => 'Medium',
        ]);

        Quest::create([
            'title' => 'Daily Streaker',
            'description' => 'Maintain a 7-day learning streak',
            'xp' => 200,
            'difficulty' => 'Medium',
        ]);

        Quest::create([
            'title' => 'Master the Basics',
            'description' => 'Complete all beginner courses',
            'xp' => 1000,
            'difficulty' => 'Hard',
        ]);

        Quest::create([
            'title' => 'Legendary Quest',
            'description' => 'Reach level 50 and unlock all achievements',
            'xp' => 5000,
            'difficulty' => 'Legendary',
        ]);

        Quest::create([
            'title' => 'First Achievement',
            'description' => 'Unlock your first achievement',
            'xp' => 150,
            'difficulty' => 'Easy',
        ]);

        Quest::create([
            'title' => 'Course Master',
            'description' => 'Complete 5 courses successfully',
            'xp' => 750,
            'difficulty' => 'Hard',
        ]);

        Quest::create([
            'title' => 'Social Butterfly',
            'description' => 'Participate in 10 course discussions',
            'xp' => 300,
            'difficulty' => 'Medium',
        ]);

        Quest::create([
            'title' => 'Perfect Score',
            'description' => 'Score 100% on a course assessment',
            'xp' => 400,
            'difficulty' => 'Hard',
        ]);
    }
}
