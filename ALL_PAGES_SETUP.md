# Complete User Pages Setup Documentation

This document covers all 8 user-facing pages that have been added to the application.

## Pages Overview

### 1. **User Profile** (`/profile`)
Displays the authenticated user's personal profile information and learning statistics.

**Features:**
- User info card with avatar initial, name, email, join date
- Quick stats grid: Total XP, Level, Courses, Assignments, Streak
- Course progress tracking with visual bars
- Recent achievements display
- Edit profile dialog
- Member info sidebar
- Achievement history with dates

**Components:**
- Vue page: `resources/js/pages/User.vue`
- Controller: `app/Http/Controllers/UserController.php`
- Route: `/profile` (GET)

---

### 2. **Courses** (`/courses`)
Browse and manage enrolled courses with progress tracking.

**Features:**
- Stats dashboard: Enrolled, Completed, Average Progress, Total XP
- Three filter tabs: Enrolled, Completed, Available
- Course cards with:
  - Course name, instructor, description
  - Difficulty badge (Beginner/Intermediate/Advanced)
  - Progress bar and completion percentage
  - Lessons completed count
  - XP earned
  - Category and next deadline
  - Action buttons
- Responsive grid layout (1-3 columns)
- Empty state messaging

**Components:**
- Vue page: `resources/js/pages/Courses.vue`
- Controller: `app/Http/Controllers/CoursesController.php`
- Route: `/courses` (GET)

**Data Structure:**
```php
Course {
    id: int,
    name: string,
    description: string,
    instructor: string,
    progress: int (0-100),
    completedLessons: int,
    totalLessons: int,
    xpEarned: int,
    nextDeadline: string,
    category: string,
    difficulty: 'Beginner' | 'Intermediate' | 'Advanced'
}
```

---

### 3. **Quests** (`/quests`)
Manage and complete time-limited learning quests for bonus rewards.

**Features:**
- Stats cards: Active, Completed, Total Rewards, Streak Days
- Three filter tabs: Active, Completed, Available
- Quest cards with:
  - Quest title and objective
  - Difficulty level (Easy/Medium/Hard/Legendary) with icon
  - Description
  - Reward XP value
  - Progress bar (for active quests)
  - Days remaining
  - Status indicators
  - Action buttons
- Stacked card layout with full descriptions
- Empty state handling

**Components:**
- Vue page: `resources/js/pages/Quests.vue`
- Controller: `app/Http/Controllers/QuestsController.php`
- Route: `/quests` (GET)

**Data Structure:**
```php
Quest {
    id: int,
    title: string,
    description: string,
    objective: string,
    reward: int,
    difficulty: 'Easy' | 'Medium' | 'Hard' | 'Legendary',
    daysLeft: int,
    progress: int (0-100),
    status: 'active' | 'completed' | 'failed' | 'available'
}
```

---

### 4. **Leaderboard** (`/leaderboard`)
Competitive ranking system showing top performers.

**Features:**
- User rank card: Your rank, total users, XP to next rank
- Filter tabs: By XP, Level, Streak, Achievements
- Top 3 podium display:
  - 1st place with trophy emoji and highlight
  - 2nd and 3rd places with medal emojis
  - Stats cards for each (Level, Streak)
- Full leaderboard table (rank 4+):
  - Rank badge (📊)
  - User name and level
  - XP/Level/Streak/Achievements based on filter
  - View profile buttons
  - Highlight for current user
- Current user highlight across all sections

**Components:**
- Vue page: `resources/js/pages/Leaderboard.vue`
- Controller: `app/Http/Controllers/LeaderboardController.php`
- Route: `/leaderboard` (GET)

**Data Structure:**
```php
LeaderboardEntry {
    rank: int,
    userId: int,
    name: string,
    xp: int,
    level: int,
    streakDays: int,
    achievements: int,
    isCurrentUser?: boolean
}
```

---

### 5. **Achievements** (`/achievements`)
Showcase all unlocked and locked achievements.

**Features:**
- Stats dashboard: Unlocked, Total, Completion %, XP from achievements
- Filter tabs: All, Unlocked, Locked
- Achievement grid (4 columns):
  - Large emoji icon
  - Achievement name and description
  - Status indicator (Unlocked date or Locked)
  - Difficulty badge (for locked)
- Achievement detail modal with:
  - Icon, name, description
  - Difficulty, XP reward, category, rarity
  - Unlock date or "Keep working" message
  - Share/View button
- Color coding by difficulty
- Grayscale effect for locked achievements
- Rarity percentage display

**Components:**
- Vue page: `resources/js/pages/Achievements.vue`
- Controller: `app/Http/Controllers/AchievementsController.php`
- Route: `/achievements` (GET)

**Data Structure:**
```php
Achievement {
    id: int,
    name: string,
    description: string,
    icon: string,
    category: string,
    difficulty: 'Easy' | 'Medium' | 'Hard' | 'Legendary',
    unlocked: boolean,
    unlockedAt?: string,
    xpReward: int,
    rarity: int (percentage)
}
```

---

### 6. **Progress** (`/progress`)
Comprehensive learning progress tracking and analytics.

**Features:**
- Level progress card:
  - Current level, next level, total XP
  - Experience progress bar to next level
  - Percentage to next level
- Stats grid: Lessons, Courses, Completion %, Learning Time
- Progress metrics section:
  - Category filtering (All, Learning, Assessments, etc.)
  - Metric cards with:
    - Icon, name, description
    - Current/Target progress
    - Progress bar
    - Percentage complete
- Recent milestones timeline:
  - Date, achievement title, description
  - Checkmark indicator
- Call-to-action card: Continue Learning button

**Components:**
- Vue page: `resources/js/pages/Progress.vue`
- Controller: `app/Http/Controllers/ProgressController.php`
- Route: `/progress` (GET)

**Data Structure:**
```php
LevelProgress {
    currentLevel: int,
    nextLevel: int,
    currentXP: int,
    xpForNextLevel: int,
    totalXPEarned: int
}

ProgressMetric {
    id: int,
    name: string,
    description: string,
    current: int,
    target: int,
    unit: string,
    icon: string,
    category: string
}

Milestone {
    date: string,
    title: string,
    description: string
}
```

---

### 7. **Rewards** (`/rewards`)
Display earned rewards, badges, and certificates.

**Features:**
- Stats cards: Total Rewards, Badges, Reward XP, Next Milestone
- Badges section:
  - Grid display (5 columns)
  - Large icons
  - Rarity color coding (Common/Uncommon/Rare/Legendary)
  - Name, description, rarity label
  - Earned date
- Recent rewards list:
  - Icon, name, description
  - Type badge (badge/certificate/unlock/bonus)
  - Earned date
  - XP value display
  - Hover effects
- Reward milestones progression:
  - Completed milestones with checkmark
  - In-progress milestones with progress text
  - Future milestones grayed out
- Share rewards section with buttons

**Components:**
- Vue page: `resources/js/pages/Rewards.vue`
- Controller: `app/Http/Controllers/RewardsController.php`
- Route: `/rewards` (GET)

**Data Structure:**
```php
Reward {
    id: int,
    name: string,
    description: string,
    icon: string,
    type: 'badge' | 'certificate' | 'unlock' | 'bonus',
    earnedAt: string,
    xpValue: int
}

Badge {
    id: int,
    name: string,
    description: string,
    icon: string,
    rarity: 'common' | 'uncommon' | 'rare' | 'legendary',
    earnedAt: string
}
```

---

### 8. **Messages** (`/messages`)
Unified messaging and notification center.

**Features:**
- Stats cards: Unread count, Total, Last message time, Archive
- Filter tabs: All, Unread, Notifications, Announcements
- Message list with:
  - Type icon (🔔 notification, 💬 message, 📢 announcement, ⚙️ system)
  - Sender name and subject
  - Preview text (truncated)
  - Type badge (colored)
  - Timestamp
  - Unread indicator dot
  - Unread messages highlighted with accent background
- Message detail modal with:
  - Full sender info with icon
  - Subject line
  - Full message body
  - Reply, Archive, Delete buttons
- Responsive layout for mobile
- Empty state messaging

**Components:**
- Vue page: `resources/js/pages/Messages.vue`
- Controller: `app/Http/Controllers/MessagesController.php`
- Route: `/messages` (GET)

**Data Structure:**
```php
Message {
    id: int,
    from: string,
    fromAvatar: string,
    subject: string,
    preview: string,
    body: string,
    timestamp: string,
    isRead: boolean,
    type: 'notification' | 'message' | 'announcement' | 'system'
}
```

---

## Route Summary

All routes are protected with `auth` and `verified` middleware.

```php
GET /profile      -> UserController@profile
GET /courses      -> CoursesController@index
GET /quests       -> QuestsController@index
GET /leaderboard  -> LeaderboardController@index
GET /achievements -> AchievementsController@index
GET /progress     -> ProgressController@index
GET /rewards      -> RewardsController@index
GET /messages     -> MessagesController@index
GET /assignments  -> AssignmentController@index
```

---

## Navigation Integration

All pages are integrated into the sidebar (`AppSidebar.vue`) with icons:

| Page | Icon | Order |
|------|------|-------|
| Dashboard | 📊 LayoutGrid | 1 |
| Courses | 📚 BookOpen | 2 |
| Quests | 🎯 Target | 3 |
| Leaderboard | 🏆 Trophy | 4 |
| Achievements | ⭐ Star | 5 |
| Progress | 📈 TrendingUp | 6 |
| Rewards | 🎁 Gift | 7 |
| Messages | 💬 MessageSquare | 8 |
| Assignments | 📄 FileText | 9 |
| Profile | 👤 User | 10 |

---

## Styling & Components

All pages use:
- **Layouts**: `AppLayout.vue` with breadcrumb support
- **UI Components**:
  - `Card`, `CardContent`, `CardDescription`, `CardHeader`, `CardTitle`
  - `Button` (variants: default, outline, ghost, sm, lg)
  - `Progress` (visual progress bars)
  - `Dialog`, `DialogContent`, `DialogHeader`, `DialogTitle`, `DialogDescription`
- **Icons**: lucide-vue-next for navigation
- **Styling**: Tailwind CSS with dark mode support
- **Responsive**: Mobile, tablet, desktop layouts

---

## Sample Data

Each controller includes sample/mock data for development:
- Controllers return Inertia-rendered Vue components
- Data is structured to match the component prop interfaces
- Easily replaceable with real database queries

**Example Controller Pattern:**
```php
public function index()
{
    $user = Auth::user();
    
    return Inertia::render('PageName', [
        'data' => [...],
        'stats' => [...],
    ]);
}
```

---

## Customization Guide

### Adding a New Metric to Progress Page
Edit `ProgressController@index`:
```php
'metrics' => [
    [
        'id' => 7,
        'name' => 'New Metric',
        'description' => 'Description',
        'current' => 10,
        'target' => 50,
        'unit' => 'items',
        'icon' => '📊',
        'category' => 'Category Name',
    ],
    // ... more metrics
],
```

### Changing Filter Options
Example for Courses page, modify `Courses.vue`:
```vue
const selectedFilter = ref<'enrolled' | 'completed' | 'available' | 'archived'>('enrolled');
```

### Adding Real Database Data
Replace mock data in controllers with actual queries:
```php
'enrolledCourses' => $user->enrollments()
    ->with('course')
    ->get()
    ->map(fn($e) => [
        'id' => $e->course->id,
        'name' => $e->course->name,
        // ... map to expected structure
    ]),
```

---

## Performance Considerations

1. **Pagination**: Leaderboard and Messages should paginate large datasets
2. **Lazy Loading**: Achievement grids can use virtual scrolling for large lists
3. **Caching**: Leaderboard data can be cached (refresh every 5 mins)
4. **N+1 Queries**: Use eager loading with `with()` for relationships

---

## Future Enhancements

1. **Real-time Updates**: WebSocket notifications for messages
2. **Filtering & Search**: Advanced filters on all pages
3. **Export**: Export achievements, progress, certificates
4. **Social Features**: Share achievements, follow users
5. **Analytics**: Charts and insights on progress page
6. **Mobile App**: Native mobile versions
7. **Gamification**: More quest types, daily challenges
8. **Personalization**: Customizable dashboard, preferences

---

## Testing

Test each page by:
1. Navigate to route from sidebar or directly via URL
2. Verify all data displays correctly
3. Test filter functionality
4. Test modal/dialog interactions
5. Test responsive design on mobile
6. Verify dark mode rendering
7. Check loading states if real data source is added

---

## Browser Support

All pages support:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

Dark mode support via `prefers-color-scheme` CSS media query.

---

## API Integration Points

When connecting to real APIs, update controllers to query:

**Courses**: `/api/users/{id}/courses`
**Quests**: `/api/users/{id}/quests`
**Leaderboard**: `/api/leaderboard`
**Achievements**: `/api/users/{id}/achievements`
**Progress**: `/api/users/{id}/progress`
**Rewards**: `/api/users/{id}/rewards`
**Messages**: `/api/users/{id}/messages`

---

## Files Summary

**Vue Pages**: 8 files
- `resources/js/pages/User.vue`
- `resources/js/pages/Courses.vue`
- `resources/js/pages/Quests.vue`
- `resources/js/pages/Leaderboard.vue`
- `resources/js/pages/Achievements.vue`
- `resources/js/pages/Progress.vue`
- `resources/js/pages/Rewards.vue`
- `resources/js/pages/Messages.vue`

**Controllers**: 8 files
- `app/Http/Controllers/UserController.php`
- `app/Http/Controllers/CoursesController.php`
- `app/Http/Controllers/QuestsController.php`
- `app/Http/Controllers/LeaderboardController.php`
- `app/Http/Controllers/AchievementsController.php`
- `app/Http/Controllers/ProgressController.php`
- `app/Http/Controllers/RewardsController.php`
- `app/Http/Controllers/MessagesController.php`

**Routes**: 1 file (updated)
- `routes/web.php` (8 new routes added)

**Navigation**: 1 file (updated)
- `resources/js/components/AppSidebar.vue`

**Total New Files**: 16
**Total Modified Files**: 2
