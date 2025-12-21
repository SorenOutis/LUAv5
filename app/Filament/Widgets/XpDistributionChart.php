<?php

namespace App\Filament\Widgets;

use App\Models\UserProfile;
use Filament\Widgets\ChartWidget;

class XpDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Total XP Distribution by User';

    protected function getData(): array
    {
        $data = UserProfile::selectRaw('CASE 
                WHEN total_xp < 1000 THEN "0-1K"
                WHEN total_xp < 5000 THEN "1K-5K"
                WHEN total_xp < 10000 THEN "5K-10K"
                WHEN total_xp < 25000 THEN "10K-25K"
                WHEN total_xp < 50000 THEN "25K-50K"
                ELSE "50K+"
            END as xp_range, COUNT(*) as count')
            ->groupBy('xp_range')
            ->get()
            ->sortBy(function ($item) {
                $order = ["0-1K" => 0, "1K-5K" => 1, "5K-10K" => 2, "10K-25K" => 3, "25K-50K" => 4, "50K+" => 5];
                return $order[$item->xp_range] ?? 999;
            })
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'User Count',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#f97316',
                        '#eab308',
                        '#84cc16',
                        '#22c55e',
                        '#10b981',
                    ],
                    'borderColor' => '#fff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $data->pluck('xp_range')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
