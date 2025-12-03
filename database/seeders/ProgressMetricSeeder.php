<?php

namespace Database\Seeders;

use App\Models\ProgressMetric;
use Illuminate\Database\Seeder;

class ProgressMetricSeeder extends Seeder
{
    public function run(): void
    {
        $metrics = [
            [
                'name' => 'Assignments Completed',
                'description' => 'Track your assignment completion progress',
                'current' => 0,
                'target' => 100,
                'unit' => 'assignments',
                'icon' => '✅',
                'category' => 'Learning',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'XP Gained',
                'description' => 'Total experience points earned',
                'current' => 0,
                'target' => 999999,
                'unit' => 'XP',
                'icon' => '⭐',
                'category' => 'Assessments',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Courses Completed',
                'description' => 'Full courses you have finished',
                'current' => 0,
                'target' => 5,
                'unit' => 'courses',
                'icon' => '🏁',
                'category' => 'Courses',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Current Streak',
                'description' => 'Days in a row of learning',
                'current' => 0,
                'target' => 30,
                'unit' => 'days',
                'icon' => '🔥',
                'category' => 'Consistency',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Achievements Unlocked',
                'description' => 'Special achievements earned',
                'current' => 0,
                'target' => 20,
                'unit' => 'achievements',
                'icon' => '🏆',
                'category' => 'Achievements',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($metrics as $metric) {
            ProgressMetric::firstOrCreate(
                ['name' => $metric['name']],
                $metric
            );
        }
    }
}
