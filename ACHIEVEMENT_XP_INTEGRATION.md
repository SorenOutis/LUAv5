# Achievement XP Integration Guide

## Overview

When students earn achievements/badges, they automatically receive XP that gets recorded in their user profile. This integrates the rewards system with the existing XP tracking system.

---

## How It Works

### Database Flow

```
Student Earns Achievement
        ↓
Achievement Unlocked (pivot table)
        ↓
XP Awarded Service
        ↓
user_profiles.total_xp += achievement.xp_reward
        ↓
User Level Recalculated
        ↓
User Profile Updated
```

### XP Storage

Achievement XP is stored in the `user_profiles` table:

| Field | Type | Description |
|-------|------|-------------|
| total_xp | integer | Total XP earned (achievements + assignments + manual) |
| level | integer | User's level (calculated: total_xp / 100 + 1) |
| current_level_xp | integer | XP progress toward next level (total_xp % 100) |

---

## Implementation

### Method 1: Using AchievementUnlocker Service (Recommended)

```php
use App\Services\AchievementUnlocker;
use App\Models\User;
use App\Models\Achievement;

// Get user and achievement
$user = User::find(1);
$achievement = Achievement::find(3);

// Unlock achievement (attaches to user + awards XP)
$unlocker = app(AchievementUnlocker::class);
$unlocked = $unlocker->unlock($user, $achievement);

if ($unlocked) {
    // Achievement was unlocked and XP awarded
    echo "Achievement unlocked! +{$achievement->xp_reward} XP";
} else {
    // Achievement already unlocked
    echo "Achievement already earned";
}
```

### Method 2: Direct Attachment + Manual XP

```php
use App\Observers\AchievementUnlockObserver;
use App\Models\User;
use App\Models\Achievement;

$user = User::find(1);
$achievement = Achievement::find(3);

// Attach achievement
$user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);

// Manually award XP
AchievementUnlockObserver::awardXPForAchievement($user, $achievement);
```

### Method 3: Manual Implementation

```php
$user = User::find(1);
$achievement = Achievement::find(3);

// Attach achievement
$user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);

// Get or create profile
$profile = $user->profile()->firstOrCreate([], [
    'total_xp' => 0,
    'level' => 1,
    'current_level_xp' => 0,
    'xp_for_next_level' => 1000,
    'rank_title' => 'Beginner',
]);

// Add XP
$newTotalXP = $profile->total_xp + $achievement->xp_reward;

// Update profile
$profile->update([
    'total_xp' => $newTotalXP,
    'level' => max(1, intval($newTotalXP / 100) + 1),
    'current_level_xp' => $newTotalXP % 100,
]);
```

---

## Available Services

### AchievementUnlocker Service

Located: `app/Services/AchievementUnlocker.php`

#### Methods

**unlock($user, $achievement)**
- Unlocks an achievement for a user
- Automatically awards XP
- Returns: bool (true if unlocked, false if already owned)

```php
$unlocker = app(AchievementUnlocker::class);
$unlocked = $unlocker->unlock($user, $achievement);
```

**unlockMultiple($user, $achievements)**
- Unlocks multiple achievements at once
- Returns: array of [achievement_id => unlocked_bool]

```php
$achievements = Achievement::whereIn('id', [1, 2, 3])->get();
$results = $unlocker->unlockMultiple($user, $achievements);

// Results:
// [1 => true, 2 => false, 3 => true]
```

**syncXPForUnlockedAchievement($user, $achievement)**
- Checks if user has achievement and awards XP if needed
- Useful for syncing XP when achievements already exist

```php
$unlocker->syncXPForUnlockedAchievement($user, $achievement);
```

**syncAllAchievementXP($user)**
- Recalculates all achievement XP for a user
- Useful for fixing XP discrepancies

```php
$unlocker->syncAllAchievementXP($user);
```

---

## XP Values

### Current Achievement XP Rewards

| Achievement | XP | Category |
|-------------|-----|----------|
| First Steps | 50 | Learning |
| Quick Learner | 150 | Learning |
| Bookworm | 300 | Learning |
| Scholar | 1000 | Learning |
| On Fire | 200 | Streak |
| Unstoppable | 500 | Streak |
| Legendary Dedication | 2000 | Streak |
| Achievement Hunter | 250 | Milestones |
| Perfect Collector | 750 | Milestones |
| Master of All | 5000 | Milestones |
| Level Up | 100 | Levels |
| Rising Star | 400 | Levels |
| Peak Performance | 3000 | Levels |
| XP Collector | 50 | XP |
| XP Master | 500 | XP |
| XP Legend | 2500 | XP |
| Social Butterfly | 25 | Social |
| Discussion Master | 300 | Social |
| Community Leader | 600 | Social |
| Speedrunner | 400 | Special |
| Perfect Score | 350 | Special |
| Early Bird | 75 | Special |

---

## Level System

### Level Calculation

```php
level = max(1, floor(total_xp / 100) + 1)
```

### Examples

| Total XP | Level | Current Level XP |
|----------|-------|------------------|
| 0 | 1 | 0 |
| 50 | 1 | 50 |
| 100 | 2 | 0 |
| 150 | 2 | 50 |
| 500 | 6 | 0 |
| 1000 | 11 | 0 |
| 1250 | 13 | 50 |

---

## Integration Points

### Auto-Unlock on Actions

You can automatically unlock achievements when users perform actions:

**Example: Unlock achievement when lesson completed**

```php
// In LessonController or Observer
use App\Services\AchievementUnlocker;

public function completeLesson(Lesson $lesson)
{
    // ... complete lesson logic ...

    // Award First Steps achievement on first lesson
    $user = auth()->user();
    $lessonCount = $user->lessonCompletions()->count();
    
    if ($lessonCount === 1) {
        $unlocker = app(AchievementUnlocker::class);
        $achievement = Achievement::where('name', 'First Steps')->first();
        $unlocker->unlock($user, $achievement);
    }
}
```

**Example: Unlock achievement on streak milestone**

```php
// In Streak service/observer
use App\Services\AchievementUnlocker;

if ($streak->current_streak === 7) {
    $unlocker = app(AchievementUnlocker::class);
    $achievement = Achievement::where('name', 'On Fire')->first();
    $unlocker->unlock($user, $achievement);
}
```

---

## Troubleshooting

### XP Not Updating

**Problem:** Achievement unlocked but XP not added

**Solution:** Use the sync method:
```php
$unlocker = app(AchievementUnlocker::class);
$unlocker->syncAllAchievementXP($user);
```

### Duplicate XP

**Problem:** Achievement awarded twice or XP doubled

**Solution:** Use the unlock method which checks if already unlocked:
```php
// Only awards if not already unlocked
$unlocked = $unlocker->unlock($user, $achievement);
if (!$unlocked) {
    echo "Already earned";
}
```

### Wrong Level Calculation

**Problem:** Level not calculated correctly

**Solution:** Recalculate all levels:
```php
// For single user
$profile = $user->profile;
$profile->update([
    'level' => max(1, intval($profile->total_xp / 100) + 1),
    'current_level_xp' => $profile->total_xp % 100,
]);

// For all users
User::all()->each(function ($user) {
    $profile = $user->profile;
    if ($profile) {
        $profile->update([
            'level' => max(1, intval($profile->total_xp / 100) + 1),
            'current_level_xp' => $profile->total_xp % 100,
        ]);
    }
});
```

---

## Testing

### Manual Testing in Tinker

```php
php artisan tinker

// Get user and achievement
$user = User::find(2);
$achievement = Achievement::find(1);

// Show before
echo "Before: " . $user->profile->total_xp . " XP";

// Unlock achievement
$unlocker = app(App\Services\AchievementUnlocker::class);
$unlocker->unlock($user, $achievement);

// Show after
echo "After: " . $user->profile->total_xp . " XP";
echo "Achievement XP was: " . $achievement->xp_reward;
```

### Unit Test Example

```php
public function test_achievement_unlocks_and_awards_xp()
{
    $user = User::factory()->create();
    $achievement = Achievement::first();
    $initialXP = $user->profile->total_xp;

    $unlocker = app(AchievementUnlocker::class);
    $unlocked = $unlocker->unlock($user, $achievement);

    $this->assertTrue($unlocked);
    $this->assertEquals(
        $initialXP + $achievement->xp_reward,
        $user->profile->fresh()->total_xp
    );
}
```

---

## Files

### New Files Created

1. **app/Services/AchievementUnlocker.php**
   - Service class for unlocking achievements and awarding XP
   - Main service to use in your application

2. **app/Observers/AchievementUnlockObserver.php**
   - Observer class with helper methods
   - Static method for direct XP awarding

### Existing Files Used

1. **app/Models/User.php**
   - Has `achievements()` relationship
   - Has `profile()` relationship

2. **app/Models/UserProfile.php**
   - Stores `total_xp`, `level`, `current_level_xp`
   - Updated when achievements earned

3. **app/Models/Achievement.php**
   - Has `xp_reward` field
   - Defines XP value for each achievement

4. **database/migrations/2025_12_01_090600_create_user_profiles_table.php**
   - user_profiles table schema
   - Stores all XP-related data

---

## Summary

✅ **Automatic XP Tracking**
- When achievement unlocked → XP awarded automatically
- XP stored in user_profiles table
- User level recalculated

✅ **Flexible Implementation**
- Use AchievementUnlocker service (recommended)
- Or use static helper methods
- Or implement manually

✅ **Safe Operations**
- Prevents duplicate XP awards
- Preserves manually awarded XP
- Includes XP syncing/recovery methods

✅ **Well Integrated**
- Works with existing XP system
- Follows same patterns as AssignmentSubmissionObserver
- Compatible with level calculations

---

## Next Steps

1. Use `AchievementUnlocker` service when unlocking achievements
2. Hook up auto-unlock on user actions (lessons, streaks, etc.)
3. Monitor XP updates via admin panel
4. Sync XP if needed using service methods

**XP integration is complete and ready to use!**
