# User Management Page Setup

This document outlines the new User Management page that has been integrated into the admin panel and main application.

## What Was Added

### 1. Frontend User Profile Page
**File**: `resources/js/pages/User.vue`

A comprehensive user profile page showing:
- User information (name, email, bio, join date)
- User statistics (XP, level, courses, assignments, achievements, streak)
- Course progress with visual indicators
- Recent achievements with unlocking dates
- Edit profile functionality
- Achievement detail dialogs

**Route**: `/profile`

### 2. Backend User Controller
**File**: `app/Http/Controllers/UserController.php`

Handles:
- `profile()` - Display current user's profile
- `index()` - List all users (admin view)
- `show(User $user)` - Display specific user details

### 3. Enhanced Admin Resource
**File**: `app/Filament/Resources/UserResource.php`

**Admin Panel Features**:
- Manage users with full CRUD operations
- Edit user profile data (bio, XP, level, streak)
- View user statistics in table columns:
  - Name (searchable, sortable)
  - Email (searchable, sortable)
  - Level (numeric, sortable)
  - Total XP (numeric, sortable)
  - Achievements count (badge)
  - Join date (formatted)
- User form includes:
  - Full name
  - Email address
  - Password (required on create)
  - Bio
  - XP amount
  - Level (1-100)
  - Streak days
- Advanced filtering capabilities

**Access**: `http://localhost:8000/admin/users`

### 4. Updated Navigation
**File**: `resources/js/components/AppSidebar.vue`

Added "Profile" link to main navigation menu with User icon, appearing between Dashboard and Assignments.

## Routes Added

```php
// User profile page (authenticated users)
GET /profile -> UserController@profile (route name: profile)
```

## Features

### User Profile Page Features
- **Header Section**: Profile card with user avatar (auto-generated), name, email, and join date
- **Quick Stats**: 5-card layout showing:
  - Total XP (lifetime points)
  - Level (current level)
  - Courses (enrolled count)
  - Assignments (completed count)
  - Streak (consecutive active days)

- **Course Progress**: 
  - Visual progress bars for each enrolled course
  - Lessons completion status
  - Progress percentage

- **Achievement Summary**:
  - Total achievements unlocked
  - Success rate calculation

- **Recent Achievements**:
  - Grid display of up to 5 recent achievements
  - Click to view achievement details
  - Achievement details modal

- **Profile Dialog**:
  - Edit name, email, and bio
  - Save changes functionality

### Admin Panel Features
- **User Management**: Create, read, update, delete users
- **Profile Management**: Edit user profile data directly in admin
- **Search & Filter**: 
  - Search by name or email
  - Sort by any column
  - View status at a glance

- **Bulk Operations**: Ready for bulk user actions
- **Relationships**: Auto-loads user relationships (profile, achievements)

## Data Structure

### User Profile Data
```php
$profile = [
    'id' => int,
    'name' => string,
    'email' => string,
    'avatar' => string|null,
    'bio' => string|null,
    'joinedDate' => string, // formatted date
];
```

### User Statistics
```php
$stats = [
    'totalXP' => int,
    'level' => int,
    'coursesEnrolled' => int,
    'assignmentsCompleted' => int,
    'achievementsUnlocked' => int,
    'currentStreak' => int,
];
```

### Course Progress
```php
$progress = [
    'courseId' => int,
    'courseName' => string,
    'progress' => int, // percentage
    'completedLessons' => int,
    'totalLessons' => int,
];
```

### Achievements
```php
$achievement = [
    'id' => int,
    'name' => string,
    'description' => string,
    'icon' => string,
    'unlockedAt' => string, // formatted date
];
```

## Integration Points

### Models Used
- `User` - Main user model
- `UserProfile` - Profile relationship (if exists)
- `CourseEnrollment` - Enrollment relationship
- `LessonCompletion` - Lesson completion tracking
- `Achievement` - User achievements (many-to-many)

### Required Model Relationships
The User model must have these relationships:

```php
public function profile()
{
    return $this->hasOne(\App\Models\UserProfile::class);
}

public function enrollments()
{
    return $this->hasMany(\App\Models\CourseEnrollment::class);
}

public function lessonCompletions()
{
    return $this->hasMany(\App\Models\LessonCompletion::class);
}

public function achievements()
{
    return $this->belongsToMany(\App\Models\Achievement::class)
        ->withTimestamps()
        ->withPivot('unlocked_at');
}
```

## Access Control

### Frontend
- **Profile Page**: Requires authentication (`auth` middleware)
- **Visible to**: Authenticated users only
- **User can see**: Their own profile

### Admin Panel
- **User Management**: Filament auto-discovery
- **Access URL**: `/admin/users`
- **Visible to**: Admin users only

## Styling

The pages use existing UI components:
- `Card`, `CardContent`, `CardDescription`, `CardHeader`, `CardTitle` - Card layouts
- `Button` - Interactive buttons
- `Dialog`, `DialogContent`, `DialogHeader`, `DialogTitle`, `DialogDescription` - Modals
- `Progress` - Progress bars
- Tailwind CSS - Responsive design with dark mode support

## Customization Guide

### Adding More User Stats
Edit `app/Http/Controllers/UserController.php`:

```php
'stats' => [
    'totalXP' => optional($user->profile)->total_xp ?? 0,
    'newStatField' => $user->newCalculation(),
    // Add more fields here
],
```

Then update the Vue component to display:

```vue
<Card>
    <CardHeader class="pb-2">
        <CardTitle class="text-sm font-medium">New Stat</CardTitle>
    </CardHeader>
    <CardContent>
        <div class="text-2xl font-bold">{{ stats.newStatField }}</div>
    </CardContent>
</Card>
```

### Adding Profile Fields in Admin
Edit `app/Filament/Resources/UserResource.php` form:

```php
TextInput::make('profile.new_field')
    ->label('New Field')
    ->required(),
```

### Hiding Sensitive Data
Edit the User model:

```php
protected $hidden = [
    'password',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'remember_token',
    'sensitive_field', // Add sensitive fields
];
```

## Related Pages

This User management system integrates with existing pages:
- **Dashboard** (`/dashboard`) - Overall user stats
- **Assignments** (`/assignments`) - User assignments
- **Settings** (`/settings/profile`) - Account settings
- **Admin Panel** (`/admin`) - Administration interface

## Testing

To test the new User page:

1. Login as an authenticated user
2. Click "Profile" in the sidebar (or navigate to `/profile`)
3. View your profile information and statistics
4. Click "Edit Profile" to update your information
5. Click on achievements to view details

To test the Admin panel:

1. Login as an admin user
2. Navigate to `/admin/users`
3. View the list of all users
4. Click on a user to edit their details
5. Update user information including profile data
6. Filter and search users

## Migration Notes

If you need to update the User model or create a UserProfile model migration:

```bash
php artisan make:migration create_user_profiles_table --create=user_profiles
```

Then add the required fields in the migration:

```php
public function up(): void
{
    Schema::create('user_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->text('bio')->nullable();
        $table->integer('total_xp')->default(0);
        $table->integer('level')->default(1);
        $table->integer('streak_days')->default(0);
        $table->timestamps();
    });
}
```

## Support

For issues or questions:
1. Check that all model relationships are properly defined
2. Ensure UserProfile model exists (or update User model accordingly)
3. Verify that achievement relationship uses pivot table with `unlocked_at` timestamp
4. Check Laravel and Filament documentation for version-specific features
