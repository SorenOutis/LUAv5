<?php

namespace App\Observers;

use App\Models\Achievement;

class AchievementUnlockObserver
{
    /**
     * Handle the Achievement "attached" event (when user unlocks achievement).
     * This observer listens to pivot table attach events.
     */
    public function updated(Achievement $achievement): void
    {
        // Note: This handles updates to achievement definitions
        // For user unlocks, see the helper method below
    }

    /**
     * Helper method to add XP when an achievement is unlocked for a user.
     * Call this method when attaching an achievement to a user.
     * 
     * Usage:
     * $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
     * app(AchievementUnlockObserver::class)->awardXPForAchievement($user, $achievement);
     *
     * Or better yet, use the AchievementUnlocker service:
     * app(AchievementUnlocker::class)->unlock($user, $achievement);
     */
    public static function awardXPForAchievement($user, $achievement): void
    {
        if (!$achievement->xp_reward || $achievement->xp_reward <= 0) {
            return;
        }

        // Get or create user profile
        $profile = $user->profile()->firstOrCreate([], [
            'total_xp' => 0,
            'level' => 1,
            'current_level_xp' => 0,
            'xp_for_next_level' => 1000,
            'streak_days' => 0,
            'rank_title' => 'Beginner',
        ]);

        // Add the XP reward
        $newTotalXP = $profile->total_xp + $achievement->xp_reward;

        // Update profile with new total XP and recalculate level
        $profile->update([
            'total_xp' => $newTotalXP,
            'level' => max(1, intval($newTotalXP / 100) + 1),
            'current_level_xp' => $newTotalXP % 100,
        ]);
    }
}
