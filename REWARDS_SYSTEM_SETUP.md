# Rewards System Setup

## Overview
The Rewards page is now fully dynamic, displaying badges and achievements based on actual user data from the database.

## What's Been Implemented

### 1. Database Seeders
- **AchievementSeeder**: Creates 25 different achievements across 7 categories:
  - Learning (4 achievements)
  - Streak (3 achievements)
  - Milestones (3 achievements)
  - Levels (3 achievements)
  - XP (3 achievements)
  - Social (3 achievements)
  - Special (3 achievements)

- **UserSeeder**: Updated to attach sample achievements to test users (5 random achievements with random unlock dates)

### 2. Backend Updates

#### RewardsController (`app/Http/Controllers/RewardsController.php`)
- Dynamically fetches all active achievements
- Gets user's unlocked achievements with timestamps
- Builds badges with locked/unlocked status
- Calculates stats (total badges, XP earned, next milestone)
- Shows recent rewards sorted by unlock date

**Key Methods:**
- Queries all active achievements
- Uses pivot data to show unlock dates
- Calculates progress percentage toward next milestone
- Returns only last 10 unlocked achievements for recent rewards

### 3. Frontend Updates

#### Rewards.vue Component
- **Badge Interface**: Extended with `isUnlocked`, `earnedAt`, `xpReward`, and `category` fields
- **Visual States**:
  - Unlocked: Full color with rarity background
  - Locked: Grayed out (50% opacity) with lock icon 🔒
- **Recent Rewards**: Dynamically shows latest earned achievements
- **Milestone Tracking**: Dynamic milestones (1, 5, 10, 25 achievements) with progress

### 4. Admin Panel

#### RewardResource (`app/Filament/Resources/RewardResource.php`)
New admin resource for managing rewards/achievements:

**Features:**
- Create, Read, Update, Delete achievements
- Form sections:
  - Reward Information (name, description, icon)
  - Configuration (category, difficulty/rarity, XP reward)
  - Status (active toggle)
- Table columns with colors and badges
- Filters by category and difficulty
- Sortable by name, category, difficulty, and XP

**Pages:**
- `ListRewards.php`: List all rewards with create button
- `CreateReward.php`: Create new reward
- `EditReward.php`: Edit existing reward with delete action

## Database Structure

### achievements table
```sql
- id (primary key)
- name (string)
- description (longText, nullable)
- icon (string, default: '⭐')
- category (string)
- difficulty (enum: Easy, Medium, Hard, Legendary)
- xp_reward (integer)
- is_active (boolean, default: true)
- timestamps
```

### achievement_user pivot table
```sql
- id (primary key)
- user_id (foreign key)
- achievement_id (foreign key)
- unlocked_at (timestamp, nullable)
- timestamps
- unique: [user_id, achievement_id]
```

## Running Seeders

To populate the database with achievements and test user data:

```bash
php artisan migrate
php artisan db:seed
```

Or seed only achievements:
```bash
php artisan db:seed --class=AchievementSeeder
```

## User Achievement Management

To manually attach achievements to users (PHP code):

```php
$user = User::find(1);
$achievement = Achievement::find(1);

// Unlock achievement
$user->achievements()->attach($achievement->id, [
    'unlocked_at' => now(),
]);

// Or with a specific date
$user->achievements()->attach($achievement->id, [
    'unlocked_at' => now()->subDays(5),
]);

// Detach achievement
$user->achievements()->detach($achievement->id);
```

## Features

### For Students/Users
- View all available badges (locked and unlocked)
- Locked badges shown grayed out with lock icon
- See unlock dates for earned achievements
- View XP value for each badge
- Track progress toward milestones
- Recent rewards section shows 10 most recent unlocks

### For Admins
- Manage achievements via Filament admin panel
- Create new achievement types
- Edit existing achievements
- Set difficulty/rarity levels
- Configure XP rewards
- Enable/disable achievements
- Filter by category or difficulty

## Badge Categories

1. **Learning**: Achievements for course and lesson completion
2. **Streak**: Achievements for maintaining learning streaks
3. **Milestones**: Achievements for collecting other achievements
4. **Levels**: Achievements for reaching certain levels
5. **XP**: Achievements for earning XP amounts
6. **Social**: Achievements for community interaction
7. **Special**: Unique and challenging achievements

## Rarity Levels

- **Easy** (Green): Basic achievements
- **Medium** (Blue): Intermediate challenges
- **Hard** (Orange): Difficult challenges
- **Legendary** (Purple): Very rare achievements

## Navigation

- **User Page**: `/dashboard/rewards` - Student view of badges and rewards
- **Admin Page**: `/admin/rewards` - Manage achievements and badges
- **Admin List**: Click "Rewards" in Gamification section of admin sidebar

## Default Test Data

When running seeders, you'll have:
- 25 achievements across all categories
- Admin user: admin@example.com / password
- Test user: test@example.com / password (with 5 sample achievements unlocked)

## Next Steps (Optional Enhancements)

1. Add achievement unlock logic to other systems (lesson completion, streak tracking, etc.)
2. Create achievement unlock events/listeners
3. Add achievement notifications when users unlock badges
4. Create achievement badges PDF export
5. Add social sharing for achievements
6. Create achievement progression guides
7. Add achievement requirements editor in admin panel
