# New User Tier System

## Overview
All new users now start with the **Plastic** tier (the lowest rank) and progress through the ranking system as they gain XP and levels.

## How It Works

### User Registration
1. When a new user registers, the `UserObserver` is triggered
2. A `UserProfile` is automatically created with:
   - `total_xp`: 0
   - `level`: 1
   - `current_level_xp`: 0
   - `xp_for_next_level`: 1000
   - `streak_days`: 0
   - `rank_title`: "Plastic" (lowest tier)

### Progression
- Users earn XP through assignments and courses
- As XP increases, their level increases (1000 XP per level)
- Their rank position updates based on total XP (higher XP = higher rank)
- Their tier automatically updates based on rank position:
  - **Plastic** (Rank 131+) - Entry level
  - **Iron** (Rank 101-130)
  - **Bronze** (Rank 81-100)
  - **Silver** (Rank 61-80)
  - **Gold** (Rank 46-60)
  - **Platinum** (Rank 36-45)
  - **Diamond** (Rank 26-35)
  - **Ascendant** (Rank 16-25)
  - **Immortal** (Rank 6-15)
  - **Radiant** (Rank 1-5) - Top tier

## Files Modified

### app/Observers/UserObserver.php (New)
Automatically creates a UserProfile when a user is created:
```php
UserProfile::create([
    'user_id' => $user->id,
    'total_xp' => 0,
    'level' => 1,
    'current_level_xp' => 0,
    'xp_for_next_level' => 1000,
    'streak_days' => 0,
    'rank_title' => 'Plastic',
]);
```

### app/Providers/AppServiceProvider.php
Registers the UserObserver in the boot method:
```php
User::observe(UserObserver::class);
```

### app/Http/Controllers/DashboardController.php
Updated to create profiles with "Plastic" tier and 1000 XP per level:
```php
'xp_for_next_level' => 1000,
'rank_title' => 'Plastic',
```

### app/Http/Controllers/LeaderboardController.php
Already calculates tier based on rank position, so tier progression is automatic.

## Testing

### Create a New User
1. Go to registration page
2. Register a new account
3. Navigate to leaderboard
4. Verify the user appears at the bottom with "Plastic" tier and 0 XP

### Gain XP
1. Complete assignments/courses to earn XP
2. Visit leaderboard and refresh
3. Verify rank position updates
4. Verify tier changes when crossing tier thresholds

## Database
No new database changes needed. The existing `rank_title` field in `user_profiles` is used to store the tier name for reference.

## Notes
- The tier is calculated dynamically based on rank position
- `rank_title` in the database stores the tier name for quick reference
- The `RankingTierResource` in Filament allows customizing tier ranges and properties
- All changes take effect immediately without requiring additional migrations
