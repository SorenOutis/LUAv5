<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AchievementsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $achievements = Achievement::where('is_active', true)
            ->with('users')
            ->get();

        $allAchievements = $achievements->map(function($achievement) use ($user) {
            $unlockedAt = $achievement->users->find($user->id)?->pivot->unlocked_at;
            $formattedDate = null;
            
            if ($unlockedAt) {
                $formattedDate = is_string($unlockedAt) ? $unlockedAt : $unlockedAt->format('M d, Y');
            }
            
            return [
                'id' => $achievement->id,
                'name' => $achievement->name,
                'description' => $achievement->description,
                'icon' => $achievement->icon,
                'category' => $achievement->category,
                'difficulty' => $achievement->difficulty,
                'xp_reward' => $achievement->xp_reward,
                'unlocked' => $achievement->users->contains($user->id),
                'unlockedAt' => $formattedDate,
            ];
        });

        $unlockedCount = $allAchievements->filter(fn($a) => $a['unlocked'])->count();
        $totalCount = $allAchievements->count();

        return Inertia::render('Achievements', [
            'allAchievements' => $allAchievements,
            'stats' => [
                'totalUnlocked' => $unlockedCount,
                'totalAchievements' => $totalCount,
                'completionPercentage' => $totalCount > 0 ? round(($unlockedCount / $totalCount) * 100) : 0,
                'xpEarned' => $allAchievements
                    ->filter(fn($a) => $a['unlocked'])
                    ->sum('xp_reward'),
            ],
        ]);
    }
}
