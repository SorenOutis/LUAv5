# Leaderboard Ranking System Setup

## Overview
Implemented a Valorant-inspired ranking tier system with a customizable admin panel. The leaderboard displays users ranked by XP with visual tier badges and information about levels and streaks.

## Default Ranking Tiers (Customizable)

1. **Radiant** 👑 (Rank 1-5) - #FFD700 (Gold)
2. **Immortal** 💎 (Rank 6-15) - #FF1493 (Deep Pink)
3. **Ascendant** ⭐ (Rank 16-25) - #4169E1 (Royal Blue)
4. **Diamond** 💠 (Rank 26-35) - #00CED1 (Dark Turquoise)
5. **Platinum** 🥈 (Rank 36-45) - #A9A9A9 (Dark Gray)
6. **Gold** 🥇 (Rank 46-60) - #FFD700 (Gold)
7. **Silver** 🔶 (Rank 61-80) - #C0C0C0 (Silver)
8. **Bronze** 🥉 (Rank 81-100) - #CD7F32 (Copper)
9. **Iron** ⚙️ (Rank 101-130) - #696969 (Dim Gray)
10. **Plastic** 📦 (Rank 131+) - #A0A0A0 (Gray)

## Database Changes

### New Table: `ranking_tiers`
```sql
- id (Primary Key)
- name (string, unique) - Tier name
- icon (string) - Emoji icon
- color (string) - Hex color code
- min_rank (integer) - Minimum rank position
- max_rank (integer, nullable) - Maximum rank position (null = no limit)
- description (text, nullable) - Tier description
- sort_order (integer) - Display order
- timestamps
```

## New Files Created

### Models
- `app/Models/RankingTier.php` - Model for ranking tiers

### Filament Admin Panel
- `app/Filament/Resources/RankingTierResource.php` - Admin resource
- `app/Filament/Resources/RankingTierResource/Pages/ListRankingTiers.php`
- `app/Filament/Resources/RankingTierResource/Pages/CreateRankingTier.php`
- `app/Filament/Resources/RankingTierResource/Pages/EditRankingTier.php`

### Database
- Migration: `database/migrations/2025_12_03_014047_create_ranking_tiers_table.php`
- Seeder: `database/seeders/RankingTierSeeder.php` - Seeds default tiers

### Frontend
- Updated: `resources/js/pages/Leaderboard.vue`

### Controllers
- Updated: `app/Http/Controllers/LeaderboardController.php` - Now fetches real user data

## Features Implemented

### User Leaderboard Page
- **Your Rank Card**: Displays current rank, tier with icon and color, total XP, and XP needed for next level
- **Top 3 Podium**: Visual display of top 3 users with their tier information
- **Filter Tabs**: Sort by XP, Level, Streak, or Achievements
- **Rankings Table**: Shows all users with:
  - Rank number
  - Tier icon and name (color-coded)
  - User name
  - Level and streak days
  - Total XP
  - View button for user profiles

### Pagination
- Initially shows 20 users max
- Scrollable list with "Load More" button
- Max height container with smooth scrolling

### Admin Panel
Access via Filament admin panel at `/admin/ranking-tiers`

**Manage Tiers:**
- Create new ranking tiers
- Edit existing tiers (name, icon, color, rank range, description)
- Delete tiers
- Reorder tiers via drag-and-drop
- Set custom rank ranges per tier

**Fields:**
- Tier Name (e.g., "Radiant")
- Emoji Icon
- Color Picker
- Description (Rich Text)
- Minimum Rank Position
- Maximum Rank Position
- Sort Order

## How It Works

1. **Ranking Calculation**: Users are ranked by total XP in descending order
2. **Tier Assignment**: Each user's tier is determined by their rank position and the configured tier ranges
3. **Customization**: Admin can modify tiers, ranges, colors, and icons without code changes
4. **Real-time Updates**: Leaderboard reflects actual user data from `user_profiles` table

## Integration with Existing Systems

- Uses existing `users` table
- Uses existing `user_profiles` table (total_xp, level, streak_days, rank_title)
- Uses existing `achievements` relationship for achievement count
- Compatible with current XP/gamification system

## Usage

### View Leaderboard
1. Navigate to the Leaderboard page from the main menu
2. Filter by XP, Level, Streak, or Achievements
3. View your rank and tier
4. Scroll or click "Load More" to see additional players

### Customize Ranking Tiers
1. Login to Filament Admin Panel
2. Navigate to "Ranking Tiers"
3. Create, edit, or delete tiers
4. Changes apply immediately to all users

## API Response Structure

```php
[
    'leaderboard' => [
        [
            'rank' => 1,
            'userId' => 1,
            'name' => 'User Name',
            'xp' => 15750,
            'level' => 25,
            'streakDays' => 42,
            'achievements' => 18,
            'isCurrentUser' => false,
            'rankTier' => [
                'id' => 1,
                'name' => 'Radiant',
                'icon' => '👑',
                'color' => '#FFD700',
                'minRank' => 1,
                'maxRank' => 5,
            ]
        ]
    ],
    'leaderboardTotal' => 1247,
    'userRank' => [...],
    'stats' => [
        'totalUsers' => 1247,
        'topXP' => 15750,
        'myRank' => 5,
        'xpToNextRank' => 4300,
    ]
]
```

## Migration & Seeding

Run migrations:
```bash
php artisan migrate
```

Seed default tiers:
```bash
php artisan db:seed --class=RankingTierSeeder
```

Or include in main DatabaseSeeder:
```php
$this->call(RankingTierSeeder::class);
```

## Notes

- Tier colors are applied dynamically to UI elements
- View buttons on leaderboard are ready for user profile integration
- Leaderboard respects user's current filter selection when loading more
- Maximum 20 users shown initially to maintain performance
