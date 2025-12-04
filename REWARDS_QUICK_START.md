# Rewards System Quick Start

## What's New

The Rewards page now displays **real user data** with dynamic badges, achievements, and milestones.

## Quick Setup

```bash
# Run migrations (if not already done)
php artisan migrate

# Seed database with 25 achievements + test user
php artisan db:seed
```

## Access Points

### For Students
- **URL**: `/dashboard/rewards`
- **Shows**: All badges (locked and unlocked), recent awards, milestone progress

### For Admins
- **URL**: `/admin/rewards` (or click "Rewards" under "Gamification" in admin sidebar)
- **Shows**: List of all rewards with manage options

## What's Dynamic

### 1. Badges Section
- ✅ Shows ALL 25 achievements
- ✅ Locked badges: Grayed out with 🔒 icon
- ✅ Unlocked badges: Full color with earned date
- ✅ Sorted: Unlocked first, locked last
- ✅ Shows XP value for each badge

### 2. Recent Rewards
- ✅ Shows last 10 achievements earned by current user
- ✅ Sorted by unlock date (newest first)
- ✅ Shows earned date for each reward
- ✅ Updates when new achievements are unlocked

### 3. Reward Milestones
- ✅ Tracks: 1, 5, 10, 25 achievements
- ✅ Shows progress for each milestone
- ✅ Updates in real-time based on user data
- ✅ Color-coded (earned = accent color, locked = gray)

## Admin Features

### Create New Reward
1. Go to Admin > Rewards
2. Click "Create"
3. Fill in:
   - Name (e.g., "Speed Demon")
   - Description
   - Icon (emoji)
   - Category (Learning, Streak, Milestones, Levels, XP, Social, Special)
   - Difficulty (Easy, Medium, Hard, Legendary)
   - XP Reward amount
   - Toggle Active status
4. Save

### Edit Reward
1. Go to Admin > Rewards
2. Click on any reward name
3. Update fields
4. Save

### Delete Reward
1. Go to Admin > Rewards
2. Click on any reward
3. Click Delete button
4. Confirm

## Unlock Achievements (Developer)

### Via Artisan Tinker
```php
php artisan tinker

$user = User::find(1);
$achievement = Achievement::find(1);
$user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
```

### Via Code
```php
use App\Models\User;
use App\Models\Achievement;

$user = User::find(1);
$achievement = Achievement::find(1);

// Unlock
$user->achievements()->attach($achievement->id, [
    'unlocked_at' => now(),
]);

// Lock
$user->achievements()->detach($achievement->id);
```

## Sample Test Data

| Item | Value |
|------|-------|
| Admin Email | admin@example.com |
| Admin Password | password |
| Test User Email | test@example.com |
| Test User Password | password |
| Test User Achievements | 5 random unlocked |

## Key Files Modified

| File | Purpose |
|------|---------|
| `RewardsController.php` | Backend logic - fetches dynamic data |
| `Rewards.vue` | Frontend - displays badges and milestones |
| `DatabaseSeeder.php` | Added AchievementSeeder |
| `UserSeeder.php` | Added sample achievements for test user |

## Key Files Created

| File | Purpose |
|------|---------|
| `RewardResource.php` | Admin CRUD interface |
| `ListRewards.php` | List page |
| `CreateReward.php` | Create page |
| `EditReward.php` | Edit/delete page |

## Common Tasks

### Add Achievement to All Users
```php
$achievement = Achievement::find(1);
User::all()->each(fn ($user) => 
    $user->achievements()->attach($achievement->id, ['unlocked_at' => now()])
);
```

### Remove Achievement from User
```php
$user = User::find(1);
$user->achievements()->detach(1); // Remove achievement #1
```

### Change Achievement Status
```php
$achievement = Achievement::find(1);
$achievement->update(['is_active' => false]); // Hide from display
```

### Get User's Achievements
```php
$user = User::find(1);
$achievements = $user->achievements()->get();
$unlockedCount = $user->achievements()->count();
```

## Features at a Glance

✅ Dynamic badge display based on database  
✅ Locked/unlocked badge states  
✅ Admin panel for managing achievements  
✅ 25 pre-seeded achievements  
✅ Real-time milestone tracking  
✅ User-specific recent rewards  
✅ Rarity levels (Easy/Medium/Hard/Legendary)  
✅ 7 achievement categories  
✅ XP rewards per achievement  
✅ Active/inactive toggle  

## Need Help?

See `REWARDS_SYSTEM_SETUP.md` for detailed documentation.

---

**System Status**: ✅ Ready to Use
