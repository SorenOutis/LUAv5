<?php

namespace App\Filament\Widgets;

use App\Models\Achievement;
use Filament\Widgets\ChartWidget;

class AchievementDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Achievement Unlock Rate';

    protected function getData(): array
    {
        $achievements = Achievement::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(8)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Unlocked by Players',
                    'data' => $achievements->pluck('users_count')->toArray(),
                    'backgroundColor' => 'rgba(168, 85, 247, 0.8)',
                    'borderColor' => '#a855f7',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $achievements->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
