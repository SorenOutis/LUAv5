<?php

namespace Database\Seeders;

use App\Models\RankingTier;
use Illuminate\Database\Seeder;

class RankingTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'SUPREME',
                'icon' => '🏆',
                'color' => '#FFD700',
                'min_rank' => 1,
                'max_rank' => 5,
                'description' => 'The absolute elite. Top 5 players.',
                'sort_order' => 1,
            ],
            [
                'name' => 'ETERNAL',
                'icon' => '🔥',
                'color' => '#FF1493',
                'min_rank' => 6,
                'max_rank' => 15,
                'description' => 'Exceptional skill and dedication. Top 6-15 players.',
                'sort_order' => 2,
            ],
            [
                'name' => 'APEX',
                'icon' => '⭐',
                'color' => '#4169E1',
                'min_rank' => 16,
                'max_rank' => 25,
                'description' => 'Outstanding performance. Top 16-25 players.',
                'sort_order' => 3,
            ],
            [
                'name' => 'DIAMOND',
                'icon' => '💎',
                'color' => '#00CED1',
                'min_rank' => 26,
                'max_rank' => 35,
                'description' => 'High skill level. Top 26-35 players.',
                'sort_order' => 4,
            ],
            [
                'name' => 'PLATINUM',
                'icon' => '🪨',
                'color' => '#A9A9A9',
                'min_rank' => 36,
                'max_rank' => 45,
                'description' => 'Strong performance. Top 36-45 players.',
                'sort_order' => 5,
            ],
            [
                'name' => 'GOLD',
                'icon' => '🥇',
                'color' => '#FFD700',
                'min_rank' => 46,
                'max_rank' => 60,
                'description' => 'Above average. Top 46-60 players.',
                'sort_order' => 6,
            ],
            [
                'name' => 'SILVER',
                'icon' => '🔶',
                'color' => '#C0C0C0',
                'min_rank' => 61,
                'max_rank' => 80,
                'description' => 'Decent progress. Top 61-80 players.',
                'sort_order' => 7,
            ],
            [
                'name' => 'BRONZE',
                'icon' => '🥉',
                'color' => '#CD7F32',
                'min_rank' => 81,
                'max_rank' => 100,
                'description' => 'Getting started. Top 81-100 players.',
                'sort_order' => 8,
            ],
            [
                'name' => 'IRON',
                'icon' => '⚙️',
                'color' => '#696969',
                'min_rank' => 101,
                'max_rank' => 130,
                'description' => 'Beginner level. Top 101-130 players.',
                'sort_order' => 9,
            ],
            [
                'name' => 'PLASTIC',
                'icon' => '🗑️',
                'color' => '#A0A0A0',
                'min_rank' => 131,
                'max_rank' => null,
                'description' => 'Entry level. Ranked 131+.',
                'sort_order' => 10,
            ],
        ];

        foreach ($tiers as $tier) {
            RankingTier::updateOrCreate(
                ['name' => $tier['name']],
                $tier
            );
        }
    }
}
