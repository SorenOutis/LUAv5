<?php

namespace App\Filament\Widgets;

use App\Models\Quest;
use Filament\Widgets\ChartWidget;

class QuestCompletionChart extends ChartWidget
{
    protected ?string $heading = 'Quests by Difficulty';

    protected function getData(): array
    {
        $quests = Quest::selectRaw('difficulty, COUNT(*) as count')
            ->groupBy('difficulty')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Quest Count',
                    'data' => $quests->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#f97316',
                        '#eab308',
                        '#84cc16',
                    ],
                    'borderColor' => '#fff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $quests->pluck('difficulty')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
