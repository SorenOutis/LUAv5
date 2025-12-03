# Award Points Feature

## Overview
A dedicated admin panel page that allows administrators to manually award XP points to users/students. This feature provides an easy way for admins to give bonus points, rewards, or compensation for various activities.

## Location
- **Admin Route**: `/admin/award-points`
- **Navigation**: Sidebar menu under "Award Points"

## Files Created
1. `app/Filament/Resources/AwardPointsResource.php` - Main resource class
2. `app/Filament/Resources/AwardPointsResource/Pages/ListAwardPoints.php` - List page

## Features

### User Display
The main table displays all users/students with the following columns:
- **Name** - User's full name (searchable and sortable)
- **Email** - User's email address (searchable and sortable)
- **Level** - Current level (numeric, sortable)
- **Total XP** - Total experience points (numeric, sortable)
- **Streak** - Current streak in days (numeric, sortable)
- **Joined** - Account creation date (sortable)

Users are sorted by most recent first by default.

### Award Points Action
Click "Award Points" button on any user row to:

1. **Enter Points Amount** - Specify how many XP points to award (minimum 1)
2. **Add Reason (Optional)** - Provide a note explaining why the points are being awarded
3. **Submit** - Award the points to the user

The user's profile is automatically updated with the new XP total.

## How to Use

### Step 1: Access the Page
1. Log in to the admin panel
2. Navigate to "Award Points" in the sidebar menu

### Step 2: Find a User
- Use the search box to find users by name or email
- Sort by any column by clicking the column header
- Scroll through the list if browsing

### Step 3: Award Points
1. Click the "Award Points" button (star icon) on the user's row
2. Enter the number of points to award (e.g., 50, 100, 250)
3. Optionally add a reason (e.g., "Perfect assignment submission", "Perfect attendance")
4. Click "Award" button
5. A success notification will appear confirming the points were awarded

## Technical Details

### Database
The feature updates the `user_profiles` table, specifically the `total_xp` column:
- User's new total XP = current total XP + awarded points

### Model Relationships
- Uses `User` model with `hasOne` relationship to `UserProfile`
- Creates profile if it doesn't exist (fallback)

### Authorization
- Protected by Filament's authentication middleware
- Admin users only (Filament login required)

## Example Scenarios

### Bonus Points for Good Performance
```
User: John Doe
Award: 150 XP
Reason: Completed advanced assignment with excellent code quality
```

### Attendance Bonus
```
User: Jane Smith
Award: 50 XP
Reason: Perfect attendance for the month
```

### Challenge Completion
```
User: Mike Johnson
Award: 200 XP
Reason: Completed special coding challenge ahead of schedule
```

## Integration with Gamification
The awarded XP integrates with your existing gamification system:
- Points count toward level progression
- Updates leaderboard rankings
- Contributes to user's total XP tracking
- Can unlock achievements if configured

## Limitations
- Only updates `total_xp` field
- Does not automatically recalculate levels (if needed, implement level calculation logic)
- No audit log by default (consider adding activity logging if needed)

## Future Enhancements
Consider adding:
1. Audit logging to track who awarded points and when
2. Bulk award functionality for multiple users
3. Points withdrawal/deduction capability
4. Category-based points (bonus, challenge, attendance, etc.)
5. Automatic level recalculation based on XP thresholds
