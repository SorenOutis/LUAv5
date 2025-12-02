<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Progress', [
            'levelProgress' => [
                'currentLevel' => 18,
                'nextLevel' => 19,
                'currentXP' => 3500,
                'xpForNextLevel' => 5000,
                'totalXPEarned' => 8500,
            ],
            'metrics' => [
                [
                    'id' => 1,
                    'name' => 'Lessons Completed',
                    'description' => 'Track your lesson completion progress',
                    'current' => 45,
                    'target' => 100,
                    'unit' => 'lessons',
                    'icon' => '📚',
                    'category' => 'Learning',
                ],
                [
                    'id' => 2,
                    'name' => 'Quizzes Passed',
                    'description' => 'Number of quizzes you have passed',
                    'current' => 12,
                    'target' => 25,
                    'unit' => 'quizzes',
                    'icon' => '✅',
                    'category' => 'Assessments',
                ],
                [
                    'id' => 3,
                    'name' => 'Courses Completed',
                    'description' => 'Full courses you have finished',
                    'current' => 1,
                    'target' => 5,
                    'unit' => 'courses',
                    'icon' => '🏁',
                    'category' => 'Courses',
                ],
                [
                    'id' => 4,
                    'name' => 'Current Streak',
                    'description' => 'Days in a row of learning',
                    'current' => 12,
                    'target' => 30,
                    'unit' => 'days',
                    'icon' => '🔥',
                    'category' => 'Consistency',
                ],
                [
                    'id' => 5,
                    'name' => 'Achievements Unlocked',
                    'description' => 'Special achievements earned',
                    'current' => 8,
                    'target' => 20,
                    'unit' => 'achievements',
                    'icon' => '⭐',
                    'category' => 'Achievements',
                ],
                [
                    'id' => 6,
                    'name' => 'Coding Hours',
                    'description' => 'Total hours spent coding',
                    'current' => 42,
                    'target' => 100,
                    'unit' => 'hours',
                    'icon' => '⏱️',
                    'category' => 'Time',
                ],
            ],
            'recentMilestones' => [
                [
                    'date' => 'Jan 2, 2024',
                    'title' => 'Reached Level 18',
                    'description' => 'You achieved level 18 through consistent learning!',
                ],
                [
                    'date' => 'Jan 1, 2024',
                    'title' => 'New Year, New Goals',
                    'description' => 'Happy New Year! Your learning journey continues.',
                ],
                [
                    'date' => 'Dec 31, 2023',
                    'title' => 'Course Completed',
                    'description' => 'You completed "HTML & CSS Basics" course with 95% score.',
                ],
                [
                    'date' => 'Dec 28, 2023',
                    'title' => 'Speed Learner Achievement',
                    'description' => 'You completed 5 lessons in one day!',
                ],
                [
                    'date' => 'Dec 25, 2023',
                    'title' => 'Level 10 Reached',
                    'description' => 'Congratulations! You reached level 10.',
                ],
            ],
            'stats' => [
                'totalLessonsCompleted' => 45,
                'totalCoursesEnrolled' => 3,
                'totalLessonsAvailable' => 100,
                'averageCompletionRate' => 70,
            ],
        ]);
    }
}
