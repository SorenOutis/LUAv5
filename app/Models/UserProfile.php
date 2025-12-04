<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\RankingTier;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'total_xp',
        'level',
        'current_level_xp',
        'xp_for_next_level',
        'streak_days',
        'last_activity_date',
        'rank_title',
    ];

    protected $casts = [
        'last_activity_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the rank title based on the user's current level
     */
    public function calculateRankTitle(): string
    {
        $rankPosition = $this->levelToRankPosition($this->level);
        $tier = $this->getRankTier($rankPosition);
        return $tier['name'];
    }

    /**
     * Convert user level to a rank position for tier matching
     */
    private function levelToRankPosition(int $level): int
    {
        return match (true) {
            $level >= 25 => 1,      // Supreme
            $level >= 20 => 6,      // Eternal
            $level >= 15 => 16,     // Apex
            $level >= 12 => 26,     // Diamond
            $level >= 10 => 36,     // Platinum
            $level >= 7 => 46,      // Gold
            $level >= 5 => 61,      // Silver
            $level >= 3 => 81,      // Bronze
            $level >= 2 => 101,     // Iron
            default => 131,         // Plastic
        };
    }

    /**
     * Get rank tier by rank position
     */
    private function getRankTier(int $rank): array
    {
        $tier = RankingTier::where('min_rank', '<=', $rank)
            ->where(function ($query) use ($rank) {
                $query->whereNull('max_rank')
                    ->orWhere('max_rank', '>=', $rank);
            })
            ->orderBy('sort_order', 'asc')
            ->first();

        if (!$tier) {
            $tier = RankingTier::orderBy('sort_order', 'desc')->first();
        }

        if (!$tier) {
            return [
                'id' => 0,
                'name' => 'Unranked',
                'icon' => null,
                'color' => '#gray',
                'minRank' => 0,
                'maxRank' => null,
            ];
        }

        return [
            'id' => $tier->id,
            'name' => $tier->name,
            'icon' => $tier->icon,
            'color' => $tier->color,
            'minRank' => $tier->min_rank,
            'maxRank' => $tier->max_rank,
        ];
    }
}
