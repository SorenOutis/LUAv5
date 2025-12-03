<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Fix existing profiles
$users = User::with('profile')->get();
foreach ($users as $user) {
    $profile = $user->profile;
    if ($profile) {
        $correctLevel = max(1, intval($profile->total_xp / 100) + 1);
        $correctCurrentXp = $profile->total_xp % 100;
        
        if ($profile->level != $correctLevel || $profile->current_level_xp != $correctCurrentXp) {
            $profile->update([
                'level' => $correctLevel,
                'current_level_xp' => $correctCurrentXp,
            ]);
            echo "Fixed {$user->name}: XP={$profile->total_xp}, Level={$correctLevel}\n";
        }
    }
}

echo "Done!\n";
