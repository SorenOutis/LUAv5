<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::with('profile')->get();
foreach ($users as $user) {
    $profile = $user->profile;
    echo "User: {$user->name}\n";
    echo "  Total XP: " . ($profile->total_xp ?? 'NULL') . "\n";
    echo "  Level: " . ($profile->level ?? 'NULL') . "\n";
    echo "  Current Level XP: " . ($profile->current_level_xp ?? 'NULL') . "\n";
    echo "  Calculated Level: " . max(1, intval(($profile->total_xp ?? 0) / 100) + 1) . "\n";
    echo "\n";
}
