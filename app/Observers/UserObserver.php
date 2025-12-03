<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserProfile;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Create a user profile with initial "Plastic" tier (lowest rank)
        UserProfile::create([
            'user_id' => $user->id,
            'total_xp' => 0,
            'level' => 1,
            'current_level_xp' => 0,
            'xp_for_next_level' => 1000,
            'streak_days' => 0,
            'rank_title' => 'Plastic',
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
