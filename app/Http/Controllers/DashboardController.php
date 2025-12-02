<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Calculate total XP from assignment submissions
        $assignmentXP = $user->assignmentSubmissions()
            ->whereNotNull('xp')
            ->sum('xp');

        // Get or create user profile
        $profile = $user->profile()->firstOrCreate([], [
            'total_xp' => 0,
            'level' => 1,
            'current_level_xp' => 0,
            'xp_for_next_level' => 500,
            'streak_days' => 0,
            'rank_title' => 'Beginner',
        ]);

        // Set total_xp to assignment XP (not accumulated)
        $totalXP = $assignmentXP;
        $profile->update(['total_xp' => $totalXP]);

        // Calculate level based on total XP (100 XP per level)
        $level = max(1, intval($totalXP / 100) + 1);
        $currentLevelXP = $totalXP % 100;
        $profile->update([
            'level' => $level,
            'current_level_xp' => $currentLevelXP,
        ]);

        // Get active courses with enrollment progress
        $courses = $user->enrollments()
            ->with('course')
            ->get()
            ->map(fn ($enrollment) => [
                'id' => $enrollment->course->id,
                'name' => $enrollment->course->title,
                'progress' => $enrollment->progress_percentage,
                'completedLessons' => $enrollment->completed_lessons_count,
                'totalLessons' => $enrollment->course->total_lessons,
                'xpEarned' => $enrollment->xp_earned,
                'nextDeadline' => $enrollment->course->updated_at->format('Y-m-d'),
            ]);

        // Get leaderboard (top 5 users by assignment XP)
        $leaderboard = \App\Models\User::with('profile', 'assignmentSubmissions')
            ->get()
            ->map(fn ($u) => [
                'name' => $u->name,
                'assignmentXP' => $u->assignmentSubmissions()->whereNotNull('xp')->sum('xp'),
                'profileXP' => $u->profile?->total_xp ?? 0,
            ])
            ->map(fn ($item) => [
                'name' => $item['name'],
                'xp' => $item['assignmentXP'],
                'level' => max(1, intval($item['assignmentXP'] / 100) + 1),
                'badge' => $this->getLevelBadge(max(1, intval($item['assignmentXP'] / 100) + 1)),
                'isUser' => $item['name'] === $user->name,
            ])
            ->sortByDesc('xp')
            ->take(5)
            ->values()
            ->map(fn ($item, $index) => array_merge($item, ['rank' => $index + 1]));

        // Get unlocked achievements
        $achievements = $user->achievements()
            ->get()
            ->map(fn ($achievement) => [
                'id' => $achievement->id,
                'name' => $achievement->title,
                'description' => $achievement->description,
                'unlocked' => true,
                'icon' => $achievement->icon,
            ]);

        // Get locked achievements (available but not unlocked)
        $allAchievements = \App\Models\Achievement::where('is_active', true)
            ->get()
            ->map(fn ($achievement) => [
                'id' => $achievement->id,
                'name' => $achievement->title,
                'description' => $achievement->description,
                'unlocked' => $user->achievements->pluck('id')->contains($achievement->id),
                'icon' => $achievement->icon,
            ]);

        // Get recent activity
        $recentActivity = $user->lessonCompletions()
            ->with('lesson')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($completion) => [
                'type' => 'lesson',
                'title' => 'Completed: ' . $completion->lesson->title,
                'xp' => $completion->lesson->xp_reward,
                'timestamp' => $completion->completed_at->diffForHumans(),
            ]);

        return Inertia::render('Dashboard', [
            'userStats' => [
                'totalXP' => $totalXP,
                'level' => $level,
                'currentXP' => $currentLevelXP,
                'maxXPForLevel' => 100,
                'rank' => $profile->rank_title,
                'streakDays' => $profile->streak_days,
                'achievements' => $achievements->count(),
            ],
            'courses' => $courses,
            'leaderboard' => $leaderboard,
            'achievements' => $allAchievements,
            'recentActivity' => $recentActivity,
        ]);
    }

    private function getLevelBadge(int $level): string
    {
        return match (true) {
            $level >= 15 => '⭐',
            $level >= 12 => '🔥',
            $level >= 10 => '🚀',
            $level >= 5 => '💪',
            default => '⚡',
        };
    }
}
