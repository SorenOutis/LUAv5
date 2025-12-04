<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Console\Kernel');

$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Get current user or first user
$user = Auth::user() ?? User::first();

if (!$user) {
    echo "No user found\n";
    exit;
}

echo "=== User Debug Info ===\n";
echo "User ID: {$user->id}\n";
echo "User Name: {$user->name}\n";
echo "User Email: {$user->email}\n";

// Check profile
if ($user->profile) {
    echo "Profile Found: Yes\n";
    echo "Total XP: {$user->profile->total_xp}\n";
    echo "Level: {$user->profile->level}\n";
} else {
    echo "Profile Found: No\n";
}

// Check roles
$roles = $user->roles->pluck('name')->implode(', ');
echo "Roles: " . ($roles ?: 'None') . "\n";

// Check role exclusion
$hasAdminRoles = $user->hasAnyRole(['admin', 'staff', 'teacher', 'super_admin']);
echo "Has Admin Roles: " . ($hasAdminRoles ? 'Yes' : 'No') . "\n";

// Check if excluded
echo "\n=== Leaderboard Filters ===\n";
echo "ID > 1: " . ($user->id > 1 ? 'Yes' : 'No') . "\n";
echo "Has Profile: " . ($user->profile ? 'Yes' : 'No') . "\n";
echo "Not Admin/Staff/Teacher: " . (!$hasAdminRoles ? 'Yes' : 'No') . "\n";

$willAppearOnLeaderboard = $user->id > 1 && $user->profile && !$hasAdminRoles;
echo "\n=== Will Appear on Leaderboard ===\n";
echo $willAppearOnLeaderboard ? 'YES' : 'NO';
echo "\n";

// Check all users on leaderboard
echo "\n=== All Leaderboard Users ===\n";
$leaderboardUsers = User::with('profile', 'streak')
    ->whereHas('profile')
    ->where('id', '>', 1)
    ->get()
    ->filter(function ($u) {
        return !$u->hasAnyRole(['admin', 'staff', 'teacher', 'super_admin']);
    });

echo "Total on Leaderboard: " . count($leaderboardUsers) . "\n";
foreach ($leaderboardUsers as $u) {
    echo "- ID:{$u->id} {$u->name} XP:{$u->profile->total_xp} Level:{$u->profile->level}\n";
}
