# Quick Reference Guide

## 🚀 Quick Start

### View All Pages
Visit these URLs (must be logged in):
- `http://localhost:8000/dashboard` - Main dashboard
- `http://localhost:8000/courses` - Course management
- `http://localhost:8000/quests` - Quest tracking
- `http://localhost:8000/leaderboard` - Rankings
- `http://localhost:8000/achievements` - Badges & achievements
- `http://localhost:8000/progress` - Learning analytics
- `http://localhost:8000/rewards` - Earned rewards
- `http://localhost:8000/messages` - Notifications
- `http://localhost:8000/profile` - User profile

### Development Server
```bash
php artisan serve
npm run dev
```

### Login Credentials
- Email: `admin@example.com`
- Password: `password`

---

## 📁 File Locations

### Vue Pages
```
resources/js/pages/
├── User.vue
├── Courses.vue
├── Quests.vue
├── Leaderboard.vue
├── Achievements.vue
├── Progress.vue
├── Rewards.vue
└── Messages.vue
```

### Controllers
```
app/Http/Controllers/
├── UserController.php
├── CoursesController.php
├── QuestsController.php
├── LeaderboardController.php
├── AchievementsController.php
├── ProgressController.php
├── RewardsController.php
└── MessagesController.php
```

### Routes
```
routes/web.php
```

### Navigation
```
resources/js/components/AppSidebar.vue
```

---

## 🔧 Common Tasks

### Add a New Page
1. Create Vue file: `resources/js/pages/PageName.vue`
2. Create controller: `app/Http/Controllers/PageNameController.php`
3. Add route in `routes/web.php`:
   ```php
   Route::get('page-name', [PageNameController::class, 'index'])
       ->middleware(['auth', 'verified'])
       ->name('page-name');
   ```
4. Add navigation in `AppSidebar.vue`:
   ```js
   {
       title: 'Page Name',
       href: '/page-name',
       icon: IconName,
   }
   ```

### Connect to Database
In controller, replace sample data:
```php
// Before (sample data)
return Inertia::render('PageName', [
    'items' => [
        ['id' => 1, 'name' => 'Sample'],
    ],
]);

// After (database query)
return Inertia::render('PageName', [
    'items' => $user->items()
        ->with('relationships')
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
        ]),
]);
```

### Add a Filter
In Vue component:
```js
const selectedFilter = ref<'all' | 'active' | 'archived'>('all');

const getFilteredItems = () => {
    if (selectedFilter.value === 'active') {
        return items.filter(i => i.status === 'active');
    }
    return items;
};
```

### Add a Modal
```vue
<Dialog :open="!!selectedItem" @update:open="(open) => !open && (selectedItem = null)">
    <DialogContent>
        <DialogHeader>
            <DialogTitle>Item Details</DialogTitle>
        </DialogHeader>
        <!-- Modal content here -->
    </DialogContent>
</Dialog>
```

---

## 🎨 Component Library

### Available Components
```js
// Buttons
<Button>Default</Button>
<Button variant="outline">Outline</Button>
<Button variant="ghost">Ghost</Button>
<Button size="sm">Small</Button>
<Button size="lg">Large</Button>

// Cards
<Card>
    <CardHeader>
        <CardTitle>Title</CardTitle>
        <CardDescription>Subtitle</CardDescription>
    </CardHeader>
    <CardContent>Content</CardContent>
</Card>

// Progress
<Progress :value="75" />

// Dialog
<Dialog>
    <DialogContent>
        <DialogHeader>
            <DialogTitle>Title</DialogTitle>
            <DialogDescription>Description</DialogDescription>
        </DialogHeader>
        Content
    </DialogContent>
</Dialog>
```

### Icons
```js
import { 
    LayoutGrid, BookOpen, Target, Trophy, Star, 
    TrendingUp, Gift, MessageSquare, FileText, User 
} from 'lucide-vue-next'
```

---

## 📊 Sample Data Patterns

### Controller Return Pattern
```php
return Inertia::render('PageName', [
    'items' => [...],
    'stats' => [...],
    'filters' => [...],
]);
```

### Data Structure Example
```php
'courses' => [
    [
        'id' => 1,
        'name' => 'Course Name',
        'progress' => 75,
        'xpEarned' => 500,
        'completedLessons' => 15,
        'totalLessons' => 20,
    ],
]
```

---

## 🎯 Page Feature Quick Reference

| Page | Key Features | Filter Count | Modal |
|------|--------------|-------------|-------|
| Courses | Progress bars, difficulty | 3 | No |
| Quests | Countdown, difficulty | 3 | No |
| Leaderboard | Podium, sorting | 4 | No |
| Achievements | Grid, rarity | 3 | Yes |
| Progress | Metrics, timeline | Category | No |
| Rewards | Badges, milestones | No | No |
| Messages | Types, threads | 4 | Yes |
| Profile | Stats, edit | No | Yes |

---

## 🔐 Authentication & Authorization

### Middleware Protection
All routes use:
```php
->middleware(['auth', 'verified'])
```

### Get Current User
```php
$user = Auth::user();
```

### Check Authentication
```php
if (Auth::check()) {
    // User is authenticated
}
```

---

## 🐛 Debugging Tips

### Check Browser Console
```
F12 → Console → Look for errors
```

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Artisan Debugging
```bash
php artisan tinker
$user = User::first();
$user->enrollments()->get();
```

### Inertia DevTools
Open in browser: Check Network → Check Inertia responses

---

## 📈 Performance Tips

### Optimize Queries
```php
// Bad (N+1)
$users = User::all();
foreach ($users as $user) {
    $user->enrollments->count();
}

// Good (eager loading)
$users = User::with('enrollments')->get();
```

### Use Pagination
```php
$courses = $user->enrollments()
    ->paginate(15);
```

### Cache Leaderboard
```php
Cache::remember('leaderboard', 300, function () {
    return User::orderBy('xp', 'desc')->take(100)->get();
});
```

---

## 🎨 Styling Quick Tips

### Tailwind Classes
```html
<!-- Spacing -->
<div class="p-4 m-2">Padding and margin</div>

<!-- Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4">Grid</div>

<!-- Flexbox -->
<div class="flex items-center justify-between">Flex</div>

<!-- Colors -->
<div class="bg-accent text-accent-foreground">Accent</div>
<div class="bg-destructive text-destructive-foreground">Destructive</div>

<!-- Responsive -->
<div class="text-sm md:text-base lg:text-lg">Responsive text</div>

<!-- Dark Mode -->
<div class="dark:bg-gray-900 dark:text-white">Dark mode</div>
```

---

## 📱 Mobile Testing

### Toggle Device Toolbar
`F12 → Ctrl+Shift+M` (Windows) or `Cmd+Shift+M` (Mac)

### Test Breakpoints
- Mobile: 375px
- Tablet: 768px
- Desktop: 1024px+

### Checklist
- [ ] Text readable
- [ ] Buttons clickable
- [ ] No horizontal scroll
- [ ] Images load
- [ ] Modals display

---

## 🔗 Important Links

### Documentation
- `ALL_PAGES_SETUP.md` - Complete feature guide
- `ALL_PAGES_CHECKLIST.md` - Testing checklist
- `IMPLEMENTATION_COMPLETE.md` - Summary

### Frameworks
- [Laravel Docs](https://laravel.com/docs)
- [Inertia Docs](https://inertiajs.com)
- [Vue 3 Docs](https://vuejs.org)
- [Tailwind Docs](https://tailwindcss.com)
- [Lucide Icons](https://lucide.dev)

---

## ⚡ Common Routes

```php
// Authenticated user routes
GET  /profile       → Show user profile
GET  /courses       → Show courses
GET  /quests        → Show quests
GET  /leaderboard   → Show leaderboard
GET  /achievements  → Show achievements
GET  /progress      → Show progress
GET  /rewards       → Show rewards
GET  /messages      → Show messages

// Admin routes
GET  /admin/users   → User management
GET  /admin         → Admin panel
```

---

## 🚨 Common Issues & Solutions

### Page not loading?
1. Check if logged in
2. Check URL spelling
3. Check browser console for errors
4. Check Laravel logs: `tail -f storage/logs/laravel.log`

### Styling not applied?
1. Clear cache: `npm run dev` (restart)
2. Clear browser cache: `Ctrl+Shift+Delete`
3. Check class name spelling
4. Check dark mode toggle

### Data not displaying?
1. Check controller returns correct data
2. Check Vue prop names match data keys
3. Check console for errors
4. Verify array/object structure

### Route not found?
1. Check route is in `routes/web.php`
2. Verify controller exists
3. Check middleware requirements
4. Restart Laravel server

---

## ✅ Pre-Deployment Checklist

- [ ] All pages accessible
- [ ] No console errors
- [ ] Mobile responsive
- [ ] Dark mode works
- [ ] All links navigate correctly
- [ ] Sample data displays
- [ ] Performance acceptable
- [ ] Documentation reviewed
- [ ] Database integration plan
- [ ] API endpoints identified

---

## 📞 Quick Commands

```bash
# Start development
php artisan serve
npm run dev

# Run tests
php artisan test

# Check logs
tail -f storage/logs/laravel.log

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Tinker (interactive shell)
php artisan tinker
```

---

## 🎯 Next Steps

1. ✅ Review documentation
2. ✅ Test all pages
3. ✅ Check responsive design
4. ⏭️ Connect to database
5. ⏭️ Implement real features
6. ⏭️ Deploy to production

---

**Last Updated**: December 2024
**Version**: 1.0
**Status**: Ready to Use
