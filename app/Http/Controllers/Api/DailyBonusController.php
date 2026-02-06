<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DailyLoginBonusService;
use Illuminate\Http\JsonResponse;

class DailyBonusController extends Controller
{
    /**
     * Claim daily login bonus
     */
    public function claim(DailyLoginBonusService $service): JsonResponse
    {
        $user = auth()->user();
        $result = $service->awardDailyBonus($user);

        // Include streak information in response
        if ($result['success']) {
            $streak = $user->streak()->first();
            $result['streak'] = [
                'currentStreak' => $streak?->current_streak ?? 0,
                'longestStreak' => $streak?->longest_streak ?? 0,
            ];
        }

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
