# Achievement XP Integration - Usage Examples

## Quick Start

### Basic Usage

```php
use App\Services\AchievementUnlocker;
use App\Models\User;
use App\Models\Achievement;

// Get the service
$unlocker = app(AchievementUnlocker::class);

// Unlock achievement (automatically adds XP)
$user = User::find(1);
$achievement = Achievement::find(1); // "First Steps" (+50 XP)

$unlocked = $unlocker->unlock($user, $achievement);

// Check if it was unlocked
if ($unlocked) {
    echo "Achievement unlocked! +{$achievement->xp_reward} XP";
    echo "New total XP: {$user->profile->total_xp}";
}
```

---

## Real-World Scenarios

### Scenario 1: Unlock Achievement on Lesson Completion

```php
// In LessonController or LessonCompletionObserver
use App\Services\AchievementUnlocker;

public function completeLesson(Lesson $lesson)
{
    // ... existing lesson completion logic ...

    $user = auth()->user();
    $totalLessons = $user->lessonCompletions()->count();

    // Award "First Steps" achievement on first lesson
    if ($totalLessons === 1) {
        $unlocker = app(AchievementUnlocker::class);
        $achievement = Achievement::where('name', 'First Steps')->first();
        if ($achievement) {
            $unlocker->unlock($user, $achievement);
        }
    }

    // Award "Quick Learner" on 5 lessons in one day
    if ($totalLessons % 5 === 0) {
        $unlocker = app(AchievementUnlocker::class);
        $achievement = Achievement::where('name', 'Quick Learner')->first();
        if ($achievement) {
            $unlocker->unlock($user, $achievement);
        }
    }
}
```

### Scenario 2: Unlock Achievement on Streak Milestone

```php
// In StreakService or StreakObserver
use App\Services\AchievementUnlocker;

public function checkStreakMilestone(Streak $streak)
{
    $user = $streak->user;
    $unlocker = app(AchievementUnlocker::class);

    // 7-day streak: "On Fire"
    if ($streak->current_streak === 7) {
        $achievement = Achievement::where('name', 'On Fire')->first();
        $unlocker->unlock($user, $achievement);
    }

    // 30-day streak: "Unstoppable"
    if ($streak->current_streak === 30) {
        $achievement = Achievement::where('name', 'Unstoppable')->first();
        $unlocker->unlock($user, $achievement);
    }

    // 100-day streak: "Legendary Dedication"
    if ($streak->current_streak === 100) {
        $achievement = Achievement::where('name', 'Legendary Dedication')->first();
        $unlocker->unlock($user, $achievement);
    }
}
```

### Scenario 3: Unlock Achievement on Course Completion

```php
// In CourseCompletionController or CourseObserver
use App\Services\AchievementUnlocker;

public function completeCourse(CourseEnrollment $enrollment)
{
    // ... existing completion logic ...

    $user = $enrollment->user;
    $completedCourses = $user->enrollments()
        ->where('completion_status', 'completed')
        ->count();

    $unlocker = app(AchievementUnlocker::class);

    // "Course Master" on first course completion
    if ($completedCourses === 1) {
        $achievement = Achievement::where('name', 'Course Master')->first();
        $unlocker->unlock($user, $achievement);
    }
}
```

### Scenario 4: Unlock Achievement on Perfect Score

```php
// In AssessmentSubmissionController
use App\Services\AchievementUnlocker;

public function submitAssessment(Assessment $assessment, Request $request)
{
    // ... grade assessment ...
    $score = $this->calculateScore($request);
    $user = auth()->user();

    // Award "Perfect Score" for 100%
    if ($score === 100) {
        $unlocker = app(AchievementUnlocker::class);
        $achievement = Achievement::where('name', 'Perfect Score')->first();
        if ($achievement) {
            $unlocker->unlock($user, $achievement);
        }
    }
}
```

### Scenario 5: Unlock Achievement on Level Milestone

```php
// Check after any XP award (lesson, assignment, etc.)
use App\Services\AchievementUnlocker;

public function checkLevelMilestones(User $user)
{
    $level = $user->profile->level;
    $unlocker = app(AchievementUnlocker::class);

    // Level 5: "Level Up"
    if ($level >= 5) {
        $achievement = Achievement::where('name', 'Level Up')->first();
        $unlocker->unlock($user, $achievement);
    }

    // Level 25: "Rising Star"
    if ($level >= 25) {
        $achievement = Achievement::where('name', 'Rising Star')->first();
        $unlocker->unlock($user, $achievement);
    }

    // Level 50: "Peak Performance"
    if ($level >= 50) {
        $achievement = Achievement::where('name', 'Peak Performance')->first();
        $unlocker->unlock($user, $achievement);
    }
}
```

### Scenario 6: Unlock Achievement on XP Milestone

```php
// Check after XP awards
use App\Services\AchievementUnlocker;

public function checkXPMilestones(User $user)
{
    $totalXP = $user->profile->total_xp;
    $unlocker = app(AchievementUnlocker::class);

    // 1000 XP: "XP Collector"
    if ($totalXP >= 1000) {
        $achievement = Achievement::where('name', 'XP Collector')->first();
        $unlocker->unlock($user, $achievement);
    }

    // 10000 XP: "XP Master"
    if ($totalXP >= 10000) {
        $achievement = Achievement::where('name', 'XP Master')->first();
        $unlocker->unlock($user, $achievement);
    }

    // 50000 XP: "XP Legend"
    if ($totalXP >= 50000) {
        $achievement = Achievement::where('name', 'XP Legend')->first();
        $unlocker->unlock($user, $achievement);
    }
}
```

---

## Admin/Manual Unlocking

### From Admin Panel - Create Custom Command

```php
// app/Console/Commands/UnlockAchievementCommand.php
<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Achievement;
use App\Services\AchievementUnlocker;
use Illuminate\Console\Command;

class UnlockAchievementCommand extends Command
{
    protected $signature = 'achievement:unlock {user_id} {achievement_id}';
    protected $description = 'Unlock an achievement for a user';

    public function handle()
    {
        $user = User::find($this->argument('user_id'));
        $achievement = Achievement::find($this->argument('achievement_id'));

        if (!$user || !$achievement) {
            $this->error('User or Achievement not found');
            return;
        }

        $unlocker = app(AchievementUnlocker::class);
        if ($unlocker->unlock($user, $achievement)) {
            $this->info("Achievement '{$achievement->name}' unlocked for {$user->name}");
            $this->info("XP awarded: +{$achievement->xp_reward}");
        } else {
            $this->warn("Achievement already unlocked for this user");
        }
    }
}
```

Usage:
```bash
php artisan achievement:unlock 2 1
# Achievement 'First Steps' unlocked for John Doe
# XP awarded: +50
```

### Via Tinker

```bash
php artisan tinker
```

```php
$user = User::find(2);
$achievement = Achievement::find(1);

$unlocker = app(App\Services\AchievementUnlocker::class);
$unlocker->unlock($user, $achievement);

echo $user->profile->total_xp; // 50
```

### Bulk Unlock Multiple Achievements

```php
use App\Services\AchievementUnlocker;

$user = User::find(2);
$achievements = Achievement::whereIn('id', [1, 2, 3])->get();

$unlocker = app(AchievementUnlocker::class);
$results = $unlocker->unlockMultiple($user, $achievements);

foreach ($results as $achievementId => $wasUnlocked) {
    echo "Achievement $achievementId: " . ($wasUnlocked ? "Unlocked" : "Already owned");
}
```

---

## XP Verification & Fixes

### Check User XP

```php
$user = User::find(2);
$profile = $user->profile;

echo "Total XP: {$profile->total_xp}";
echo "Level: {$profile->level}";
echo "Current Level XP: {$profile->current_level_xp}";

// Verify achievements
$achievements = $user->achievements()->get();
echo "Achievements earned: {$achievements->count()}";

$totalAchievementXP = $achievements->sum('xp_reward');
echo "Total Achievement XP: {$totalAchievementXP}";
```

### Sync All Achievement XP for User

```php
use App\Services\AchievementUnlocker;

$user = User::find(2);
$unlocker = app(AchievementUnlocker::class);
$unlocker->syncAllAchievementXP($user);

echo "XP synced! New total: {$user->profile->total_xp}";
```

### Sync All Users Achievement XP

```php
use App\Services\AchievementUnlocker;

$unlocker = app(AchievementUnlocker::class);

User::all()->each(function ($user) use ($unlocker) {
    $unlocker->syncAllAchievementXP($user);
});

echo "All users synced!";
```

---

## Testing

### Unit Test Example

```php
// tests/Unit/AchievementXPTest.php
<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Achievement;
use App\Services\AchievementUnlocker;
use Tests\TestCase;

class AchievementXPTest extends TestCase
{
    public function test_unlocking_achievement_awards_xp()
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

    public function test_cannot_unlock_same_achievement_twice()
    {
        $user = User::factory()->create();
        $achievement = Achievement::first();

        $unlocker = app(AchievementUnlocker::class);
        
        $first = $unlocker->unlock($user, $achievement);
        $second = $unlocker->unlock($user, $achievement);

        $this->assertTrue($first);
        $this->assertFalse($second);
    }

    public function test_level_calculation_on_xp_award()
    {
        $user = User::factory()->create();
        $user->profile->update(['total_xp' => 100]);

        $achievement = Achievement::factory()->create(['xp_reward' => 100]);

        $unlocker = app(AchievementUnlocker::class);
        $unlocker->unlock($user, $achievement);

        $user->profile->refresh();
        $this->assertEquals(200, $user->profile->total_xp);
        $this->assertEquals(3, $user->profile->level); // (200 / 100) + 1
    }
}
```

Run tests:
```bash
php artisan test tests/Unit/AchievementXPTest.php
```

---

## Integration Checklist

- [ ] Review AchievementUnlocker service
- [ ] Review examples above
- [ ] Identify where achievements should auto-unlock
- [ ] Implement auto-unlock logic in your controllers
- [ ] Create unit tests for your implementation
- [ ] Test in development environment
- [ ] Verify XP updates in database
- [ ] Test level calculations
- [ ] Deploy to production

---

## Summary

Use `AchievementUnlocker` service:
```php
$unlocker = app(AchievementUnlocker::class);
$unlocker->unlock($user, $achievement);
```

That's it! XP is automatically awarded and recorded.

**Ready to integrate!**
